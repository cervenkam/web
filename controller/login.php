<?php
	require_once('../model/database_pool.php');
	require_once('../model/messages_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('GET_USER_ID',$_POST['name'],hash('ripemd160',$_POST['password']));
	if(!empty($ret)){
		if(!can_he_do_it(6,$ret[0][0])){
			echo MessagesPool::instance()->message('USER_BLOCKED');
		}else{
			$_SESSION['user_id']=$ret[0];
			$_SESSION['user_name']=$_POST['name'];
		}
	}else{
		echo MessagesPool::instance()->message('USER_BAD');
	}
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../index");
		exit();
	}
?>
