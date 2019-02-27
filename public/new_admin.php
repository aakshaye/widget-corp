<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<?php
	if (isset($_POST["submit"])) {
		$username = mysql_prep($_POST["username"]);
		$password = password_encrypt($_POST["password"]);

		$required_fields = array("username","password");
		validate_presences($required_fields);

		if (empty($errors)) {
			$query = "INSERT INTO admins (";
			$query .= "username, hashed_password ";
			$query .= ") VALUES ( ";
			$query .= "'{$username}', '{$password}') ";

			$result = mysqli_query($conn,$query);

			if ($result) {
				$_SESSION["message"] = "Admin User created";
				redirect_to("manage_admins.php");
			} else {
				$message = "Failure to create user";
			}
		}
	}
?>

<div id="main">
	<div id="navigation">
	</div>
	<div id="page">
		<?php 
			if (!empty($message)) {
				echo $message; 
			}
			echo form_errors($errors);
		?>
		<h2>Create Admin</h2>		
		<form action="new_admin.php" method="post">
			<p>Username: 
				<input type="text" name="username"/>
			</p>
			<p>Password:
				<input type="password" name="password"/>
			</p>
			<p>
				<input type="submit" name="submit" value="Create Admin"/>
			</p>
		</form>
		<a href="manage_admins.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	
