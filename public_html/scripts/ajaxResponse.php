<?php

//echo 'testing';
require_once ('ResultHelpers.php');


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
        'rollNo' => $result['rollNo'],
        'name' => $result['name'],
        'percentage' => $result['percentage'],
        'sem' => $semester,
    ];
}

if (isset($_GET['id']))
    $resultArray['id'] = $_GET['id'];

http_response_code(200);
header('Content-Type: application/json');
echo json_encode($resultArray);

?>