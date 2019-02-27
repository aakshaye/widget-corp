<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<?php
	if (isset($_POST["submit"])) {
		$username = mysql_prep($_POST["username"]);
		$password = $_POST["password"];

		$required_fields = array("username","password");
		validate_presences($required_fields);

		$admin = attempt_login($username,$password);
		//print_r($admin); exit;
		if ($admin) {
			$_SESSION["admin_id"] = $admin["id"];
			$_SESSION["username"] = $admin["username"];
			redirect_to("admin.php");
		} else {
			$_SESSION["message"] = "Username/Password not found";
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
		<h2>Login</h2>		
		<form action="login.php" method="post">
			<p>Username: 
				<input type="text" name="username"/>
			</p>
			<p>Password:
				<input type="password" name="password"/>
			</p>
			<p>
				<input type="submit" name="submit" value="Submit"/>
			</p>
		</form>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	
