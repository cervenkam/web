<?php
	$privilege = 3;
	require_once('../model/database_pool.php');
	require_once('../model/messages_pool.php');
	require_once('functions.php');
	session_start();
	if(!can_i_do_it($privilege)){
		send_bad_privileges();
		header("Location: ../index");
		exit();
	}
	$pool = DatabasePool::instance();
	$ret = $pool->query('REMOVE_REVIEWER',$_GET['user_id'],$_GET['text_id']);
	$ret = $pool->query('REMOVE_REVIEWERS_RATES',$_GET['user_id'],$_GET['text_id']);
	unicast($_GET['user_id'],MessagesPool::instance()->message('REVIEWER_UNSET'),get_text_name($_GET['text_id']));
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_".$privilege);
		exit();
	}
?>

