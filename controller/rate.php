<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	if(!logged_in()){
		header("Location: ../privilege_4"); //TODO
	}
	$pool = DatabasePool::instance();
	if(isset($_GET['delete'])){
		$ret = $pool->query('NOT_RATE',$_SESSION['user_id']['ID'],$_GET['text_id'],$_GET['type_id']);
	}else{
		$ret = $pool->query('RATE',$_SESSION['user_id']['ID'],$_POST['text_id'],$_POST['rate'],$_POST['type_id']);
	}
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_4");
		exit();
	}
?>
