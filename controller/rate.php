<?php
	$privilege = 4;
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
		$ret = $pool->query('NOT_RATE',$_SESSION['user_id']['ID'],$_GET['text_id'],$_GET['type_id']);
		broadcast(MessagesPool::instance()->message('MARK_DELETED',get_full_name(),get_text_name($_GET['text_id'])));
	}else{
		if($_POST['rate'] >= 1 && $_POST['rate'] <= 5){
			$ret = $pool->query('RATE',$_SESSION['user_id']['ID'],$_POST['text_id'],$_POST['rate'],$_POST['type_id']);
			broadcast(MessagesPool::instance()->message('MARK_ADDED',get_full_name(),get_text_name($_POST['text_id'])));
		}
	}
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_".$privilege);
		exit();
	}
?>
