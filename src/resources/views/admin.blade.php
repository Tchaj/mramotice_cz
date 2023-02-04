<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env("APP_NAME")}}</title>
    <link href="{{ asset('css/app.css') }}?ver={{ uniqid() }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">

</head>

<body>

<div id="app" app-data="true">
    <Admin></Admin>
</div>

<script src="{{ asset('js/app.js') }}?ver={{ uniqid() }}"></script>
</body>

</html>
