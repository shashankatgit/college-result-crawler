<?php

namespace App\Http\Controllers;

use App\Library\ConstantPaths;
use App\Result;
use Illuminate\Http\Request;

use App\Http\Requests;


class ResultsController extends Controller
{
    //
    public function postGetBulkResult(Request $request)
    {
        $session = $request['session'];
        $semester = $request['semester'];
        $resultCategory = $request['resultCategory'];
        $rollnoPrefix = $request['rollnoPrefix'];

        $rollnoLowerBound = (int)$request['rollnoLowerBound'];
        $rollnoUpperBound = (int)$request['rollnoUpperBound'];

        $url = \App\Library\ConstantPaths::$PUBLIC_PATH . "test/2";
        $mh = curl_multi_init();
        $requests = array();
        $fields = array();
        $active = null;

        $i = $rollnoLowerBound;

        while ($i < $rollnoUpperBound) {

            $rollno = $i<10? $rollnoPrefix.'0'.$i : $rollnoPrefix.$i;

            $result = Result::where('rollno', '=', $rollno)
                ->where('session', '=', $session)
                ->where('semester', '=', $semester)
                ->first();

            if (is_null($result)) {
                $result = new Result();
                
                
            }

            $i++;
        }
    }


    public function temp()
    {
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
    }

}
