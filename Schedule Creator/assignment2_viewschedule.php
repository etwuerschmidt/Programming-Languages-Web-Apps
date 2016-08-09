<?php
	session_start();
	#Sets Session variables based on Get variables for access outside of this script
	if (isset($_GET["name"]) && isset($_GET["schedule"])):
		$_SESSION["name"] = $_GET["name"];
		$_SESSION["schedule"] = $_GET["schedule"];
	endif;

	#Sets Session variables based on Post variables for access outside of this script
	if (isset($_POST["Submit"])):
		$_SESSION["times"] = $_POST["times"];
		$_SESSION["Submit"] = $_POST["Submit"];
		header("Location: assignment2_usersubmit.php");
		exit();
	endif;
?>

<!DOCTYPE html>
<html>
<head></head>
<body>
	<form action = "assignment2_viewschedule.php";
		method = "POST">

<?php
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	#Retrive times for the given schedule ID
	$query = "SELECT * from schedules_times where ID = '$_SESSION[schedule]'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	$comp = null;
	$col_count = 1;
	$time_array = array();
	#Set up table column headers
	echo "<table border = 1>";
	echo "<tr><td><b>User</b></td>";
	echo "<td><b>Action</b></td>";
	$sub_value = str_replace(" ", "+", $_SESSION["schedule"]);
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key=>$value):
			if ($key == "Time" && $comp != $value):
				#Inserting times at column headers
				echo "<td>$value</td>";
				$comp = $value;
				array_push($time_array, $value);
				$col_count++;
			endif;	
		endforeach;
	endfor;
	echo "</tr>";

	$comp = null;
	$comp_count = 0;
	#Retrive users for the given schedule ID
	$query = "SELECT * from users where ID = '$_SESSION[schedule]'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key=>$value):
			if ($key == "Name" && $comp != $value):
				#Inserting users at start of rows
				echo "<tr><td>$value</td>";
				$comp = $value;
			while ($comp_count < $col_count):
				if ((trim($comp) == trim($_SESSION["name"])) && $comp_count == 0 && !isset($_POST["Edit"])):
					#Sets Edit button upon initial page load for specified user from Get variable
					echo "<td><button name = Edit type = submit>Edit</button></td>";
				elseif	((trim($comp) == trim($_SESSION["name"])) && $comp_count == 0 && isset($_POST["Edit"])):
					#Edit button replaced with Submit button after Edit is selected
					echo "<td><button name = Submit type = submit value = $_SESSION[schedule]>Submit</button></td>";
				elseif (trim($comp) == trim($_SESSION["name"]) && isset($_POST["Edit"])):
					#Checkboxes appear after Edit is selected
					$check_val = str_replace(" ", "+", $time_array[$comp_count-1]);
					echo "<td><input type = checkbox name = times[] value = $check_val/></td>";
				else:
					#Blank boxes if user is not from the Get variable
					echo "<td></td>";
				endif;
				$comp_count++;
			endwhile;
			endif;
		endforeach;
		$comp_count = 0;
		
	endfor;
	echo "</table>";
?>
	
	</form>
</body>
</html>