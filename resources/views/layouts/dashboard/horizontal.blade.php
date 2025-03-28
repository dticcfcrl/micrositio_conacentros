@props(['dir'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$dir ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{env('APP_NAME')}} | Web App Responsive</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">

    @include('partials.dashboard._head')
    <style>
        body {
            background-image:none;
        }
        #messageArea .chatbot .msg {
        background-color: red;
        color:red;
    }
    </style>
</head>
<body class="" >
@include('partials.dashboard._body2')
</body>
</html>
