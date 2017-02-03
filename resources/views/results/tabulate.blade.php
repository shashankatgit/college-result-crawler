@extends('results.layouts.master')

@section('title')

@endsection

@section('scripts')

    <script>

                <?php $ajaxPath = "http://localhost/laravel-projects/bietresults/public_html/scripts/ajaxResponse.php";?>

        var count = 0;
        var totalCount = 0;

                var resultArray = [];
                var sortedResultArray;

        var topperRollNo;
        var topperName;
        var topperPercentage = 0;

        var rangeCount = '{{$rangeCount}}';
        var session = '{{$session}}';
        var semester = '{{$semester}}';
        var resultCategory = '{{$resultCategory}}';


        window.onload = function () {
            process();
        }

        function process() {
            //alert('asdasd');


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

            document.getElementById('totalCount').innerHTML=''+totalCount;

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

                    setTimeout(loadResultRow, 800*(rangeCounter-1) + suffixCounter*20, id, rollNo, session, semester, resultCategory);

                    //loadResultRow(id, rollNo, session, semester, resultCategory);


                }
            }

        }

        function loadResultRow(id, rollNo, session, semester, resultCategory) {
            var xmlhttp = new XMLHttpRequest();


            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                    var row = document.getElementById("entry" + id);



                    if (xmlhttp.status == 200) {
                        count++;
                        document.getElementById('loadCount').innerHTML=count;


                        var resultjson = JSON.parse(xmlhttp.responseText);

                        resultArray[id-1]=[];
                        resultArray[id-1]['rollno']=rollNo;
                        resultArray[id-1]['name'] = resultjson.name;
                        resultArray[id-1]['percentage'] = resultjson.percentage;


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
                            //Enable the sort button
                            document.getElementById('btnsort').removeAttribute('disabled');

                            //Set topper details
                            document.getElementById("topper-img").setAttribute("src", "{{route('results.getPhoto')}}?rollNo=" + topperRollNo);
                            document.getElementById("topper-name").innerHTML = '<b>' + topperName + '</b>';
                            document.getElementById("topper-rollno").innerHTML = '<b>' + topperRollNo + '</b>';
                            document.getElementById("topper-percentage").innerHTML = '<b>' + topperPercentage + '</b>';
                        }

                    }
                    else if (xmlhttp.status == 400) {
                        row.cells[2].innerHTML = 'error (server returned 400)';
                        row.cells[3].innerHTML = 'error';
                        count++;
                    }
                    else {
                        row.cells[2].innerHTML = 'server failed. Retrying...';
                        row.cells[3].innerHTML = 'Retrying...';
                        loadResultRow(id, rollNo, session, semester, resultCategory);
                    }
                }
            };

            xmlhttp.open("GET", <?php echo "'$ajaxPath'";?> + "?session=" + session
                    + "&semester=" + semester + "&resultCategory=" + resultCategory
                    + "&rollNo=" + rollNo, true);
            xmlhttp.send();
        }
        function addNewEntryToTable(id, rollNo, name, percentage) {

            if(name == undefined) name='  Loading...';
            if(percentage == undefined) percentage='  Loading...';

            var table = document.getElementById('resultTable');
            var row = table.insertRow();
            var cellID = row.insertCell(0);
            var cellRollNo = row.insertCell(1);
            var cellName = row.insertCell(2);
            var cellPercentage = row.insertCell(3);

            var seeFullLink = row.insertCell(4);
//            row.insertCell(5);


            row.setAttribute('id', "entry" + id);
            cellID.innerHTML = id;
            cellRollNo.innerHTML = rollNo;
            cellName.innerHTML = name;
            cellPercentage.innerHTML = percentage;

            seeFullLink.innerHTML = '<a href="{{route('results.getSingleResult')}}'
                    + '?rollNo=' + rollNo + '&semester=' + semester
                    + '&session=' + session + '&resultCategory=' + resultCategory + '" target=_blank>See Full Result</a>';
        }


        //Sorting features start

        function initSort() {
            var button = document.getElementById('btnsort');
            button.innerHTML = "Click to unsort the table to default view";
            button.setAttribute('onclick', 'initUnsort()');

            var table = document.getElementById('resultTable');
            while(table.rows[1]) table.deleteRow(1);

            if(sortedResultArray == undefined) {

                sortedResultArray =[];

                for(var j=0; j<resultArray.length; j++) {
                    sortedResultArray[j]=[];
                    sortedResultArray[j]['rollno'] = resultArray[j]['rollno'];
                    sortedResultArray[j]['name'] = resultArray[j]['name'];
                    sortedResultArray[j]['percentage'] = resultArray[j]['percentage'];
                }
                alert(sortedResultArray);
                sortedResultArray.sort(function (res1, res2) {
                    perc1 = res1['percentage'];
                    perc2 = res2['percentage'];

                    if (isNumeric(perc1) && isNumeric(perc2)) {
                        if (perc1 >= perc2) return -1;
                        else return 1;
                    }

                    else if (isNumeric(perc1)) return -1;
                    else return 1;
                });
            }

            for(var i=0; i<sortedResultArray.length; i++)
            {
                addNewEntryToTable(i+1, sortedResultArray[i]['rollno'], sortedResultArray[i]['name'], sortedResultArray[i]['percentage']);
            }

        }

        function initUnsort() {
            var button = document.getElementById('btnsort');
            button.innerHTML = "Sort Again!";
            button.setAttribute('onclick', 'initSort()');
            var table = document.getElementById('resultTable');
            while(table.rows[1]) table.deleteRow(1);

            for(var i=0; i<resultArray.length; i++)
            {
                addNewEntryToTable(i+1, resultArray[i]['rollno'], resultArray[i]['name'], resultArray[i]['percentage']);
            }
        }


        function isNumeric(input){
            var RE = /^-{0,1}\d*\.{0,1}\d+$/;
            return (RE.test(input));
        }

    </script>
@endsection


@section('styles')
    <style>
        body{
            background-color: #ececec;
            min-width: 800px;
        }
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
            width: 450px;
            padding: 5px;
        //border: 2px solid darkred;
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
                <a href="{{route('results.home')}}"><button class="btn btn-primary">Home</button></a>

                <h5><u><b>Showing tabular results for : </b></u></h5>
                <div>
                    <h6>Academic Session : {{$session}} &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Semester : {{$semester}}</h6>
                    <h6>Roll No Ranges : {{$rollNoStart1}} - {{$rollNoEnd1}}
                        @if($rangeCount>1)
                            and {{$rollNoStart2}} - {{$rollNoEnd2}}
                        @endif
                    </h6>
                </div>
                <h5 style="color:darkblue">Patience is bitter but its fruit is sweet. Be patient while I dig for you.</h5>
                <h5 style="color: red">** Not for any official use. A hobby project by Shashank Singh</h5>
            </div>
        </div>
        <div class="highest-container">
            <div>
                <h4 align="center" style="color:darkred">Loaded <span id="loadCount">0</span> out of <span id="totalCount"></span> results </h4>

                <h5 align="center"><u>Highest in this range</U></h5>
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
        <div style="margin:auto; margin-top:5px;width: 300px;">
            <button style="width:300px;" class="btn btn-primary" id="btnsort" onclick="initSort()" disabled>Click to sort table by percentage</button>
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
                <tbody id="resultTableBody">

                </tbody>

            </table>
        </div>
    </div>
@endsection

