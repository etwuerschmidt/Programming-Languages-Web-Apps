<?php
	session_start();
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	if (is_array($_SESSION["times"])):
		foreach ($_SESSION["times"] as $key):
			$new_key = rtrim($key, "/");
			$new_key = str_replace("+", " ", $new_key);
			$sub_value = str_replace("+", " ", $_SESSION["Submit"]);
			#Insert times chosen by the user into the times_selected table
			$db->query("INSERT INTO times_selected VALUES ('$new_key', '$_SESSION[Submit]')");
		endforeach;
	else:
		echo "Not an array.";
	endif;

	echo "Times successfully submitted!";

	
	
?>