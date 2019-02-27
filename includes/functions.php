<?php
	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed");
		}
	}
	function mysql_prep($string) {
		global $conn;
		$string	= mysqli_real_escape_string($conn,$string);
		return $string;
	}
	function redirect_to($url) {
		header("Location:".$url);
		exit;
	}
	function find_all_subjects($public=true) {
		global $conn;

		$query = "SELECT * ";
		$query .= "FROM subjects ";
		if ($public) {
			$query .= "WHERE visible=1 ";	
		}
		$query .= "ORDER BY position ASC";
		$subject_set = mysqli_query($conn,$query);
		confirm_query($subject_set);

		return $subject_set;
	}
	function find_pages_for_subject($subject_id,$public = true) {
		global $conn;

		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE subject_id=".$subject_id." ";
		if ($public) {
			$query .= " AND visible=1 ";	
		}
		$query .= "ORDER BY position ASC";
		$page_set = mysqli_query($conn,$query);
		confirm_query($page_set);

		return $page_set;
	}
	function find_selected_page($public = false) {
		global $current_subject;
		global $current_page;

		if (isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"], $public);
			if ($current_subject && $public) {
				$current_page = find_default_page_for_subject($current_subject["id"]);
			} else {
				$current_page = null;
			}
		} else if (isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"], $public);
			$current_subject = null;
		} else {				
			$current_subject = null;
			$current_page = null;
		}
	}
	function navigation($subject_array,$page_array) {
		$subject_set = find_all_subjects(false);
		$output = "";

		while ($subject = mysqli_fetch_assoc($subject_set)) {
			$output .= "<ul class=\"subjects\">";
			$output .= "<li ";
				if ($subject_array["id"] && $subject["id"] === $subject_array["id"]) {
					$output .= "class=\"selected\" ";
				}
			$output .= ">";
			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject["id"]);
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"])."<br/>"; 
			$output .= "</a><ul class=\"pages\">";
			
			$page_set = find_pages_for_subject($subject["id"],false);
			while ($page = mysqli_fetch_assoc($page_set)) {
				$output .= "<li ";
				if ($page_array["id"] && $page["id"] === $page_array["id"]) {
					$output .= "class=\"selected\" ";
				}
				$output .= ">";
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page["id"]);
				$output .= "\">";
				$output .= htmlentities($page["menu_name"]); 
				$output .= "</a></li>";					
			}
			mysqli_free_result($page_set);
			$output .= "</ul></li></ul>";
		}
		return $output;
		mysqli_free_result($subject_set);
	}
	function public_navigation($subject_array,$page_array) {
		$subject_set = find_all_subjects();
		$output = "";

		while ($subject = mysqli_fetch_assoc($subject_set)) {
			$output .= "<ul class=\"subjects\">";
			$output .= "<li ";   //subject_array is selected subject, subject["id"] is id of subject of current iteration
				if ($subject_array["id"] && $subject["id"] === $subject_array["id"]) { 
					$output .= "class=\"selected\" ";
				}
			$output .= ">";
			$output .= "<a href=\"index.php?subject=";
			$output .= urlencode($subject["id"]);
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"])."<br/>"; 
			$output .= "</a><ul class=\"pages\">";
			
			if ($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject["id"]) {
				$page_set = find_pages_for_subject($subject["id"]);
				while ($page = mysqli_fetch_assoc($page_set)) {
					$output .= "<li ";
					if ($page_array["id"] && $page["id"] === $page_array["id"]) {
						$output .= "class=\"selected\" ";
					}
					$output .= ">";
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page["id"]);
					$output .= "\">";
					$output .= htmlentities($page["menu_name"]); 
					$output .= "</a></li>";					
				}
				mysqli_free_result($page_set);
			}
			$output .= "</ul></li></ul>";
		}
		return $output;
		mysqli_free_result($subject_set);
	}
	function find_subject_by_id($subject_id, $public=true) {
		global $conn;

		$subject_id = mysqli_real_escape_string($conn,$subject_id);

		$query = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id={$subject_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";	
		}
		$query .= "ORDER BY position ASC ";
		$query .= "LIMIT 1";
		$result = mysqli_query($conn,$query);
		confirm_query($result);
		if ($subject=mysqli_fetch_assoc($result)) {
			return $subject;
		} else {
			return null;
		}
	}
	function find_page_by_id($page_id, $public=true) {
		global $conn;

		$page_id = mysqli_real_escape_string($conn,$page_id);

		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id={$page_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";	
		}
		$query .= "ORDER BY position ASC ";
		$query .= "LIMIT 1";
		$result = mysqli_query($conn,$query);
		confirm_query($result);
		if ($page=mysqli_fetch_assoc($result)) {			
			return $page;
		} else {
			return null;
		}
	}
	function find_default_page_for_subject($subject_id) {
		$page_set = find_pages_for_subject($subject_id);
		if ($first_page=mysqli_fetch_assoc($page_set)) {			
			return $first_page;
		} else {
			return null;
		}
	}
	function find_all_admins() {
		global $conn;

		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "ORDER BY username ASC";
		$admins = mysqli_query($conn,$query);
		confirm_query($admins);

		return $admins;
	}
	function find_admin_by_id($admin_id) {
		global $conn;

		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE id = {$admin_id} ";
		$query .= "LIMIT 1";
		$admin = mysqli_query($conn,$query);

		confirm_query($admin);
		$admin = mysqli_fetch_assoc($admin);
		return $admin;		
	}
	function password_encrypt($password) {
		$format = "$2y$10$";
		$salt_length = "22";
		$salt = generate_salt($salt_length);

		$format_and_salt = $format.$salt;

		$hash = crypt($password,$format_and_salt);

		return $hash;
	}
	function generate_salt($salt_length) {
		$unique_random_string = md5(uniqid(mt_rand(),true));

		$base64_string = base64_encode($unique_random_string);

		$modified_base64_string = str_replace("+",".",$base64_string);

		$salt = substr($modified_base64_string,0,$salt_length);

		return $salt;
	}
	function password_check($password,$existing_hash) {
		$hash = crypt($password,$existing_hash);
		if ($hash == $existing_hash) {
			return true;
		} else {
			return false;
		}
	}
	function attempt_login($username,$password) {
		$admin = find_admin_by_username($username);

		if ($admin) {
			$checked = password_check($password,$admin["hashed_password"]);
			if ($checked) {
				return $admin;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	function find_admin_by_username($username) {
		global $conn;

		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE username = '{$username}' ";
		$query .= "LIMIT 1";
		$admin = mysqli_query($conn,$query);

		confirm_query($admin);
		if ($admin = mysqli_fetch_assoc($admin)) {
			return $admin;
		} else {
			return null;
		}
	}
	function logged_in() {
		return isset($_SESSION["admin_id"]);
	}
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("login.php");
		} 
	}
?>