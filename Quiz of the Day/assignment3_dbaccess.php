<?php
	#Sets a session variable of the number of questions correct
	session_start();
	$_SESSION["cor"] = $_GET["cor"];
	if (isset($_SESSION["num"])):	
		$_SESSION["num"] += 1;
	else:
		$_SESSION["num"] = 0;
	endif;

	#Selects a random quiz seeded by the current date
	$seed = new DateTime(date("Y-m-d"));
	$seed = $seed->getTimestamp();
	$db = new mysqli('localhost', "assignment3", "assignment3", "assignment3_db");
	$query = "SELECT Quiz FROM quiz_id ORDER BY RAND($seed) LIMIT 1";
	$result = $db->query($query);
	$row = $result->fetch_array();
	echo "<h2><font face = verdana>$row[0]</font></h2>|";
	
	#Selects quiz questions for the quiz of the day
	$cur_quiz = $row[0];
	$quest = "";
	$check = "set";
	$query = "SELECT Questions FROM quiz_questions WHERE Quiz = '$row[0]' LIMIT 1 OFFSET " . $_SESSION["num"];
	$result = $db->query($query);
	$row = $result->fetch_array();

	#If the question does not exist, send back end of quiz information
	if (is_null($row[0])):
		$check = null;
		session_unset();
		echo "<h4><font face = verdana>End of quiz!</font><h4>|";
		echo "<b>Your score for today is: $_GET[cor] / $_GET[total]</b></br>|";
		$query = "INSERT INTO stats VALUE ('$cur_quiz', '$_GET[cor]', '$_GET[total]', 1)";
		$db->query($query);
		$query = "SELECT QCorrect FROM stats WHERE Quiz = '$cur_quiz'";
		$tcorrect = 0;
		$result = $db->query($query);
		$rows = $result->num_rows;
		for ($i = 0; $i < $rows; $i++):
			$row = $result->fetch_array();
			foreach ($row as $key => $value):
				if ($key === 0):
					$tcorrect += $value;
				endif;
			endforeach;
		endfor;

		$query = "SELECT QTotal FROM stats WHERE Quiz = '$cur_quiz'";
		$ttotal = 0;
		$result = $db->query($query);
		$rows = $result->num_rows;
		for ($i = 0; $i < $rows; $i++):
			$row = $result->fetch_array();
			foreach ($row as $key => $value):
				if ($key === 0):
					$ttotal += $value;
				endif;
			endforeach;
		endfor;
		$tdeci = 100 * $tcorrect / $ttotal;
		echo "Overall score for today is: $tdeci% correct</br>|";

		$query = "SELECT Users FROM stats WHERE Quiz = '$cur_quiz'";
		$tusers = 0;
		$result = $db->query($query);
		$rows = $result->num_rows;
		for ($i = 0; $i < $rows; $i++):
			$row = $result->fetch_array();
			foreach ($row as $key => $value):
				if ($key === 0):
					$tusers += $value;
				endif;
			endforeach;
		endfor;
		echo "$tusers user(s) have taken this quiz|";
		echo "set|";
		echo "set";
	
	#Else send the question information
	else:
		echo "<h4><font face = verdana>$row[0]</font><h4>|";
		$quest = $row[0];
	endif;

	#Send the answer information for the given question
	$insert = 1;
	$query = "SELECT Answers FROM questions_answers WHERE Question = '$row[0]'";
	$result = $db->query($query);
	$rows = $result->num_rows;
	for ($i = 0; $i < $rows; $i++):
		$row = $result->fetch_array();
		foreach ($row as $key => $value):
			if ($key === 0 && !is_null($row[0])):
				echo "<input type = checkbox id = $insert value = \"$value\" onclick = check_answer()><font face = verdana size = 4>$value</font></input></br>|";
				$insert++;
			endif;
		endforeach;
	endfor;

	#Sned the correct answer for the given question
	$query = "SELECT Correct_Answer from quiz_questions WHERE Questions = '$quest'";
	$result = $db->query($query);
	$row = $result->fetch_array();
	if (!is_null($check)):
		echo "$row[0]";
	endif;

?>