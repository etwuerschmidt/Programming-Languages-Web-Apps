<?php
	session_start();
	unset($_SESSION["new_schedule"]);
	$mailpath = 'C:\xampp\PHPMailer-master\PHPMailer-master';
	$path = get_include_path();
	set_include_path($path . PATH_SEPARATOR . $mailpath);
	require 'PHPMailerAutoload.php';

	$mod_post = str_replace("+", " ", $_POST["Finalize"]);
	$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
	#Retrieves the schedule ID from whichever schedule was chosen on the previous page
	$result = $db->query("SELECT ID from schedules_makers where Schedule = '$mod_post'");
	$rows = $result->num_rows;
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key => $value):
			if ($key == "ID"):
				$ID = $value;
			endif;
		endforeach;
	endfor;

	#Retrives user selected times for the given ID
	$query = "SELECT Time from times_selected WHERE ID = '$ID'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	$time_count = array();
	$users = array();
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key=>$value):
			if ($key === "Time"):
				#If the time is within the array, add 1, else set the initial value to 1
				$bool = in_array(trim($value), array_keys($time_count));
				if ($bool):
					$time_count[$value] += 1;
				else:
					$time_count[$value] = 1;
				endif;
			endif;	

		endforeach;
	endfor;

	#Sort the array of time frequencies
	asort($time_count);
	#Return the value with the highest frequency
	$last = array_pop(array_keys($time_count));
	
	#Retrieves all emails associated with the given schedule ID
	$query = "SELECT Email from users WHERE ID = '$ID'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key=>$value):
			if ($key === "Email"):
				array_push($users, $value);
			endif;
		endforeach;
	endfor;

	#Emails all users their meeting time
	foreach ($users as $key):
		$mail = new PHPMailer();
		 
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPSecure = "tls"; // sets tls authentication
		$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server; or your email service
		$mail->Port = 587; // set the SMTP port for GMAIL server; or your email server port
		$mail->Username = "cs4501.fall15.assignment2@gmail.com"; // email username
		$mail->Password = "UVACSROCKS"; // email password

		$sender = "cs4501.fall15.assignment2@gmail.com";
		$receiver = $key;
		$subj = "Your Meeting Time";
		$msg = "Your meeting time is $last";

		// Put information into the message
		$mail->addAddress($receiver);
		$mail->SetFrom($sender);
		$mail->Subject = "$subj";
		$mail->Body = "$msg";

		if(!$mail->send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} 
		#	else { echo 'Message has been sent'; }
	endforeach;
	$_SESSION["final_time"] = "set";
	header("Location: assignment2_menu.php");
?>