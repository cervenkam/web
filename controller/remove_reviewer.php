<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('REMOVE_REVIEWER',$_GET['user_id'],$_GET['text_id']);
	$ret = $pool->query('REMOVE_REVIEWERS_RATES',$_GET['user_id'],$_GET['text_id']);
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_3");
		exit();
	}
?>

