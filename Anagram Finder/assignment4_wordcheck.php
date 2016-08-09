<?php
	#Checks if the user's guess is a valid dictionary word
	$word = $_POST["word"];
	$db = new mysqli('localhost', "assignment4", "assignment4", "assignment4_db");
	$query = "SELECT word FROM Words WHERE word = '$word'";
	$result = $db->query($query);
	$row = $result->fetch_array();
	#If no, echo x, if yes, echo the word from the dictionary
	if (is_null($row[0])):
		echo "x";
	else:
		echo "$row[0]";
	endif;


?>