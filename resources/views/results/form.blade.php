@extends('results.layouts.master')

@section('styles')
    <style>

        div {
            /*border: 1px solid red;*/
        }

        #main-container {
            margin-top: 20px;

        }

        .form-container {
            float: left;
            min-width: 400px;
            width: 48%;
            margin-right: 10px;
            margin-bottom: 20px;
        }


    </style>


@endsection
@section('content')

    <div id="main-container" class="container">

        <div class="form-container" id="formContainer1">
            <div class="panel panel-primary">
                <div class="panel-heading" style="background: #4f0000">Navigator Mode (Single Mode)</div>
                <div class="panel-body">
                    <h5 style="color:darkred">
                        Added previous and next controls to the original results. You can see the
                        results in cycle without having to enter the details again
                    </h5>
                    <br>
                    <form class="form" action="" method="post">

                        <div class="form-group row">
                            <label for="rollno" class="col-sm-3 col-form-label">Roll No</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="rollno" id="rollno"
                                       placeholder="Roll No"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="semester" class="col-sm-3 col-form-label">Semester</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="semester" id="semester"
                                       placeholder="Semester"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="session" class="col-sm-3 col-form-label">Acd Session </label>
                            <div class="col-sm-9">
                                <select class="form-control" name="session">
                                    <option value="2012-2013">2012-2013</option>
                                    <option value="2013-2014">2013-2014</option>
                                    <option value="2014-2015">2014-2015</option>
                                    <option value="2015-2016" selected>2015-2016</option>
                                    <option value="2016-2017">2016-2017</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="resultCategory" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="resultCategory" name="resultCategory">
                                    <option value="R" selected>Regular</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <button class="btn btn-danger col-sm-2" style="float: right; margin-right:20px"
                                    type="submit">Fire it
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="form-container" id="formContainer2">
            <div class="panel panel-primary">
                <div class="panel-heading" style="background: #00154f">Bulk Mode or Class View (Tabular Mode)</div>
                <div class="panel-body">
                    <h5 style="color:darkblue">
                        Tabular Results for the given range of results
                    </h5>
                    <br>
                    <form class="form" action="" method="post">

                        <div class="form-group row">
                            <label for="session" class="col-sm-3 col-form-label">Acd Session </label>
                            <div class="col-sm-9">
                                <select class="form-control" name="session">
                                    <option value="2012-2013">2012-2013</option>
                                    <option value="2013-2014">2013-2014</option>
                                    <option value="2014-2015">2014-2015</option>
                                    <option value="2015-2016" selected>2015-2016</option>
                                    <option value="2016-2017">2016-2017</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="semester" class="col-sm-3 col-form-label">Semester</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="semester" id="semester"
                                       placeholder="Semester"
                                       required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="resultCategory" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="resultCategory" name="resultCategory">
                                    <option value="R" selected>Regular</option>
                                </select>
                            </div>
                        </div>

                        <br>
                        <div class="row" style="margin-left:5px;margin-right: 5px">
                            <p align="left" style="color:#222">
                                <b><u>Help for range concept: The range concept is for showing tabular data.</u></b>
                                <br>
                                <u>1st field (prefix)</U> : This is the common part in all roll no you wish to see
                                <br>
                                <u>2nd field (start rollno)</U> : This part will be concatenated with the prefix to obtain
                                the full roll no
                                <br>
                                <u>3rd field (end rollno)</U> : This will mark the end roll no of this particular range
                                <br><br>
                                <b style="color:darkred">For example : prefix=14043100 start=1 end=47 will tabulate all results from roll no
                                1404310001 to 1404310047</b>
                            </p>
                        </div>


                        <div class="row">
                            <label for="rollnoRange1" class="col-sm-5 col-form-label">Range 1</label>
                        </div>
                        <div class="form-group row">

                            <div class="col-sm-1"></div>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="rollnoPrefix1" id="rollnoPrefix1"
                                       placeholder="roll no prefix" required>

                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="rollnoStart1" id="rollnoStart1"
                                       placeholder="start rollno" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="rollnoEnd1" id="rollnoEnd1"
                                       placeholder="end rollno" required>
                            </div>
                        </div>



                        <div class="row">
                            <label for="rollnoRange1" class="col-sm-5 col-form-label">Range 2 (optional)</label>
                        </div>
                        <div class="form-group row">

                            <div class="col-sm-1"></div>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" name="rollnoPrefix2" id="rollnoPrefix2"
                                       placeholder="roll no prefix" >

                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="rollnoStart2" id="rollnoStart2"
                                       placeholder="start rollno" >
                            </div>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="rollnoEnd2" id="rollnoEnd2"
                                       placeholder="end rollno" >
                            </div>
                        </div>


                        <div class="form-group row">
                            <button class="btn btn-danger col-sm-2" style="float: right; margin-right:20px"
                                    type="submit">Fire it
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection