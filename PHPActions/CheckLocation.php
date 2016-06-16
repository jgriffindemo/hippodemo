<?php

include '../PHPLib/AppActionBase.php';

class CheckLocation extends AppActionBase
{
	/*
	  Used to determine if a location exists.

	  Returns a JSON response.
	*/
	static public function Exists($user_id, $city, $region, $country)
	{
		self::_connect_to_app_db();
		$dom_location_string = self::$_db->format_location($city, $region, $country);
		$response = ['response' => 'false'];
		$response['response'] = self::$_db->location_exists($dom_location_string, $user_id);
		return json_encode($response);
	}
}

function main()
{
	if(empty($_POST) === false) 
	{
		session_start();
		header('Content-type: application/json');
		echo CheckLocation::Exists($_SESSION['user_id'], $_POST['city'], $_POST['region'], $_POST['country']);
	}
}
main();