<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$id = $_GET["id"];
	if (!$id) {
		redirect_to("manage_admins.php");
	}

	$query = "DELETE from admins where id={$id} LIMIT 1";
	$result = mysqli_query($conn,$query);

	if ($result && mysqli_affected_rows($conn) == 1) {
		$_SESSION["message"] = "User deleted";
	} else {
		$_SESSION["message"] = "Failure to delete page";
	}
	redirect_to("manage_admins.php");
?>

