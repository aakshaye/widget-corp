<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php
	find_selected_page();
?>
<?php 
	if (!$current_subject) {
		redirect_to("manage_content.php");
	}
?>
<?php
	if (isset($_POST["submit"])) {
		//validations
		$required_fields = array("menu_name","position","visible","content");
		validate_presences($required_fields);

		$fields_with_length_limit = array("menu_name");
		validate_lengths($fields_with_length_limit);
		
		if (empty($errors)) {
			$subject_id = (int)$current_subject["id"];
			$menu_name = mysql_prep($_POST["menu_name"]);
			$position =(int)$_POST["position"];
			$visible =(int)$_POST["visible"];
			$content = mysql_prep($_POST["content"]);

			$query = "INSERT INTO pages (";
			$query .= " subject_id,menu_name,position,visible,content";
			$query .= ") VALUES (";
			$query .= "{$subject_id},'{$menu_name}',{$position},{$visible},'{$content}')";
			$result = mysqli_query($conn,$query);
			
			if ($result) {
				$_SESSION["message"] = "Page created";
				redirect_to("manage_content.php");
			} else {
				$message = "Failure to create page";
				redirect_to("new_page.php?subject=".urlencode($current_subject["id"]));
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
		<h2>Create Page</h2>		
		<form action="new_page.php?subject=<?php echo urlencode($current_subject["id"]);?>" method="post">
			<p>Menu name: 
				<input type="text" name="menu_name"/>
			</p>
			<p>Position:
			<select name="position">
				<?php
					$page_count = mysqli_num_rows(find_pages_for_subject($current_subject["id"]),false);
					for ($count=1; $count <= $page_count+1; $count++) {
						echo "<option value=\"{$count}\">{$count}</option>";
					}
				?>
			</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" />No
				<input type="radio" name="visible" value="1" />Yes
			</p>
			Content:
			<p> 
				<textarea rows="15" cols="50" name="content">
				</textarea>
			</p>
			<p>
				<input type="submit" name="submit" value="Create Page"/>
			</p>
		</form>
		<a href="manage_content.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	
