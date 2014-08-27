<!DOCTYPE html>
<html lang="en-us" id="lock-page">
<head>
    <meta charset="utf-8">
    <title>{{{(isset($page_title))?$page_title:Config::get('app.default_page_title')}}}</title>
    <meta name="description" content="{{{(isset($page_description))?$page_description:''}}}">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@if(Config::get('app.use_css_local'))
	{{ HTML::style('css/bootstrap.min.css') }}
@else
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
@endif
    {{ HTML::style('css/font-awesome.min.css') }}
    {{ HTML::style('css/production.css') }}
    {{ HTML::style('css/production-skins.min.css') }}
    {{ HTML::style('css/lockscreen.min.css') }}
    <link rel="shortcut icon" href="{{asset('img/favicon/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('img/favicon/favicon.ico')}}" type="image/x-icon">
@if(Config::get('app.use_googlefonts'))
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
@endif
</head>
<body>
    <div id="main" role="main">
        <div class="lockscreen animated flipInY">
            <div class="logo">
                <h1 class="semi-bold">Здравствуйте!</h1>
            </div>
            <div>
                <div>
                    <h2>Спасибо что пользуетесь нашей системой.</h2>
                    <p class="no-margin margin-top-5">
                        Пожалуйста {{ HTML::link('login','авторизуйтесь') }} чтобы начать работу.</p>
                    </p>
                </div>

            </div>
            <p class="font-xs margin-top-5">
                Copyright Grapheme 2014.
            </p>
        </div>
    </div>
    {{HTML::script('js/plugin/pace/pace.min.js');}}
@if(Config::get('app.use_scripts_local'))
	{{HTML::script('js/vendor/jquery.min.js');}}
@else
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
@endif
    <script> if (!window.jQuery.ui) { document.write('<script src="{{asset('js/vendor/jquery-ui.min.js');}}"><\/script>');} </script>
	{{HTML::script('js/vendor/bootstrap.min.js');}}
	{{HTML::script('js/system/main.js');}}
	{{HTML::script('js/vendor/SmartNotification.min.js');}}
	{{HTML::script('js/vendor/jquery.validate.min.js');}}
	{{HTML::script('js/system/app.js');}}
    <!--[if IE 8]>
        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
    <![endif]-->
</body>
</html>