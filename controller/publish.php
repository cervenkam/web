<?php
	$privilege = 2;
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
	if(isset($_GET['delete'])){
		$type = 'NOT_PUBLISH';
	}else{
		$type = 'PUBLISH';
	}
	broadcast(MessagesPool::instance()->message('TEXT_STATUS',get_text_name($_GET['id']),MessagesPool::instance()->message($text),get_full_name()));
	$ret = $pool->query($type,$_GET['id']);
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_".$privilege);
		exit();
	}
?>
