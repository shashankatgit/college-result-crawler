@extends('results.layouts.master')

@section('title')

@endsection

@section('scripts')

    <script>
        window.onload = function() {
            process();
        }

        function process() {
            alert('asdasd');
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
            for(var suffixCounter=start[rangeCounter]; suffixCounter<=end[rangeCounter]; suffixCounter++ , id++)
            {
                if(suffixCounter<10)
                {
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

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
                    if (xmlhttp.status == 200) {
                        resultjson = JSON.parse(xmlhttp.responseText);
                        var row = document.getElementById("entry"+id);
                        row.cells[2].innerHTML = resultjson.name;
                        row.cells[3].innerHTML = resultjson.percentage;
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
                            + "&semester=" + semester + "&resultCategory="+resultCategory
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

            row.setAttribute('id',"entry"+id);
            cellID.innerHTML=id;
            cellRollNo.innerHTML=rollNo;
            cellName.innerHTML = '  loading...  ';
            cellPercentage.innerHTML = '  loading...  ';
        }
    </script>
@endsection


@section('styles')

@endsection

@section('content')
    <div onload="process()" class="table-container">
        <table id="resultTable" class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>RollNo</th>
                <th>Name</th>
                <th>Percentage</th>
            </tr>
            </thead>

        </table>
    </div>
@endsection


