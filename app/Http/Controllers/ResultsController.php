<?php

namespace App\Http\Controllers;

use App\Library\ConstantPaths;
use App\Library\FetchHelpers;
use App\Library\ResultHelpers;
use App\Result;
use Illuminate\Http\Request;

use App\Http\Requests;


class ResultsController extends Controller
{
    //
    public function postGetSingleResult(Request $request)
    {
        $session = $request['session'];
        $semester = $request['semester'];
        $resultCategory = $request['resultCategory'];
        $rollNo = $request['rollNo'];

        $resultHTML = ResultHelpers::getSingleResult($session, $semester, $resultCategory, $rollNo, true);
        
        return view('results.show-single-result', compact('resultHTML','rollNo','semester','session'));
    }

}
