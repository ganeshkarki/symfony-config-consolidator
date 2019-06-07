<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class ConfigConsolidator extends Controller
{
    private $appConfigList = [];
    private $moduleConfigList = [];
    private $pageConfigList = [];
    private $extraConfigList = [];

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function __invoke (Request $request)
    {
        $consolidatedList = [];

        if ($request->method() === Request::METHOD_POST) {
            $appLevelConfig = $request->input('app-config');
            $moduleLevelConfig = $request->input('module-config');
            $pageLevelConfig = $request->input('page-config');
            $extraAddition = $request->input('extra-config');

            if ($request->input('transform', 'no') === 'yes') {
                $this->transformDoubleDashToStringWithDashBefore($appLevelConfig);
                $this->transformDoubleDashToStringWithDashBefore($moduleLevelConfig);
                $this->transformDoubleDashToStringWithDashBefore($pageLevelConfig);
                $this->transformDoubleDashToStringWithDashBefore($extraAddition);
            }

            session([
                'inputConfig' => [
                    'app' => $appLevelConfig,
                    'module' => $moduleLevelConfig,
                    'page' => $pageLevelConfig,
                    'extra' => $extraAddition
                ]
            ]);

            try {
                $yaml = new Parser();

                $this->appConfigList = $yaml->parse($appLevelConfig);
                $this->moduleConfigList = $yaml->parse($moduleLevelConfig ?? '');
                // TODO: Allow to specify the modification using use_javascrpt() or use_stylesheet()
                //       helper methods instead of yaml formal for Page level and extraConfig
                $this->pageConfigList = $yaml->parse($pageLevelConfig ?? '');
                // TODO: rename extraConfigList to layoutConfigList
                $this->extraConfigList = $yaml->parse($extraAddition ?? '');

                $consolidatedList = $this->getConsolidatedList();

            } catch (ParseException $parseException) {
                Log::alert($parseException->getMessage());

                return redirect()->back()
                    ->withInput($request->all())
                    ->withErrors('Invalid YAML format');
            } catch (\Exception $e) {
                Log::alert('Something went wrong:' . $e->getMessage());

                return redirect()->back()
                    ->withInput($request->all())
                    ->withErrors('OOPS! Something went wrong. Check your input.');
            }

            $consolidatedList = $this->getYamlString($consolidatedList);
        }

        return view('config_consolidator',['mergeResults' => $consolidatedList, 'inputConfig' => session('inputConfig')]);
    }


    /**
     * Note: Only supports 1 level support
     */
    private function getConsolidatedList(): array
    {
        $configList = [];

        if (!empty($this->appConfigList)) {
            $this->mergeConfigs($configList, $this->appConfigList);
        }

        if (!empty($this->moduleConfigList)) {
            $this->mergeConfigs($configList, $this->moduleConfigList);
        }

        if (!empty($this->pageConfigList)) {
            $this->mergeConfigs($configList, $this->pageConfigList);
        }

        if (!empty($this->extraConfigList)) {
            $this->mergeConfigs($configList, $this->extraConfigList);
        }

        return $configList;
    }

    private function mergeConfigs(&$configList, $newConfig): void
    {
        foreach ($newConfig as $item => $subItem) {
            foreach ($subItem as $subItemString) {
                // If value is nested it returns array
                // Since we only need to path use the key instead
                if (is_array($subItemString)) {
                    $subItemString = key($subItemString);
                }

                // Strip unneeded chars or url params from path
                $subItemString = $this->getJsCssPathOnly($subItemString);

                if (strpos($subItemString, '-') === 0) {
                    $subItemHash = md5(substr($subItemString, 1));
                    unset($configList[$item][$subItemHash]);
                } else {
                    $subItemHash = md5($subItemString);
                    $configList[$item][$subItemHash] = $subItemString;
                }
            }
        }
    }

    private function getYamlString(array $list): string
    {
        $parsedList = [];
        foreach ($list as $item => $value){
            $parsedList[$item] = Arr::flatten($value);
        }

        try {
            $dumper = new Dumper();
            return $dumper->dump($parsedList, 2);

        } catch (\InvalidArgumentException $e) {
            Log::alert($e->getMessage());
        }

        return '';
    }

    private function transformDoubleDashToStringWithDashBefore(&$yamlString): void
    {
        $yamlString = str_replace('- - ', '- -', $yamlString);
    }

    private function getJsCssPathOnly(string $s)
    {
        preg_match('/(.*\.(css|js)).*$/', $s, $matches);

        return $matches[1] ?? '' ?: $s;
    }
}
