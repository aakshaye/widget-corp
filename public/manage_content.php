<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_selected_page(); ?>

<div id="main">
	<div id="navigation">
		<br/>
		<a href="admin.php">&laquo; Main Menu</a>
		<?php
			echo navigation($current_subject,$current_page);
		?>
		<a href="new_subject.php">+ Add Subject</a>
	</div>
	<div id="page">
		<?php echo message(); ?>		
		<?php if ($current_subject) { ?>
				<h2>Manage Subject</h2>
				Menu Name:<?php echo htmlentities($current_subject["menu_name"]); ?><br/>
				Position:<?php echo $current_subject["position"]; ?><br/>
				Visible: <?php echo $current_subject["visible"]?"Yes":"No"; ?><br/>
				<a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Edit Subject</a><hr/>
				<h2>Pages in this subject:</h2>
				<?php 
					$page_set = find_pages_for_subject($current_subject["id"],false);
					$output = "<ul>";
					while ($page = mysqli_fetch_assoc($page_set)) {
						$output .= "<li><a href=\"manage_content.php?page=";
						$output .= urlencode($page["id"])."\">";
						$output .= htmlentities($page["menu_name"]);
						$output .= "</a></li>";
					}
					$output .= "</ul>";
					echo $output;
				?>
				+ <a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]);?>">Add a new page to this subject</a>
			<?php } elseif($current_page) { ?>
				<h2>Manage Page</h2>
				Menu Name:<?php echo htmlentities($current_page["menu_name"]); ?><br/>
				Position:<?php echo $current_page["position"]; ?><br/>
				Visible: <?php echo $current_page["visible"]?"Yes":"No"; ?><br/>
				Content: <br/>
				<div class="view-content">
					<?php echo htmlentities($current_page["content"]); ?><br/> 
				</div>
				<a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit Page</a>
			<?php } else { ?>
				Please select a subject or page
			<?php } ?>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	
