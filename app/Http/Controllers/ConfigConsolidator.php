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
    private $extraConfigList = [];
    private $moduleConfigList = [];

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke (Request $request)
    {
        $consolidatedList = [];

        if ($request->method() === Request::METHOD_POST) {
            // TODO: Form Validation

            $appLevelConfig = $request->input('app-yaml');
            $moduleLevelConfig = $request->get('module-yaml');
            $extraAddition = $request->get('manually-added-items', '');

            try {
                $yaml = new Parser();

                $this->appConfigList = $yaml->parse($appLevelConfig);
                $this->moduleConfigList = $yaml->parse($moduleLevelConfig ?? '');
                $this->extraConfigList = $yaml->parse($extraAddition ?? '');

                $consolidatedList = $this->getConsolidatedList();

            } catch (ParseException $parseException) {
                Log::alert($parseException->getMessage());
                // TODO: show error in frontend
            } catch (\Exception $e) {
                Log::alert('Something went wrong:' . $e->getMessage());
            }

            $consolidatedList = $this->getYamlString($consolidatedList);
            // dump($consolidatedList);
        }

        return view('config_consolidator',['mergeResults' => $consolidatedList]);
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

        if (!empty($this->extraConfigList)) {
            $this->mergeConfigs($configList, $this->extraConfigList);
        }

        return $configList;
    }

    private function mergeConfigs(&$configList, $newConfig): void
    {
        foreach ($newConfig as $item => $subItem) {
            foreach ($subItem as $subItemString) {
                if ($subItemString[0] === '-') {
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
}
