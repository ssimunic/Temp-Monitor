<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#2196f3">
	<meta name="description" content="Internet of Things data platform for temperature and humidity sensors with maps">
	<meta name="keywords" content="tempmonitor, iot, internet of things, iot platform, internet of things platform, iot data platform, internter of things data platform">
	<title>{{ $_ENV['WEBNAME'] }}</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<link href="{{ asset('/css/paper.min.css') }}" rel="stylesheet">

</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}">{{ $_ENV['WEBNAME'] }}</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li @if(Request::path() == 'home') class="active" @endif><a href="{{ url('/home') }}">Home</a></li>
{{--					<li @if(Request::path() == 'getstarted') class="active" @endif><a href="{{ url('/getstarted') }}">Get started</a></li>--}}
					@if(Auth::user())
					<li @if(Request::path() == 'sensor') class="active" @endif><a href="{{ url('/sensor') }}">Sensors</a></li>
                    <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Maps<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li @if(Request::path() == 'map/basic') class="active" @endif><a href="{{ url('/map/basic') }}">Basic</a></li>
						        <li @if(Request::path() == 'map/custom') class="active" @endif><a href="{{ url('/map/custom') }}">Custom</a></li>
							</ul>
				    </li>
					@endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li  @if(Request::path() == 'auth/login') class="active" @endif><a href="{{ url('/auth/login') }}">Login</a></li>
						<li  @if(Request::path() == 'auth/register') class="active" @endif><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/profile') }}">Profile</a></li>
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
    <div class="background"></div>

	@yield('content')
    <style>
    html {
        position: relative;
        min-height: 100%;
    }

    body {
        padding-top: 87px;
        margin-bottom: 60px;
    }
    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 60px;
        background-color: #f5f5f5;
    }

    body > .container {
        padding: 60px 15px 0;
    }

    .container .text-muted {
        margin: 18px 0;
    }

    .footer > .container {
        padding-right: 15px;
        padding-left: 15px;
    }

	th, label, b{
		font-weight: 500;
	}


    </style>
    <footer class="footer">
      <div class="container" style="width: 100%;">
        <p class="text-muted text-center">Copyright <a target="_blank" href="http://silviosimunic.com/" style="color: #bbbbbb">Silvio Simunic</a> &copy; {{ date('Y') }}.</p>
      </div>
    </footer>

	<script>

        $(document).ready(function(){
            $('input[type="checkbox"]').click(function(){
               $('.alerts').toggle(200);
            });

             $(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
             });
        });
	</script>
</body>
</html>
