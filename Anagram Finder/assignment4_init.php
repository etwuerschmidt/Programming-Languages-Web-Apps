<?php
	ini_set("max_execution_time", 0);
	$db = new mysqli('localhost', "assignment4", "assignment4", "assignment4_db");
	$fileptr = fopen("dictionary.txt", "r");
	

	#Drops tables and creates them in the database
	$db->query("drop table words");
	
	$sql = "CREATE TABLE words (
		Word VARCHAR(50),
		ID int primary key AUTO_INCREMENT)";

	$db->query($sql);

	#Reads information from the file
	while ($curr = fgets($fileptr, 512)):
		$curr = trim($curr);
		$db->query("INSERT INTO words VALUES ('$curr', null)");
	endwhile;

	#Link to anagram script
	echo "<a href = assignment4_anagramdisplay.html>Link to anagram game</a>";

?>