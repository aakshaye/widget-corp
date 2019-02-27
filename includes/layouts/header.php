<!DOCTYPE html>
<html lang="en">
<?php
	 if (!isset($layout_context)) {
	 	$layout_context = "public";
	}
?>
<head>
	<title>Widget Corp <?php if ($layout_context=="admin") echo "Admin";?></title>
	<link rel="stylesheet" href="css/admin.css" type="text/css"/>
</head>
<body>
	<div id="header">
		<h1>Widget Corp <?php if ($layout_context=="admin") echo "Admin";?></h1>
	</div>