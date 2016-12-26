<?php

namespace App\Http\Controllers;

use App\Library\ConstantPaths;
use App\Library\FetchHelpers;
use Illuminate\Http\Request;

use App\Http\Requests;

require_once(public_path() . ConstantPaths::$PATH_WEB_ASSETS . 'simpletest/browser.php');

class FetchResultController extends Controller
{
    //

    public function postGetSingleResultDirectlyFromServer(Request $request)
    {
        $session = $request['session'];
        $semester = $request['semester'];
        $resultCategory = $request['resultCategory'];
        $rollNo = $request['rollno'];
        $marksElementId = $request['marksElementId'];

        $resultHTML = FetchHelpers::helperFetchHTMLFromServer($session, $semester, $resultCategory, $rollNo);
        $parsedJSON = FetchHelpers::parseHTML($rollNo, $semester, $resultHTML);
        return $parsedJSON;
    }

    public function helperFetchResultFromServer($session, $semester, $resultCategory, $rollNo) //won't be directly called ever
    {
//        sleep(1);
//        return response()->json($result);

        $invalidResult = ['rollno' => $rollNo, 'name'=>'N/A', 'percentage'=>'N/A'];

        $browser = new SimpleBrowser();
        $browser->get('http://bietjhs.ac.in/studentresultdisplay/frmprintreport.aspx');

        $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlAcademicSession', $session);
        $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlSem', $semester);
        $browser->setFieldById('ctl00_ContentPlaceHolder1_ddlResultCategory', $resultCategory);
        $browser->setFieldById('ctl00_ContentPlaceHolder1_txtRollno', $rollNo);

        $browser->click('View');
//$html = new simple_html_dom();

//die($browser->getContent());
        $dom = new DOMDocument();

        @$dom->loadHTML($browser->getContent());

        $name = $dom->getElementById('ctl00_ContentPlaceHolder1_sName');
        if($name==null)
            return response()->json($invalidResult);

        $name=$name->textContent;

        $marks = $dom->getElementById($marksElementId);
        if($marks==null)
            return response()->json($invalidResult);
        $marks = explode('/', $marks->textContent);

        if (!is_numeric($marks[0]) || !is_numeric($marks[1]))
            return response()->json($invalidResult);

        $percentage = $marks[0] / $marks[1] * 100;

        $result = [
            'rollno' => $rollNo,
            'name' => $name,
            'percentage' => $percentage,
        ];

//print_r($result);
        return $result;
        //return view('allwebviews.results.process',compact('session','semester','resultCategory','rollNo'));


    }
}
