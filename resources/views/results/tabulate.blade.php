@extends('results.layouts.master')

@section('title')

@endsection

@section('scripts')

    <script>
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
                    loadResultRow(id, rollNo, session, semester, resultCategory);
                }
            }

        }

        function loadResultRow(id, rollNo, session, semester, resultCategory) {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                    var row = document.getElementById("entry" + id);
                    if (xmlhttp.status == 200) {
                        var resultjson = JSON.parse(xmlhttp.responseText);
                        row.cells[2].innerHTML = resultjson.name;
                        row.cells[3].innerHTML = resultjson.percentage;

                        row.cells[4].innerHTML = '<a href="{{route('results.getSingleResult')}}'
                            + '?rollNo=' +rollNo + '&semester=' + semester
                            + '&session=' + session + '&resultCategory=' + resultCategory + '" target=_blank>See Full Result</a>';

                        if(resultjson.isValid)
                            row.cells[5].innerHTML = '<img src=' + '"http://ims.bietjhs.in/student/StudentPhoto.ashx?RollNo=' + rollNo
                                    + '&type=Pic" height=90>';

                    }
                    else if (xmlhttp.status == 400) {
                        row.cells[2].innerHTML = 'error';
                        row.cells[3].innerHTML = 'error';
                    }
                    else {
                        row.cells[2].innerHTML = 'error';
                        row.cells[3].innerHTML = 'error';
                    }
                }
            };

            xmlhttp.open("GET", "{{route('results.ajaxResponse')}}" + "?session=" + session
                    + "&semester=" + semester + "&resultCategory=" + resultCategory
                    + "&rollNo=" + rollNo, true);
            xmlhttp.send();
        }
        function addNewEntryToTable(id, rollNo) {
            var table = document.getElementById('resultTable');
            var row = table.insertRow();
            var cellID = row.insertCell(0);
            var cellRollNo = row.insertCell(1);
            var cellName = row.insertCell(2);
            var cellPercentage = row.insertCell(3);

            row.insertCell(4);
            row.insertCell(5);


            row.setAttribute('id', "entry" + id);
            cellID.innerHTML = id;
            cellRollNo.innerHTML = rollNo;
            cellName.innerHTML = '  loading...  ';
            cellPercentage.innerHTML = '  loading...  ';
        }
    </script>
@endsection


@section('styles')
    <style>
        .table-container {
            margin-top: 30px;
            margin-right: 15px;
            margin-left: 15px;
            border: 3px dotted darkblue;
            padding:5px;
        }

    </style>
@append

@section('content')
    <div class="main-container">
        <div class="header-container">
            <div>
                <h3>Highest in the range</h3>
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
                    <th class="col-sm-2">Photo</th>
                </tr>
                </thead>

            </table>
        </div>
    </div>
@endsection


