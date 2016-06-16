<?php

include '../PHPLib/AppActionBase.php';

class CheckUser extends AppActionBase
{
	/*
	    Based on the username the user submits, we either give them the 
	    option to create a user that doesn't exist, or forward them to 
	    their user-specific weather page.
	*/
	static public function DirectBasedOnUsername($username)
	{
		session_start();
		$_SESSION['username'] = $username;
		self::_connect_to_app_db();
		$user_check = self::$_db->username_exists($username);

		if($user_check === false) {
			self::_redirect('../create_user.php');
		}
		else 
		{
			$_SESSION['user_id'] = $user_check;
			self::_redirect('../weather.php');
		}
	}
}

function main()
{
	if(empty($_POST) === false) 
	{
		session_start();
		CheckUser::DirectBasedOnUsername($_POST['username']);
	}
}

main();