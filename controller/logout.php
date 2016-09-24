<?php
	session_start();
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	if(!isset($_POST['part_only']) || $_POST['part_only'] != 'yes'){
		header("Location:../index");
		exit();
	}else{
		echo "LOGOUT OK";
	}
?>
