<?php

#Sets cookie to deter repeat users from taking quiz
session_start();
$new = strtotime("tomorrow 00:00:00");
$sec = session_get_cookie_params();
$exp = $new - time();
if (isset($_COOKIE["RepeatUser"])):
	header("Location: assignment3_quiztaken.html");
	break;
else:
	setcookie("RepeatUser", "set", $new);
	#echo "First time taking quiz.";
endif;
?>

<!DOCTYPE html>
<html>
	<title>Quiz of the Day</title>
<script type = "text/javascript">

	var cor = 0;
	var total = 0;
	var data = "";

	//Checks the checked answer with the correct answer
	function check_answer() {
		var comp = document.getElementById("c").innerHTML;
		document.getElementById(1).disabled = true;
		document.getElementById(2).disabled = true;
		document.getElementById(3).disabled = true;
		
		if (document.getElementById(1).checked) {
			var check = document.getElementById(1).value;
		} else if (document.getElementById(2).checked) {
			var check = document.getElementById(2).value
		} else if (document.getElementById(3).checked) {
			var check = document.getElementById(3).value;
		} else {
			var check = "No answer selected";
		}
		
		if (check == comp) {
			alert("Correct!");
			cor += 1;
		} else {
			alert("Incorrect!");
		}
		total += 1;
		data = 'cor=' + cor + '&' + 'total=' + total;
											
	}

	//Makes a request to assignment3_dbaccess.php
	function makeRequest(url) {
		var httpRequest;

		if (window.XMLHttpRequest) {
			httpRequest = new XMLHttpRequest();
			if (httpRequest.overrideMimeType) {
				httpRequest.overrideMimeType('text/xml');
			}
		}
		httpRequest.onreadystatechange = function() { alertContents(httpRequest);};
		httpRequest.open('GET', url+"?cor="+cor+"&total="+total, true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpRequest.send(data);
	}

	//Receives information and sets it on the page accordingly
	function alertContents(httpRequest) {
		if (httpRequest.readyState == 4) {
			if (httpRequest.status == 200) {
				var qaa = httpRequest.responseText.split("|");
				document.getElementById("quiz").innerHTML = qaa[0];
				document.getElementById("questions").innerHTML = qaa[1];

				if (qaa[6] == null) {
					document.getElementById("a1").innerHTML = qaa[2];
					document.getElementById("a2").innerHTML = qaa[3];
					document.getElementById("a3").innerHTML = qaa[4];
					document.getElementById("c").innerHTML = qaa[5];
					document.getElementById("next").value = "Next Question";
				}
				else {
					document.getElementById("a1").innerHTML = qaa[2];
					document.getElementById("a2").innerHTML = qaa[3];
					document.getElementById("a3").innerHTML = qaa[4];
					document.getElementById("c").innerHTML = qaa[5];
					document.getElementById("next").style.visibility = "hidden";
					
				}
			} else {
				alert('There was a problem with the request');
			}
		}
	}

	
	

</script>

<body bgcolor="#E6E6FA">
	<h1><center><font face = "verdana" size = "10">Quiz of the Day!</font></center></h1>
	<center>
		
		<div id = "quiz"><font face = "verdana"></font></div>
		<div id = "questions"><b></b></div>
		<form name = "form1"
			action = "assignment3_quizdisplay.php"
			method = "POST">
			<div id = "a1"></div>
			<div id = "a2"></div>
			<div id = "a3"></div>
			
			<div id = "c" style="visibility: hidden"></div>
			<div id = "Final Statement"></div>
			<div id = "User Score"></div>
			<div id = "Overall Score"></div>
								
		</form>
			
		<input type = "submit" id = "next" value = "Start Quiz!" onclick="makeRequest('assignment3_dbaccess.php')"/>
	</center>
	

</body>
</html>