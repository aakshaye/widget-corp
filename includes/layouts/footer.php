	<div id="footer">
		Copyright <?php echo date("Y"); ?> &copy;, Widget Corp
	</div>
</body>
</html>
<?php
	if (isset($conn)) {
		mysqli_close($conn);	
	}
?>