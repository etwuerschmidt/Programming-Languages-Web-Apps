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
<head></head>
<body>
	<form action = "assignment2_countusers.php"
		method = "POST">

<?php
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	#Select all schedules associated with the current maker that is logged in
	$query = "SELECT * from schedules_makers where Name = '$_SESSION[maker]'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	$count = 1;

	echo "<b><font size = 6>Available Schedules:</font></b></br>";
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		echo "<ul>";
		foreach ($row as $key=>$value):
			if (trim($key) == "Schedule"):
				$send_val = str_replace(" ", "+", $value);
				#Displays all schedules in a list format
				echo "<li>$value <button name = Finalize value = $send_val>Finalize</button></br></li>";
			endif;
		endforeach;
		echo "</ul>";
	endfor;
?>

	</form>
</body>
</html>