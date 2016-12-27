<?php

namespace App\Library;

require_once(public_path() . ConstantPaths::$PATH_WEB_ASSETS . 'simpletest/browser.php');

class FetchHelpers
{
    public static function helperFetchHTMLFromServer($session, $semester, $resultCategory, $rollNo) //won't be directly called ever
    {
//        sleep(1);
//        return response()->json($result);


        $browser = new \SimpleBrowser();
        $browser->get('http://bietjhs.ac.in/studentresultdisplay/frmprintreport.aspx');

        $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlAcademicSession', $session);
        $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlSem', $semester);
        $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlResultCategory', $resultCategory);
        $browser->setFieldById('ctl00_ContentPlaceHolder1_txtRollno', $rollNo);

        $browser->click('View');

        return $browser->getContent();
//$html = new simple_html_dom();

//die($browser->getContent());

        //return view('allwebviews.results.process',compact('session','semester','resultCategory','rollNo'));


    }

    public static function parseHTMLForPercentage($rollNo, $semester, $html)
    {
        $dom = new \DOMDocument();
        $invalidResult = ['isValid'=> false,'rollno' => $rollNo, 'name' => 'N/A', 'percentage' => 'N/A'];

        @$dom->loadHTML($html);

        $name = $dom->getElementById('ctl00_ContentPlaceHolder1_sName');
        if ($name == null)
            return response()->json($invalidResult);

        $name = $name->textContent;

        $marksElementId = $semester % 2 == 0
            ? 'ctl00_ContentPlaceHolder1_emk'
            : 'ctl00_ContentPlaceHolder1_omk';

        $marks = $dom->getElementById($marksElementId);
        if ($marks == null)
            return response()->json($invalidResult);
        $marks = explode('/', $marks->textContent);

        if (!is_numeric($marks[0]) || !is_numeric($marks[1]))
            return response()->json($invalidResult);

        $percentage = $marks[0] / $marks[1] * 100;

        $result = [
            'isValid' => true,
            'rollno' => $rollNo,
            'name' => $name,
            'percentage' => $percentage,
        ];



//print_r($result);
        return $result;
    }

    public static function parseHTMLForStorage($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $data1 = $dom->getElementsByTagName('table');

        return $dom->saveHTML($data1->item(0));
    }
    
}

?>