
<?php
//require_once(public_path() . \App\Library\ConstantPaths::$PATH_WEB_ASSETS . 'simpletest/browser.php');
//require_once(public_path() . \App\Library\ConstantPaths::$PATH_WEB_ASSETS . 'simplehtmldom/simple_html_dom.php');
//
//$browser = new SimpleBrowser();
//$results = array();

$session = '2015-2016';
$semester = '4';
$resultCategory = 'R';
$rollNoPrefix = '14043100';
$rollNoSuffix = 1;

$count = 60;

$url = \App\Library\ConstantPaths::$PUBLIC_PATH . "test/2";

$mh = curl_multi_init();
$requests = array();
$fields = array();
$active = null;

//$headers = array(
//        'Content-Type: application/json'
//);

for ($j = 0; $j < $count; $rollNoSuffix++, $j++) {
    $rollNo = $rollNoSuffix < 10 ? $rollNoPrefix . "0" . $rollNoSuffix : $rollNoPrefix . $rollNoSuffix;
    $requests[$j] = curl_init($url);

    $fields = [
            'session' => $session,
            'semester' => $semester,
            'resultCategory' => 'R',
            'marksElementId' => $semester % 2 == 0
                    ? 'ctl00_ContentPlaceHolder1_emk'
                    : 'ctl00_ContentPlaceHolder1_omk',
            'rollno' => $rollNo,
    ];


    curl_setopt($requests[$j], CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($requests[$j], CURLOPT_HEADER, 0);
    curl_setopt($requests[$j], CURLOPT_POST, true);
//    curl_setopt($requests[$j], CURLOPT_HTTPHEADER, $headers);
    curl_setopt($requests[$j], CURLOPT_RETURNTRANSFER, true);

    curl_multi_add_handle($mh, $requests[$j]);

}

do {
    $a = curl_multi_exec($mh, $active);
} while ($active > 0);

$returnedData = array();

foreach ($requests as $key => $request) {

    $returnedData[$key] = json_decode(curl_multi_getcontent($request), true);

    curl_close($request);
}

curl_multi_close($mh);

?>

<html>

<body>
<table>
    <thead>
    <th>Roll no</th>
    <th>Name</th>
    <th>Percentage</th>
    </thead>
    @foreach($returnedData as $result)

        <tr>
            <td>{{$result['rollno']}}</td>
            <td>{{$result['name']}}</td>
            <td>{{$result['percentage']}}</td>
        </tr>

    @endforeach
</table>
</body>
</html>
