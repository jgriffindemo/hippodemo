<?php

include '../PHPLib/AppActionBase.php';

class AddUser extends AppActionBase
{
	static public function DirectBasedOnResponse($response, $username)
	{
		if($response === 'yes') 
		{
			self::_connect_to_app_db();			
			$_SESSION['user_id'] = self::$_db->add_user($username);
			self::_redirect('../weather.php');
		}
		else {
			self::_redirect('../index.php');
		}
	}
}

function main() 
{
	if((isset($_POST['no'])) || (isset($_POST['yes']))) 
	{
		session_start();
		AddUser::DirectBasedOnResponse(array_keys($_POST)[0], $_SESSION['username']);
	}
}

main();