<?php

include '../PHPLib/AppActionBase.php';

/*
	Returns a JSON collection of locations
	based on the user.
*/
class PullSavedLocations extends AppActionBase
{
	static public function PullLocationList($user_id)
	{
		self::_connect_to_app_db();
		$locations = self::$_db->pull_location_list($user_id);
		return json_encode($locations);
	}
}

function main()
{
	session_start();
	header('Content-type: application/json');
	echo PullSavedLocations::PullLocationList($_SESSION['user_id']);
}

main();