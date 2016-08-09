<?php
	#ensures no getting into the system by just selecting the back button
	session_start();
	if (!isset($_SESSION["login"])):
		header("Location: assignment2_login.php");
		exit();
	endif;
?>

<!DOCTYPE html>
<html>
<head>
</head>
<form action = "assignment2_schedulesubmit.php"
		id = "form"	method = "POST">

	<b><font size = 4>Schedule Name:</b></br>
	<input type = text name = "schedule_name"></br>
	</br>
	<b><font size = 4>Dates: </b></br>
	<i><font size = 2.5>Example Format - MM-DD-YYYY @ HH:MM, </i></br>
	<TEXTAREA name = "dates" ROWS = 10 COLS = "50" form = "form"></TEXTAREA></br>
	</br>
	<b><font size = 4>Names and Emails: </b></br>
	<i><font size = 2.5>Example Format - FirstName LastName:name@example.com, </i></br>
	<TEXTAREA name = "namesemails" ROWS = 10 COLS = "50" form = "form"></TEXTAREA></br>
	</br>
	<input type = "submit" name = "Submit">
</form>
</html>