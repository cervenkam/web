<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	if(isset($_GET['delete'])){
		$type = 'NOT_PUBLISH';
	}else{
		$type = 'PUBLISH';
	}
	$ret = $pool->query($type,$_GET['id']);
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_2");
		exit();
	}
?>
