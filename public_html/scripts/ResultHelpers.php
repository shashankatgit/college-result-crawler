<?php

namespace App\Library;

use App\Result;

class ResultHelpers
{

    //Returns an object of Result model by default
    public static function getSingleResult($session, $semester, $resultCategory, $rollNo, $returnHtml = false)
    {
//        $result = \DB::table('results')
//            ->where('session', '=', $session)
//            ->where('semester', '=', $semester)
//            ->where('rollno', '=', $rollNo)
//            ->get();

        $result = Result::where('session', '=', $session)
            ->where('semester', '=', $semester)
            ->where('rollno', '=', $rollNo)
            ->first();

        $parsedData = null;

        //Check if Result is absent in database
        if ($result == null) {


            $html = FetchHelpers::helperFetchHTMLFromServer($session, $semester, $resultCategory, $rollNo);
            $parsedData = FetchHelpers::parseHTMLForPercentage($rollNo, $semester, $html);

            if ($parsedData['isValid']) {
                $result = new Result();
                $html = FetchHelpers::parseHTMLForStorage($html);

                $result->rollno = $rollNo;
                $result->session = $session;
                $result->semester = $semester;
                $result->name = $parsedData['name'];
                $result->percentage = $parsedData['percentage'];
                $result->result_html = $html;
                $result->save();
            }

            else{
                $html="
                 <h3 style='margin-top: 160px;'><p align='center'>Result undeclared or invalid details!</p></h3>
                ";
            }

            if ($returnHtml)
                return $html;

            else
                return $result;
        }

        //If result is found in database
        else {
            if ($returnHtml) {
                return $result->result_html;
            }
            else
                return $result;

        }
    }

}


?>