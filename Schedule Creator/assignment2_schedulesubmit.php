<?php
	session_start();
	#Checks if any forms were left blank previously
	if (empty($_POST["schedule_name"]) || empty($_POST["dates"]) || empty($_POST["namesemails"])):
		header("Location: assignment2_menu.php");
		$_SESSION["Invalid"] = "set";
		exit();
	endif;
	
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	
	$mailpath = 'C:\xampp\PHPMailer-master\PHPMailer-master';
	$path = get_include_path();
	set_include_path($path . PATH_SEPARATOR . $mailpath);
	require 'PHPMailerAutoload.php';

	#Loads times, schedule name, and email addresses into schedules_makers table
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	$db->query("INSERT INTO schedules_makers VALUES ('$_SESSION[maker]', '$_POST[schedule_name]', 'NULL')");

	#Retrieves ID based on schedule name
	$result = $db->query("SELECT ID from schedules_makers WHERE Schedule = '$_POST[schedule_name]'");
	$rows = $result->num_rows;
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key=>$value):
			if ($key == "ID"):
				$ID = $value;
			endif;
		endforeach;
	endfor;

	#Loads times and schedule ID into schedules_times table
	$dates = explode(",", strip_tags($_POST["dates"]));
	foreach($dates as $key):
		$db->query("INSERT INTO schedules_times VALUES ('$key', '$ID')");
	endforeach;
	
	#Sends emails to all users that were entered
	$info = explode(",", strip_tags($_POST["namesemails"]));
	$ID = trim($ID);
	foreach($info as $key=>$value):
		$sep = explode(":", $value);
		$query = "INSERT INTO users VALUES ('$sep[0]', '$sep[1]', '$ID')";
		$db->query($query);
		#Replace all spaces with pluses in order to send data with GET
		$no_space_sep = str_replace(" ", "+", $sep[0]);
		$no_space_sep = trim($no_space_sep);
		$mail = new PHPMailer();
		 
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPSecure = "tls"; // sets tls authentication
		$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server; or your email service
		$mail->Port = 587; // set the SMTP port for GMAIL server; or your email server port
		$mail->Username = "cs4501.fall15.assignment2@gmail.com"; // email username
		$mail->Password = "UVACSROCKS"; // email password


		$sender = "cs4501.fall15.assignment2@gmail.com";
		$receiver = $sep[1];
		$subj = "You Have a New Schedule to View!";
		$mail->IsHTML(true);
		#Varialbes to send are the user's name and the schedule's ID
		$msg = "View your schedule at: " . "<a href = localhost/4501/assignment2_viewschedule.php?name=$no_space_sep&schedule=$ID>localhost/4501/assignment2_viewschedule.php?name=$no_space_sep&schedule=$ID</a>";

		// Put information into the message
		$mail->addAddress($receiver);
		$mail->SetFrom($sender);
		$mail->Subject = "$subj";
		$mail->Body = "$msg";

		// echo 'Everything ok so far' . var_dump($mail);
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} 
		
	endforeach;
	$_SESSION["new_schedule"] = "set";
	
	#Return back to the menu
	header("Location: assignment2_menu.php");
?>