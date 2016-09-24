<?php
	session_start();
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	header("Location:../index");
	exit();
?>
