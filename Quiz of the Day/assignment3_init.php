<?php
	error_reporting(0);
	$fileptr = fopen("assignment3_quiz_info.txt", "r");
	$db = new mysqli('localhost', "assignment3", "assignment3", "assignment3_db");

	#Drops tables and creates them in the database
	$db->query("drop table quiz_questions");
	$db->query("drop table questions_answers");
	$db->query("drop table quiz_ID");
	$db->query("drop table stats");

	$sql = "CREATE TABLE questions_answers (
		Question VARCHAR(100) not null,
		Answers VARCHAR(50) not null,
		ID int)";

	$db->query($sql);

	$sql = "CREATE TABLE quiz_questions (
		Quiz VARCHAR(50),
		Questions VARCHAR(100),
		Correct_Answer VARCHAR(50))";

	$db->query($sql);

	$sql = "CREATE TABLE quiz_ID (
		Quiz VARCHAR(50),
		ID int)";

	$db->query($sql);

	$sql = "CREATE TABLE stats (
		Quiz VARCHAR(50),
		QCorrect int,
		QTotal int,
		Users int)";

	$db->query($sql);


	$correct = array();
	$ID = 1;
	while ($curr = fgets($fileptr, 512)):
		#Splits information from file
		$quiz_name = explode("@", $curr);
		$questions = explode(":", $quiz_name[1]);
		
		foreach ($questions as $key):
			if (strpos($key, "?") !== FALSE):
				$quest = $key;
				
			else:
				$answers = explode("/", $key);
				foreach ($answers as $val):
					if (strpos($val, "~") !== FALSE):
						$val = trim($val, "~");
						array_push($correct, $val);
						$cor = $val;
					else:
						
					endif;
					if ($quest != null && $val != null):
						#Inserts questions and answers into table
						$db->query("INSERT INTO questions_answers VALUES ('$quest', '$val', '$ID')");
					endif;
				endforeach;
			endif;
			if (isset($cor) && !is_null($cor)):
				#Inserts quiz and questions into table
				$db->query("INSERT INTO quiz_questions VALUES ('$quiz_name[0]', '$quest', '$cor')");
			endif;
			$cor = null;
			
			
		endforeach;
		if (trim($quiz_name[0]) != ""):
			#Inserts quiz and ID into table
			$db->query("INSERT INTO quiz_ID VALUES ('$quiz_name[0]', '$ID')");
		endif;
		if (isset($quiz_name[1])):
			$ID += 1;
		endif;
	endwhile;

	#Link to quiz taking script
	echo "<a href=assignment3_quizdisplay.php>Go to Quiz of the Day Script</a>";	 
?>

