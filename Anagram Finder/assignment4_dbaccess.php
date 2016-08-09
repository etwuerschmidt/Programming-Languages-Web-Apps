<?php
	#Selects a random word from the dictionary (to have anagrams created from it)
	$db = new mysqli('localhost', "assignment4", "assignment4", "assignment4_db");
	$query = "SELECT word FROM Words ORDER BY Rand() LIMIT 1";
	$result = $db->query($query);
	$row = $result->fetch_array();
	echo "$row[0]";

?>