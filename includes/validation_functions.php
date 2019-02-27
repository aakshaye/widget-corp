<?php
	$errors = array();
	function fieldname_as_text($fieldname) {
		$fieldname = str_replace("_"," ",$fieldname);
		$fieldname = ucfirst($fieldname);
		return $fieldname;
	}
	function has_presence($value) {
		return isset($value) && $value!=="";
	}
	function validate_presences($required_fields) {
		global $errors;
		foreach ($required_fields as $field) {
			$value = trim($_POST[$field]);
			if (!has_presence($value)) {
				$value = fieldname_as_text($field);
				$errors[$field] = ucfirst($value)." must be entered";
			}
		}
	}
	function has_correct_length($value) {
		$min = 3;
		$max = 30;
		if (strlen($value) < $min && strlen($value) > $max) {
			return false;			
		} else {
			return true;
		}
	}
	function validate_lengths($fields) {
		global $errors;
		foreach ($fields as $field) {
			$value = trim($_POST[$field]);
			if (!has_correct_length($value)) {
				$value = fieldname_as_text($field);
				$errors[$field] = ucfirst($value)." has incorrect length";
			}			
		}
	}
	function is_inclusion_in($value,$set) {
		return in_array($set,$value);	
	}
	function form_errors($errors = array()) {
		$output = "";
		if (!empty($errors)) {
			$output = "<div class=\"error\">";
			$output .= "Please fix the following errors";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
			}
			$output .= "</ul></div>";
		}
		return $output;		
	}
?>