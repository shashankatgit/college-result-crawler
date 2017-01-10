@section('styles')
    <style>
        .c-pager-container {
            background: rgba(36, 101, 131, 0.99);
            padding-top: 5px;
            margin-bottom: 2px;
            z-index: 5;
            position: absolute;
            top: 2px;
            width: 100%;
        }

        .nav-container {
            margin-right: 7%;
            margin-left: 7%;
        }

        .jump-container {
            position: relative;
            top: -10px;
        }

    </style>
@append

<div class="c-pager-container">
    {{--<ul class="pager c-pager">--}}
    {{--<li class="previous c-pager-btn"><a href="#">< Prev Result</a></li>--}}
    {{--<li class="next c-pager-btn"><a href="#">Next Result ></a></li>--}}
    {{--</ul>--}}

    <div class="row nav-container">
        <div style="float: left">
            <form id="navbar-form" class="form" action="{{route('results.getSingleResult')}}" method="get">
                <div class="row">
                    <input type="hidden" name="rollNo" value="{{$rollNo-1}}">
                    <input type="hidden" name="session" value="{{$session}}">
                    <input type="hidden" name="semester" value="{{$semester}}">
                    <input type="hidden" name="resultCategory" value="{{$resultCategory}}">
                    <input type="hidden" name="_token" value="{{Session::token()}}">

                    <button class="btn" type="submit">< Prev Result</button>
                </div>
            </form>
        </div>
        <div style="float: right">
            <form id="navbar-form" class="form" action="{{route('results.getSingleResult')}}" method="get">
                <div class="row">
                    <input type="hidden" name="rollNo" value="{{$rollNo+1}}">
                    <input type="hidden" name="session" value="{{$session}}">
                    <input type="hidden" name="semester" value="{{$semester}}">
                    <input type="hidden" name="resultCategory" value="{{$resultCategory}}">
                    <input type="hidden" name="_token" value="{{Session::token()}}">

                    <button class="btn" type="submit">Next Result ></button>
                </div>
            </form>
        </div>
    </div>

    <div class="row jump-container" align="center">
        <div class="row">
            <a href="{{route('results.home')}}">
                <button class="btn" style="margin-right: 25px;">Home</button>
            </a>
            <form id="navbar-form" class="form" action="{{route('results.getSingleResult')}}" method="get" style="display: inline">


                <input type="number" name="rollNo" id="jumpRollNo" value="{{$rollNo}}"
                       style="width:16%;min-width:160px;display: inline"
                       class="form-control">
                <input type="hidden" name="session" value="{{$session}}">
                <input type="hidden" name="semester" value="{{$semester}}">
                <input type="hidden" name="resultCategory" value="{{$resultCategory}}">
                <input type="hidden" name="_token" value="{{Session::token()}}">

                <button class="btn" type="submit">Bazinga!</button>

            </form>
        </div>
    </div>
</div>