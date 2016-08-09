<?php
	#Connects to DB
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	if ($db->connect_error):
		echo "Error - Could not connect to MySQL";
		exit;
	endif;

	#Drops all existing tables
	$db->query("drop table makers");
	$db->query("drop table schedules_makers");
	$db->query("drop table schedules_times");
	$db->query("drop table users");
	$db->query("drop table times_selected");

	#Establishes all neccessary tables, primary key is ID
	$sql = "CREATE TABLE makers (
		Name VARCHAR(50),
		Email VARCHAR(50),
		Password VARCHAR(50))";
	
	$db->query($sql);

	$sql = "CREATE TABLE schedules_makers (
		Name VARCHAR(50),
		Schedule VARCHAR(50),
		ID int primary key AUTO_INCREMENT)";

	$db->query($sql);

	$sql = "CREATE TABLE schedules_times (
		Time VARCHAR(50),
		ID int)";

	$db->query($sql);

	$sql = "CREATE TABLE users (
		Name VARCHAR(50),
		Email VARCHAR(50),
		ID int)";

	$db->query($sql);

	$sql = "CREATE TABLE times_selected (
		Time VARCHAR(50),
		ID int)";

	$db->query($sql);

	#Insert 3 test makers
	$db->query("INSERT INTO makers VALUES ('maker1', 'maker1@test.com', 'password1')");
	$db->query("INSERT INTO makers VALUES ('maker2', 'maker2@test.com', 'password2')");
	$db->query("INSERT INTO makers VALUES ('Eric Wuerschmidt', 'etwuerschmidt@yahoo.com', 'mypassword')");

	echo "<a href=assignment2_login.php>Go to Login Script</a>";	  
?>