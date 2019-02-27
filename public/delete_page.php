<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$current_page = find_page_by_id($_GET["page"],false);
	if (!$current_page) {
		redirect_to("manage_content.php");
	}

	$id = $current_page["id"];
	$query = "DELETE from pages where id={$id} LIMIT 1";
	$result = mysqli_query($conn,$query);

	if ($result && mysqli_affected_rows($conn) == 1) {
		$_SESSION["message"] = "Page deleted";
		redirect_to("manage_content.php");
	} else {
		$message = "Failure to delete page";
		redirect_to("new_subject.php?page={$id}");
	}
?>

