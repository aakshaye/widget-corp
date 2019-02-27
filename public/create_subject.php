<?php
require_once("../includes/session.php");
require_once("../includes/db_connect.php"); 
require_once("../includes/functions.php");
confirm_logged_in();
require_once("../includes/validation_functions.php"); 
	if (isset($_POST["submit"])) {
		$menu_name = mysql_prep($_POST["menu_name"]);
		$position =(int)$_POST["position"];
		$visible =(int)$_POST["visible"];

		//validations
		$required_fields = array("menu_name","position","visible");
		validate_presences($required_fields);

		$fields_with_length_limit = array("menu_name");
		validate_lengths($fields_with_length_limit);
		
		if (!empty($errors)) {
			$_SESSION["errors"] = $errors;
			redirect_to("new_subject.php");
		}

		$query = "INSERT INTO subjects (";
		$query .= " menu_name,position,visible";
		$query .= ") VALUES (";
		$query .= "'{$menu_name}',{$position},{$visible})";
		$result = mysqli_query($conn,$query);

		if ($result) {
			$_SESSION["message"] = "Subject created";
			redirect_to("manage_content.php");
		}
	} else {
		$_SESSION["message"] = "Failure to create Subject";
		redirect_to("new_subject.php");
	}

if (isset($conn)) {	mysqli_close($conn); }
?>