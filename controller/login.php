<?php
	require_once('../model/database_pool.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('GET_USER_ID',$_POST['name'],$_POST['password']);
	DatabasePool::kill();
	if($ret){
		$_SESSION['user_id']=$ret[0];
		$_SESSION['user_name']=$_POST['name'];
	}
	header("Location:../index");
	exit();
?>
