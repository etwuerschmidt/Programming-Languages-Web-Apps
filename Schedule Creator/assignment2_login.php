<?php
	#ensures no getting into the system by just selecting the back button
	session_start();
	unset($_SESSION["login"]);
	unset($_SESSION["new_schedule"]);
	unset($_SESSION["final_time"]);
	
?>

<!DOCTYPE html>
<html>
<title>Assigment 2</title>
<head>
</head>
<body>
	<?php
		#If submit is entered, the following is executed
		if (isset($_POST["submit"])):
			$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
			$query = "SELECT * FROM makers WHERE makers.Email = '$_POST[email]' AND makers.Password = '$_POST[password]'";
			$result = $db->query($query);
			#If query returns nothing, invalid login
			if (is_null($result->fetch_array())):
				echo "<b><font size = 8>Invalid Login</font><b></br>";
			#Else, valid login
			else:
				$_SESSION["login"] = "set";
				$query = "SELECT * FROM makers";
				$result = $db->query($query);
				$rows = $result->num_rows;

				for ($i = 0; $i < $rows; $i++):
					$row = $result->fetch_array();
					foreach ($row as $key=>$value):
						if ($key == "Name: "):
							$temp = $value;
						endif;
						if ($value == $_POST["email"]):
							$_SESSION["maker"] = $temp;
						endif;
					endforeach;
				endfor;
				header("Location: assignment2_menu.php");
				exit();
			endif;
		#If forgot password is entered, following is executed
		elseif (isset($_POST["forgot"])):
			$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
			$query = "SELECT * FROM makers";
			$result = $db->query($query);
			$rows = $result->num_rows;

			#Get password from database
			for ($i = 0; $i < $rows; $i++):
				$row = $result->fetch_array();
				foreach ($row as $key):
					if ($key == $_POST["email"]):
						$name_compare = $key;
					endif;
					if (isset($name_compare)):
						$pass = $key;
					endif;
				endforeach;
			endfor;

			#Send email (from Soffa's posted code!)
			$mailpath = 'C:\xampp\PHPMailer-master\PHPMailer-master';
			$path = get_include_path();
			set_include_path($path . PATH_SEPARATOR . $mailpath);
			require 'PHPMailerAutoload.php';
			  
			$mail = new PHPMailer();
			 
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->SMTPAuth = true; // enable SMTP authentication
			$mail->SMTPSecure = "tls"; // sets tls authentication
			$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server; or your email service
			$mail->Port = 587; // set the SMTP port for GMAIL server; or your email server port
			$mail->Username = "cs4501.fall15.assignment2@gmail.com"; // email username
			$mail->Password = "UVACSROCKS"; // email password

			$sender = "cs4501.fall15.assignment2@gmail.com";
			$receiver = strip_tags($_POST["email"]);
			$subj = "Your Forgotten Password";
			$msg = "Your password is $pass";

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
			else { echo 'Message has been sent'; }

		#If add maker is entered, following is executed
		elseif (isset($_POST["add"])):
			$db = new mysqli('localhost', 'assignment2_user', 'rchpRKjsnj2JZTCm', 'assignment2_db');
			#checks if any fields are empty
			if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])):
				header("Location: assignment2_login.php");
				exit();
			endif;
			$query = "INSERT INTO makers VALUES ('$_POST[name]', '$_POST[email]', '$_POST[password]')";
			$db->query($query);
			echo 'Maker successfully added.';
			
		endif;
	?>
	<form action = "assignment2_login.php"
		method = "POST">

	<b>Please enter your information</b></br></br>
	Email address: <input type = text name = "email"></br>
	Password: <input type = password name = "password"></br></br>
	If you are adding a new maker, please enter your name: </br>
	<input type = text name = "name"></br></br>
	<input type = submit name = "submit" value = "Submit">
	<input type = submit name = "forgot" value = "Forgot Password?">
	<input type = submit name = "add" value = "Add Maker"></br>
	</form>
</body>
</html>
