<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php
	find_selected_page();
?>
<?php 
	if (!$current_subject) {
		redirect_to("manage_content.php");
	}
?>

<?php include("../includes/layouts/header.php"); ?>
<?php 
	if (isset($_POST["submit"])) {
		//validations
		$message = "";
		$required_fields = array("menu_name","position","visible");
		validate_presences($required_fields);
 
		$fields_with_length_limit = array("menu_name");
		validate_lengths($fields_with_length_limit);
		
		if (empty($errors)) {
		//perform update
			$id = $current_subject["id"];
			$menu_name = mysql_prep($_POST["menu_name"]);
			$position =(int)$_POST["position"];
			$visible =(int)$_POST["visible"];

			$query = "UPDATE subjects SET ";
			$query .= "menu_name = '{$menu_name}', ";;
			$query .= "position = {$position}, ";
			$query .= "visible = {$visible} ";
			$query .= "WHERE id = {$id} ";
			$query .= "LIMIT 1;";
			//echo $query; exit;
			$result = mysqli_query($conn,$query);

			if ($result && mysqli_affected_rows($conn) >= 0) {
				$_SESSION["message"] = "Subject updated";
				redirect_to("manage_content.php");
			} else {
				$message = "Failure to update Subject";
			}	
		}
	} else {
		
	}
?>	
<div id="main">
	<div id="navigation">
		<?php
			echo navigation($current_subject,$current_page);
		?>
	</div>
	<div id="page">
		<?php 
			if (!empty($message)) {
				echo "<div class=\"message\">".htmlentities($message)."</div>";
			} 			
			echo form_errors($errors);
		?>
		<h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>		
		<form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>Menu name: 
				<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>"/>
			</p>
			<p>Position:
			<select name="position">
				<?php
					$subject_count = mysqli_num_rows(find_all_subjects(false));
					for ($count=1; $count <= $subject_count+1; $count++) {
						echo "<option value=\"{$count}\"";
						if ($current_subject["position"] == $count) {
							echo " selected";	
						}
						echo ">{$count}</option>";
					}
				?>
			</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible"<?php if($current_subject["visible"] == 0) { echo " checked"; } ?> value="0" />No
				<input type="radio" name="visible"<?php if($current_subject["visible"] == 1) { echo " checked"; } ?> value="1" />Yes
			</p>
			<p>
				<input type="submit" name="submit" value="Edit Subject"/>
			</p>
		</form>
		<a href="manage_content.php">Cancel</a>&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete Subject</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	
