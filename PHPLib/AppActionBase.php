<?php

/* Include the database library so our actions can interact with the app's DB */
include 'QuickWeatherDB.php';

/*
	This defines the basis for all the app's backend actions.
	This is roughly like a controller template for an MVC framework,
	only ours is highly specific to the QuickWeather app.
*/
class AppActionBase
{
	protected static $_db = null;

	static protected function _connect_to_app_db() {
		self::$_db = new QuickWeatherDB();
		self::$_db->connect_to_quickweather_db();
	}

	static protected function _redirect($url) 
	{
		header("Location: {$url}");
		die();
	}
}