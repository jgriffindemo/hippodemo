<?php

/*
	This enables the "actions" to interact with the database.
	This is like a minimal "model" from MVC.
*/
class QuickWeatherDB
{
	/*
	*
	* REPLACE THESE VALUES
	*
	*/
	private $__db_server   = 'localhost';
	private $__db_user     = 'root';
	private $__db_password = 'password';
	private $__db_name     = 'apax';
	/* END */

	private $__connection = null;

	public function connect_to_quickweather_db()
	{
		try 
		{
			$connection = new PDO("mysql:host=".$this->__db_server.";dbname=".$this->__db_name, $this->__db_user, $this->__db_password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->__connection = $connection;
		}
		catch(PDOException $ex) {
			echo $ex->getMessage();
		}
	}

	/*
	  No return value.
	*/
	public function save_user_location($user_id, $city, $region, $country)
	{
		/* Insert the new raw location. */
		$query = $this->__connection->prepare('INSERT INTO locations (city, region, country) VALUES (:city, :region, :country)');
		$query->bindParam(':city', $city, PDO::PARAM_STR);
		$query->bindParam(':region', $region, PDO::PARAM_STR);
		$query->bindParam(':country', $country, PDO::PARAM_STR);
		$query->execute();
		$location_id = $this->__connection->lastInsertId();

		/* Link the user to the new location. */
		$query = $this->__connection->prepare('INSERT INTO users_locations (location_id, user_id) VALUES (:location_id, :user_id)');
		$query->bindParam(':location_id', $location_id, PDO::PARAM_INT);
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$query->execute();
	}

	/*
	  Returns the ID of the inserted user.
	*/
	public function add_user($username)
	{
		$query = $this->__connection->prepare('INSERT INTO users (`name`) VALUES (:username)');
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->execute();
		return $this->__connection->lastInsertId();
	}

	/*
	  Returns false if a username doesn't exist.
	  Returns the user id otherwise.
	*/
	public function username_exists($username)
	{
		$query = $this->__connection->prepare('SELECT id FROM users WHERE name=:queried_name');
		$query->bindParam(':queried_name', $username, PDO::PARAM_STR);
		$query->execute();

		if($query->rowCount() !== 0)
		{
			$result = $query->fetch();
			return $result['id'];
		}
		else {
			return false;
		}
	}

	/*
	  Returns false if the location doesn't exist.
	  Return true otherwise.
	*/
	  public function location_exists($location_string, $user_id)
	  {
	  	$query = $this->__connection->prepare("select * 
	  		FROM users_locations
	  		LEFT JOIN locations ON locations.id=users_locations.location_id
	  		LEFT JOIN users ON users.id=users_locations.user_id
	  		WHERE (CONCAT(locations.city, ', ', locations.region, ', ', locations.country)=:location_string
	  			OR CONCAT(locations.city, ', ', locations.country)=:location_string) AND users.id=:user_id"
	  	);
	  	$query->bindParam(':location_string', $location_string, PDO::PARAM_STR);
	  	$query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
	  	$query->execute();

	  	return ($query->rowCount() !== 0);
	  }

	/*
	  No return value.
	*/
	public function delete_location_by_string($location_string, $user_id)
	{
		$query = $this->__connection->prepare("DELETE users_locations FROM users_locations
			LEFT JOIN locations ON locations.id=users_locations.location_id
			LEFT JOIN users ON users.id=users_locations.user_id
			WHERE (CONCAT(locations.city, ', ', locations.region, ', ', locations.country)=:location_string
				OR CONCAT(locations.city, ', ', locations.country)=:location_string) AND users.id=:user_id"
		);
		$query->bindParam(':location_string', $location_string, PDO::PARAM_STR);
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$query->execute();
	}

	/*
	  Returns the user's saved locations as an array of strings.
	*/
	public function pull_location_list($user_id)
	{
		$query = $this->__connection->prepare('SELECT locations.city, locations.region, locations.country FROM locations 
			LEFT JOIN users_locations ON locations.id=users_locations.location_id
			LEFT JOIN users ON users.id=users_locations.user_id WHERE users.id=:user_id');
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$query->execute();

		$results = [];
		while($row = $query->fetch()) 
		{
			$location = $this->format_location($row['city'], $row['region'], $row['country']);
			array_push($results, $location);
		}
		return $results;
	}

	/* Used to format locations as they appear in the DOM. */
	public function format_location($city, $region, $country)
	{
		$pieces = [];
		foreach([$city, $region, $country] as $data) 
		{
			if(strlen($data) > 0) {
				array_push($pieces, $data);
			}
		}

		return (join(', ', $pieces));
	}
}