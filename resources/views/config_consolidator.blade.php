@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
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
            <label for="app-config">App Level Config</label>
            <br>
            <textarea id="app-config" name="app-config" rows="10" cols="100" required>{{ $inputConfig['app'] ?? null }}</textarea>
            <br><br>
            <label for="module-config">Module Level Default Config</label>
            <br>
            <textarea id="module-config" name="module-config" rows="10" cols="100">{{ $inputConfig['module'] ?? null }}</textarea>
            <br><br>
            <label for="page-config">Page Level Config</label>
            <br>
            <textarea id="page-config" name="page-config" rows="10" cols="100">{{ $inputConfig['page'] ?? null }}</textarea>
            <br><br>
            <label for="extra-config">Manually added settings (yaml format)</label>
            <br>
            <textarea id="extra-config" name="extra-config" rows="5" cols="100">{{ $inputConfig['extra'] ?? null }}</textarea>
            <br>
            <input type="submit" value="Merge">
            <br><br>
        </form>

        @if(!empty($mergeResults))
            <div class="result">
                <label for="merged-results">Results</label>
                <br>
                <textarea id="merged-results" name="merged-results" rows="10" cols="100" readonly>{!! $mergeResults !!}</textarea>
                <br><br>
            </div>
        @endif
    </div>
</div>
</body>
</html>
