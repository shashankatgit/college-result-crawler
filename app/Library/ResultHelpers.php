<?php

namespace App\Library;

use App\Result;

class ResultHelpers
{

    public static function getMassResult($session, $semester, $resultCategory, $rollNoParamsArray)
    {

    }


    //Returns an object of Result model by default
    public static function getSingleResult($session, $semester, $resultCategory, $rollNo, $returnHtml=false)
    {
        $result = \DB::table('results')
            ->where('session', '=', $session)
            ->where('semester', '=', $semester)
            ->where('rollno', '=', $rollNo)
            ->get();

        $parsedData=null;

        //Check if Result is absent in database
        if ($result == null) {
            $html = FetchHelpers::helperFetchHTMLFromServer($session, $semester, $resultCategory, $rollNo);
            $parsedData = FetchHelpers::parseHTML($rollNo, $semester, $html);

            if ($parsedData['isValid']) {
                $result = new Result();
                $result->rollno = $rollNo;
                $result->session = $session;
                $result->semester = $semester;
                $result->name = $parsedData['name'];
                $result->percentage = $parsedData['percentage'];
                $result->html = $html;
                $result->save();
            }

            if($returnHtml)
                return $html;

            else
                return $result;
        }

        //If result is found in database
        else {
            return $result->html;
        }
    }

}


?>