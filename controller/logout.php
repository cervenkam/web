<?php
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	header("Location:logout/");
?>
