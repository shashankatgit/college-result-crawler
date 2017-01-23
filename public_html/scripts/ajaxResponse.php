<?php

//echo 'testing';

$session = $_GET['session'];
$semester = $_GET['semester'];
$resultCategory = $_GET['resultCategory'];
$rollNo = $_GET['rollNo']; 

$result = ResultHelpers::getSingleResult($session, $semester, $resultCategory, $rollNo, false);

$errormsg = 'Result N/A , wrong session-semester pair, or invalid student! Please check.';

if ($result == null) {
    $resultArray = ['isValid' => false, 'rollno' => $rollNo, 'name' => '-', 'percentage' => $errormsg];
} else {
    $resultArray = [
        'isValid' => true,
        'rollNo' => $result->rollno,
        'name' => $result->name,
        'percentage' => $result->percentage,
        'sem' => $semester,
    ];
}

if ($request->has('id'))
    $resultArray['id'] = $request['id'];

return response()->json($resultArray);
?>