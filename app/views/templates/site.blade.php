<!DOCTYPE html>
<html lang="en-us">
 <head>
	@include('templates.site.head')
	@yield('style')
</head>
<body>
    @include('templates.site.header')
    @yield('content')
    @include('templates.site.footer')
    @include('templates.site.scripts')
    @yield('scripts')
</body>
</html>