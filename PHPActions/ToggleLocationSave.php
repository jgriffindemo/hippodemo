<?php

include '../PHPLib/AppActionBase.php';

class ToggleLocationSave extends AppActionBase
{
	/*
	  This creates a saving functionality written around
	  the button the user clicks to save a location.

	  If the location already exists, it is deleted. Otherwise,
	  we save the location.

	  This works with the style toggle in the UI, where saving and "unsaving"
	  are done by clicking the save button multiple times.

	  Returns a JSON response telling the caller what action it took.
	*/
	static public function ToggleSaveFromButton($user_id, $city, $region, $country)
	{
		self::_connect_to_app_db();
		$dom_location_string = self::$_db->format_location($city, $region, $country);
		$response = ['action' => 'save'];

		if(self::$_db->location_exists($dom_location_string, $user_id) === true) 
		{
			$response['action'] = 'delete';
			self::$_db->delete_location_by_string($dom_location_string, $user_id);
		}
		else{
			self::$_db->save_user_location($user_id, $city, $region, $country);
		}

		return json_encode($response);
	}
}

function main()
{
	if(empty($_POST) === false) 
	{
		session_start();
		header('Content-type: application/json');
		echo ToggleLocationSave::ToggleSaveFromButton($_SESSION['user_id'], $_POST['city'], $_POST['region'], $_POST['country']);
	}
}
main();