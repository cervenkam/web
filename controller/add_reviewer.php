<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('REMOVE_REVIEWER',$_POST['user_id'],$_POST['text_id']);
	$ret = $pool->query('REMOVE_REVIEWERS_RATES',$_POST['user_id'],$_POST['text_id']);
	$ret = $pool->query('ADD_REVIEWER',$_POST['user_id'],$_POST['text_id']);
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_3");
		exit();
	}
?>