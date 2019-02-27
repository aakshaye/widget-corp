<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php $admins = find_all_admins() ?>
<div id="main">
	<div id="navigation">
		<br/>
		<a href="admin.php">&laquo; Main Menu</a>		
	</div>
	<div id="page">
		<?php echo message(); ?>
		<h2>Manage Admins</h2>
		<table>
			<tr>
				<th>Username</th>
				<th colspan="2">Actions</th>
			</tr>
			<?php 
				while ($admin = mysqli_fetch_assoc($admins)) {
					echo "<tr>";
					echo "<td>";
					echo htmlentities($admin["username"]);
					echo "</td>";
					echo "<td><a href=\"edit_admin.php?id=".urlencode($admin["id"])."\">Edit</a></td>";
					echo "<td><a href=\"delete_admin.php?id=".urlencode($admin["id"])."\" onclick=\"return confirm('Are you sure?');\">Delete</a></td>";
					echo "<tr>";
				}
			?>
		</table>
		<br/>
		<a href="new_admin.php">Add new admin</a><br/>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>	