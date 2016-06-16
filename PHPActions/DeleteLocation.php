<?php

include '../PHPLib/AppActionBase.php';

class DeleteLocation extends AppActionBase
{
	static public function DeleteLocationByString($location_string, $user_id)
	{
		self::_connect_to_app_db();
		self::$_db->delete_location_by_string($location_string, $user_id);
	}
}

function main()
{
	if(empty($_POST) === false) 
	{
		session_start();
		DeleteLocation::DeleteLocationByString($_POST['location_string'], $_SESSION['user_id']);
	}
}

main();