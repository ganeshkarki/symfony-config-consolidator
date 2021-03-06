@php
/** @var \Illuminate\Support\ViewErrorBag $errors */

$appPlaceHolder = 'stylesheets:
  - /css/main: { media: all }
  - /css/secondary.css

javascripts:
  - /js/lib/jquery-3.3.1.min.js: {}
  - /js/secondary.js';

$modulePlaceHolder = 'stylesheets:
  - /css/third: { media: all }

javascripts:
  - /js/third.js';

$pagePlaceHolder = 'stylesheets:
  - -/css/secondary.css

javascripts:
  - -/js/secondary.js';

$extraPlaceHolder = 'stylesheets:
  - /css/special.css

javascripts:
  - /js/special.js';

@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Config Consolidator</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            margin: 0;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .subtitle {
            font-size: 13px;
            color: #636b6f;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .error {
            color: #FF0000;
            font-weight: 600;
        }

        .no-underline-link {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref">
    <div class="content">
        <div class="title">
            Config Consolidator
        </div>
        <div class="subtitle m-b-md">
            For stylesheets and javascripts (Symfony 1.4 - view.yml)
        </div>
        <a class="no-underline-link" href="https://github.com/ganeshkarki/symfony-config-consolidator">
            <img src="https://github.githubassets.com/images/modules/logos_page/Octocat.png" height="32" width="32">
        </a>
        <br>
        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $errorMessage)
                    {!! $errorMessage !!}
                @endforeach
            </div>
        @endif
        <form method="post">
            {{ csrf_field() }}
            <input type="checkbox" name="transform" value="yes" checked /> Transform "- - subItem" to "- -subItem"<br><br>
            <label for="app-config">App Level Config (YAML)</label>
            <br>
            <textarea id="app-config" name="app-config" rows="10" cols="100" placeholder="{{ $appPlaceHolder }}}" required>{{
                $inputConfig['app'] ?? null
            }}</textarea>
            <br><br>
            <label for="module-config">Module Level Default Config (YAML)</label>
            <br>
            <textarea id="module-config" name="module-config" rows="10" cols="100" placeholder="{{ $modulePlaceHolder }}}" >{{
                $inputConfig['module'] ?? null
            }}</textarea>
            <br><br>
            <label for="page-config">Page Level Config (YAML)</label>
            <br>
            <textarea id="page-config" name="page-config" rows="10" cols="100" placeholder="{{ $pagePlaceHolder }}}" >{{
                $inputConfig['page'] ?? null
            }}</textarea>
            <br><br>
            <label for="extra-config">Layout modified settings (YAML)</label>
            <br>
            <textarea id="extra-config" name="extra-config" rows="5" cols="100" placeholder="{{ $extraPlaceHolder }}}" >{{
                $inputConfig['extra'] ?? null
            }}</textarea>
            <br>
            <input type="submit" value="Merge">
            <br><br>
        </form>

        @if(!empty($mergeResults))
            <div class="result">
                <label for="merged-results">Results (YAML)</label>
                <br>
                <textarea id="merged-results" name="merged-results" rows="10" cols="100" readonly>{!! $mergeResults !!}</textarea>
                <br><br>
            </div>
        @endif
    </div>
</div>
</body>
</html>
