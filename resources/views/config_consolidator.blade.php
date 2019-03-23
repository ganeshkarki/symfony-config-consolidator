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
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
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
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            Config Consolidator
        </div>
        <form method="post">
            {{ csrf_field() }}
            <label for="app-yaml">App Level Config</label>
            <br>
            <textarea id="app-yaml" name="app-yaml" rows="5" cols="100" required></textarea>
            <br><br>
            <label for="module-yaml">Module Level Config</label>
            <br>
            <textarea id="module-yaml" name="module-yaml" rows="5" cols="100"></textarea>
            <br><br>
            <label for="manually-added-items">Manually added settings (yaml format)</label>
            <br>
            <textarea id="manually-added-items" name="manually-added-items" rows="5" cols="100"></textarea>
            <br>
            <input type="submit" value="Merge">
        </form>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $errorMessage)
                    {!! $errorMessage !!}
                @endforeach
            </div>
        @endif

        @if(!empty($mergeResults))
            <div class="result">
                <label for="merged-results">Results</label>
                <br>
                <textarea id="merged-results" name="merged-results" rows="5" cols="100" readonly>
                    {!! $mergeResults !!}
                </textarea>
            </div>
        @endif
    </div>
</div>
</body>
</html>
