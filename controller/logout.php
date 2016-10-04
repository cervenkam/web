<?php
	//can do everyone
	require_once('../model/messages_pool.php');
	require_once('functions.php');
	session_start();
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	if(!part_only()){
		header("Location:../index");
		exit();
	}else{
		echo MessagesPool::instance()->message('USER_LOGOUT');
	}
?>
