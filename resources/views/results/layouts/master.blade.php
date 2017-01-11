<html>
<head>

    <title>BietResults @yield('title')</title>

    <link href="{{URL::to('webassets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <meta name="og:title" content="Advanced Result Viewer (test version)"/>
    <meta name="og:description" content="An advanced result viewer for BIET Jhansi with many accessibility features."/>
    <meta name="og:image" content="http://blog.cakart.in.s3-us-west-2.amazonaws.com/blog/wp-content/uploads/2016/02/05080013/sbtet-results.png"/>


    {{--custom page specific layouts--}}
    @yield('styles')
</head>

<body>


    @yield('content')


<script src="{{URL::to('webassets/js/bootstrap.js')}}"></script>
@yield('scripts')
</body>
</html>