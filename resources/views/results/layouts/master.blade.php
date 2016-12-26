<html>
<head>

    <title>@yield('title')</title>

    <link href="{{URL::to('webassets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">




    {{--custom page specific layouts--}}
    @yield('styles')
</head>

<body>


    @yield('content')


<script src="{{URL::to('webassets/js/bootstrap.js')}}"></script>
</body>
</html>