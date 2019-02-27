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
	if (!$current_page) {
		redirect_to("manage_content.php");
	}
?>
<?php $page = find_page_by_id($current_page["id"],false); ?>
<?php
	if (isset($_POST["submit"])) {
		//validations
		$required_fields = array("menu_name","position","visible","content");
		validate_presences($required_fields);

		$fields_with_length_limit = array("menu_name");
		validate_lengths($fields_with_length_limit);
		if (empty($errors)) {
			$id = $page["id"];
			$menu_name = mysql_prep($_POST["menu_name"]);
			$position =(int)$_POST["position"];
			$visible =(int)$_POST["visible"];
			$content = mysql_prep($_POST["content"]);

			$query = "UPDATE pages SET ";
			$query .= "menu_name = '{$menu_name}',";
			$query .= "position = {$position},";
			$query .= "visible = {$visible},";
			$query .= "content = '{$content}'";
			$query .= "where id = {$id} ";
			$query .= "LIMIT 1";
			$result = mysqli_query($conn,$query);
			
			if ($result && mysqli_affected_rows($conn) >=0) {
				$_SESSION["message"] = "Page updated";
				redirect_to("manage_content.php");
			} else {
				$message = "Failure to update page";
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
		<h2>Edit Page: <?php echo $page["menu_name"];?></h2>
		<form action="edit_page.php?page=<?php echo urlencode($page["id"]);?>" method="post">
			<p>Menu name: 
				<input type="text" name="menu_name" value="<?php echo $page["menu_name"];?>" />
			</p>
			<p>Position:
			<select name="position">
				<?php
					$page_count = mysqli_num_rows(find_pages_for_subject($page["subject_id"],false));
					for ($count=1; $count <= $page_count; $count++) {
						echo "<option value=\"{$count}\"";
						if ($count == $page["position"]) {
							echo " selected";
						}
						echo ">{$count}</option>";
					}
				?>
			</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php if($current_page["visible"] == 0) { echo " checked"; } ?>/>No
				<input type="radio" name="visible" value="1" <?php if($current_page["visible"] == 1) { echo " checked"; } ?>/>Yes
			</p>
			Content:
			<p> 
				<textarea rows="15" cols="50" name="content"><?php echo htmlentities($page["content"]);?></textarea>
			</p>
			<p>
				<input type="submit" name="submit" value="Edit Page"/>
			</p>
		</form>
		<a href="manage_content.php?page=<?php echo urlencode($page["id"]);?>">Cancel</a>
		<a href="delete_page.php?page=<?php echo urlencode($current_page["id"]); ?>" onclick="return confirm('Are you sure?');">Delete Page</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	
