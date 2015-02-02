<!doctype html>
<html lang="en">
<head>
	<title>Boots</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="description" lang="en" content="" />
	<meta name="robots" content="noindex, nofollow" />
	<!--link rel="shortcut icon" href="favicon.ico" /-->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	{{ HTML::style('packages/cloudraker/boots/css/index.css') }}
	{{ HTML::style(Config::get('boots::boots.file_css')) }}
	@yield('css')
</head>
<body>

@yield('body')

{{ HTML::script('http://code.jquery.com/jquery-2.1.3.min.js') }}
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
@yield('js')
{{-- HTML::script('js/index.js') --}}
</body>
</html>
