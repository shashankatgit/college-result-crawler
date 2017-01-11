@extends('results.layouts.master')

@section('title')

@endsection

@section('scripts')

    <script>
        var count = 0;
        var totalCount = 0;

        var topperRollNo;
        var topperName;
        var topperPercentage = 0;

        window.onload = function () {
            process();
        }

        function process() {
            //alert('asdasd');
            var rangeCount = '{{$rangeCount}}';
            var session = '{{$session}}';
            var semester = '{{$semester}}';
            var resultCategory = '{{$resultCategory}}';

            var prefix = [];
            var start = [];
            var end = [];

            prefix[0] = "" + {{$rollNoPrefix1}};
            start[0] =  {{$rollNoStart1}};
            end[0] =  {{$rollNoEnd1}};

            prefix[1] = "" + {{$rollNoPrefix2}};
            start[1] =  {{$rollNoStart2}};
            end[1] =  {{$rollNoEnd2}};

            totalCount = end[0] - start[0] + 1;
            if (rangeCount > 1)
                totalCount += end[1] - start[1] + 1;

            var suffix;
            var id = 1;
            var rollNo;
            for (var rangeCounter = 0; rangeCounter < rangeCount; rangeCounter++) {
                for (var suffixCounter = start[rangeCounter]; suffixCounter <= end[rangeCounter]; suffixCounter++ , id++) {
                    if (suffixCounter < 10) {
                        suffix = "" + "0" + suffixCounter;
                    }
                    else suffix = "" + suffixCounter;

//                alert("" + prefix[rangeCounter] + suffix );
                    rollNo = "" + prefix[rangeCounter] + suffix;
                    addNewEntryToTable(id, rollNo);

                    setTimeout(loadResultRow, 2000*(rangeCounter-1) + suffixCounter*250, id, rollNo, session, semester, resultCategory);
                    //loadResultRow(id, rollNo, session, semester, resultCategory);


                }
            }

        }

        function loadResultRow(id, rollNo, session, semester, resultCategory) {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                    var row = document.getElementById("entry" + id);
                    count++;

                    row.cells[4].innerHTML = '<a href="{{route('results.getSingleResult')}}'
                            + '?rollNo=' + rollNo + '&semester=' + semester
                            + '&session=' + session + '&resultCategory=' + resultCategory + '" target=_blank>See Full Result</a>';

                    if (xmlhttp.status == 200) {
                        var resultjson = JSON.parse(xmlhttp.responseText);
                        row.cells[2].innerHTML = resultjson.name;
                        row.cells[3].innerHTML = resultjson.percentage;


                        {{--if (resultjson.isValid)--}}
                            {{--row.cells[5].innerHTML = '<img src=' + '"' + '{{route('results.getPhoto')}}?rollNo=' + rollNo--}}
                                    {{--+ '" height=90>';--}}

                        if (resultjson.percentage > topperPercentage && resultjson.isValid) {
                            topperRollNo = rollNo;
                            topperName = resultjson.name;
                            topperPercentage = resultjson.percentage;
                        }

                        if (count >= totalCount && topperPercentage>0) {
                            document.getElementById("topper-img").setAttribute("src", "{{route('results.getPhoto')}}?rollNo=" + topperRollNo);
                            document.getElementById("topper-name").innerHTML = topperName;
                            document.getElementById("topper-rollno").innerHTML = topperRollNo;
                            document.getElementById("topper-percentage").innerHTML = topperPercentage;
                        }

                    }
                    else if (xmlhttp.status == 400) {
                        row.cells[2].innerHTML = 'error (server returned 400)';
                        row.cells[3].innerHTML = 'error';
                    }
                    else {
                        row.cells[2].innerHTML = 'error';
                        row.cells[3].innerHTML = 'error (unknown server response:'+ xmlhttp.status + ')';
                    }
                }
            };

            xmlhttp.open("GET", "{{route('results.ajaxResponse')}}" + "?session=" + session
                    + "&semester=" + semester + "&resultCategory=" + resultCategory
                    + "&rollNo=" + rollNo, true);
            xmlhttp.send();
            //sleep(200);
        }
        function addNewEntryToTable(id, rollNo) {
            var table = document.getElementById('resultTable');
            var row = table.insertRow();
            var cellID = row.insertCell(0);
            var cellRollNo = row.insertCell(1);
            var cellName = row.insertCell(2);
            var cellPercentage = row.insertCell(3);

            row.insertCell(4);
//            row.insertCell(5);


            row.setAttribute('id', "entry" + id);
            cellID.innerHTML = id;
            cellRollNo.innerHTML = rollNo;
            cellName.innerHTML = '  loading...  ';
            cellPercentage.innerHTML = '  loading...  ';
        }

        function sleep(milliseconds) {
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds){
                    break;
                }
            }
        }
    </script>
@endsection


@section('styles')
    <style>
        .main-container {
            margin-top: 10px;
        }

        .table-container {
            margin-top: 30px;
            margin-right: 15px;
            margin-left: 5px;
            border: 3px dotted darkblue;
            padding: 5px;
        }

        .mid-floater {
            margin-left: 50px;
            width: 280px;
            padding: 5px;
            border: 2px solid darkred;
        }

        .highest-container {
            border: 2px solid darkblue;
            width: 400px;
            margin: auto;
            margin-top: 5px;
        }

        .highest-subcontainer {
            height: 190px;
        }

        .topper-photo {
            display: inline-block;
            float: left;
            padding: 5px;
            width: 30%;
        }

        .topper-photo img {
            width: 100%;
        }

        .topper-details {
            display: inline-block;
            float: left;
            width: 70%;
            padding: 5px;
            padding-left: 13px;
        }

    </style>
@append

@section('content')
    <div class="main-container">
        <div class="details-container">
            <div class="mid-floater">
                <h5><u><b>Showing tabular results for : </b></u></h5>
                <div>
                    <h5>Academic Session : {{$session}}</h5>
                    <h5>Semester : {{$semester}}</h5>
                    <h5>Roll No Ranges : {{$rollNoStart1}} - {{$rollNoEnd1}}
                        @if($rangeCount>1)
                            and {{$rollNoStart2}} - {{$rollNoEnd2}}
                        @endif
                    </h5>
                </div>
            </div>
        </div>
        <div class="highest-container">
            <div>
                <h4 align="center"><u>Highest in this range</U></h4>
                <div class="highest-subcontainer">
                    <div class="topper-photo">
                        <img id="topper-img"
                             src="https://img.clipartfest.com/ea4261facad7415f33b6289b6d758579_business-person-clipart-1-business-person-clipart_228-298.png">
                    </div>
                    <div class="topper-details">
                        Name : <span id="topper-name">calculating...</span><br>
                        RollNo : <span id="topper-rollno">calculating...</span><br>
                        Percentage : <span id="topper-percentage">calculating...</span><br>
                        <span style="color: darkred;">
                            * Only in the given range<br>
                            * Shows the first encountered highest (in case of same percentages)<br>
                            * Will load only after all results are successfully loaded
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-container">
            <table id="resultTable" class="table">
                <thead>
                <tr>
                    <th class="col-sm-1">No</th>
                    <th class="col-sm-2">RollNo</th>
                    <th class="col-sm-3">Name</th>
                    <th class="col-sm-3">Percentage</th>
                    <th class="col-sm-1">Full Result</th>
                    {{--<th class="col-sm-2">Photo</th>--}}
                </tr>
                </thead>

            </table>
        </div>
    </div>
@endsection


