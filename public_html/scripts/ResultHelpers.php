<?php

require_once ('FetchHelpers.php');

class ResultHelpers
{

    private static $host = 'localhost';
    private static $username = 'root';
    private static $password = '';
    private static $dbName = 'u173463499_bres';
    private static $port=3306;

    //Returns an object of Result model by default
    public static function getSingleResult($session, $semester, $resultCategory, $rollNo, $returnHtml = false)
    {
//        $result = \DB::table('results')
//            ->where('session', '=', $session)
//            ->where('semester', '=', $semester)
//            ->where('rollno', '=', $rollNo)
//            ->get();


        $con = mysqli_connect(self::$host,self::$username,self::$password,self::$dbName, self::$port);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $semester= mysqli_real_escape_string($con, $semester);
        $rollNo= mysqli_real_escape_string($con, $rollNo);

        $query = "SELECT * from results WHERE semester='$semester' AND rollno='$rollNo'";
       // echo($query);

        $result = mysqli_query($con, $query);

//        $result = Result::where('session', '=', $session)
//            ->where('semester', '=', $semester)
//            ->where('rollno', '=', $rollNo)
//            ->first();

        $parsedData = null;

        if (mysqli_num_rows($result) > 0) {
            //result present in databases
            $row = mysqli_fetch_assoc($result);

            mysqli_close($con);

            if ($returnHtml) {
                return stripslashes($row['result_html']);
            } else {

                return [
                    'rollNo'=> $row['rollno'],
                    'name'=> $row['name'],
                    'percentage'=> $row['percentage'],
                ];
            }
        }
        else { //If result not found in db

            $html = FetchHelpers::helperFetchHTMLFromServer($session, $semester, $resultCategory, $rollNo);
            $parsedData = FetchHelpers::parseHTMLForPercentage($rollNo, $semester, $html);
            $resultArray =  null;

            //print_r($parsedData);
            if ($parsedData['isValid']) {

                $html = FetchHelpers::parseHTMLForStorage($html);

                $html = addslashes($html);

                $query = "INSERT INTO results(rollno, name, session, semester, percentage, result_html) ".
                    "VALUES ('$rollNo', '{$parsedData['name']}', '$session', '$semester', 
                    '{$parsedData['percentage']}', '{$html}')";

                //echo ($query);

                $res = mysqli_query($con,$query);
                //var_dump($res);
                //echo mysqli_error($con);
                //print_r($res);
                
                mysqli_close($con);

                $resultArray =  [
                        'rollNo'=> $rollNo,
                        'name'=> $parsedData['name'],
                        'percentage'=> $parsedData['percentage'],
                    ];
            } else{
                $html="
                 <h3 style='margin-top: 160px;'><p align='center'>Result undeclared or invalid details!</p></h3>
                ";
            }

            if ($returnHtml)
                return $html;

            else
                return $resultArray;
        }



    }

}


?>