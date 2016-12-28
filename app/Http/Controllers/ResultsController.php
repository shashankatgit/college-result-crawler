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

        return view('results.show-single-result', compact('resultHTML', 'rollNo', 'semester', 'session', 'resultCategory'));
    }

    public function postGetBulkResult(Request $request)
    {
        $session = $request['session'];
        $semester = $request['semester'];
        $resultCategory = $request['resultCategory'];

        $rollNoPrefix1 = $request['rollNoPrefix1'];
        $rollNoStart1 = $request['rollNoStart1'];
        $rollNoEnd1 = $request['rollNoEnd1'];

        $rangeCount = 1;

        //Initialise all the other possible ranges
        $rollNoPrefix2 = 0;
        $rollNoStart2 = 0;
        $rollNoEnd2 = 0;

        if ($request->has('rollNoPrefix2')) {
            $rollNoPrefix2 = $request['rollNoPrefix2'];
            $rollNoStart2 = $request['rollNoStart2'];
            $rollNoEnd2 = $request['rollNoEnd2'];

            $rangeCount = 2;
        }

        return view('results.tabulate',
            compact('session', 'semester', 'resultCategory', 'rangeCount',
                'rollNoPrefix1', 'rollNoStart1', 'rollNoEnd1',
                'rollNoPrefix2', 'rollNoStart2', 'rollNoEnd2'
            ));

    }

    public function postGetResultJSON(Request $request)
    {
        $session = $request['session'];
        $semester = $request['semester'];
        $resultCategory = $request['resultCategory'];
        $rollNo = $request['rollNo'];

        $result = ResultHelpers::getSingleResult($session, $semester, $resultCategory, $rollNo, false);

  

        $resultArray = [
            'rollNo' => $result->rollno,
            'name' => $result->name,
            'percentage' => $result->percentage
        ];
        
        if($request->has('id'))
            $resultArray['id'] = $request['id'];

        return response()->json($resultArray);
    }
}
