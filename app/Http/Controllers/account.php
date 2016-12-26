<?php

/**
* 
*/
namespace App\Http\Controllers;

class Account_Controller extends Controller
{
	public function action_index()
	{
		echo "this is profile page";
	}
	
	public function action_login()
	{
		echo "login form";
	}

	public function action_logout()
	{
		echo "logout";
	}
}