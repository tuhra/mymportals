<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checking Subscriber</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="v3TwdJDYhw8dGeKkTzypt04ggz344PZWr1t3tlmg">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description">
    <meta name="author">
    <meta name="tags">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web/css/landing/landing-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/landing/landing-main.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/landing/landing.css') }}">
<style>
    .landing-cover img{
        border-radius: 30px;

    }
    .landing-cover .btn{
        border:none;
        font-size: 18px;
        width:100px;
        text-align: center;
    }
    .msisdn {
        border:none;
        font-size: 18px;
        width:400px;
        text-align: center;   
    }
</style>
    </head>
<body>

    @yield('content')
    <input type="hidden" id="continue-url" value="{{ url('continue') }}">
    <input type="hidden" id="home-url" value="{{ url('msisdn') }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '#continue-btn', function () {
                var url = $('#continue-url').val();
                window.location.href = url;
            })
            $(document).on('click', '#home-btn', function () {
                var url = $('#home-url').val();
                window.location.href = url;
            })
        })
    </script>
</body>
</html>
