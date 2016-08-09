<?php
	#ensures no getting into the system by just selecting the back button
	session_start();
	if (!isset($_SESSION["login"])):
		header("Location: assignment2_login.php");
		exit();

	#Sets a variety of headers based on previous actions
	elseif (isset($_SESSION["Invalid"])):
		echo "<b><font size = 6>Invalid Schedule - Not Created</font></b></br>";
		unset($_SESSION["Invalid"]);
	elseif (isset($_SESSION["Repeat"])):
		echo "<b><font size = 6>Schedule Already Exists</font></b></br>";
		unset($_SESSION["Repeat"]);
	elseif (isset($_SESSION["new_schedule"])):
		echo "<b><font size = 6>Schedule Created!</font></b></br>";
		unset($_SESSION["new_schedule"]);
	elseif (isset($_SESSION["final_time"])):
		echo "<b><font size = 6>Emails Successfully Sent!</font></b></br>";
		unset($_SESSION["final_time"]);
	else:
		echo "<b><font size = 6>Login Successful!</font></b></br>";
	endif;
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
	1) <a href = "assignment2_createschedule.php">Create New Schedule</a></br>
	</br>
	2) <a href = "assignment2_finalizeschedule.php">Finalize a Schedule</a></br>
	</br>
	3) <a href = "assignment2_login.php">Log Out</a></br>
</body>
</html>


