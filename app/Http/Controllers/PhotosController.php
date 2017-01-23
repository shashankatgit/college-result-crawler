<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PhotosController extends Controller
{
    //
    public function getPhoto(Request $request)
    {
        $rollNo = $request['rollNo'];

        header('Content-type: image/jpeg');

        $photo = file_get_contents("http://ims.bietjhs.ac.in/student/StudentPhoto.ashx?RollNo=" . $rollNo. "&type=Pic");

        echo $photo;
    }
}
