<?php
	//can do everyone
	require_once('../model/database_pool.php');
	require_once('../model/messages_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$res = $pool->query('GET_ID',$_POST['name']);
	if(!empty($res)){
		echo "USER ALREADY EXISTS";
		exit();
	}
	$ret = $pool->query(
		'ADD_REGULAR_USER',
		$_POST['name'],
		$_POST['password'],
		$_POST['fullname'],
		$_POST['organization'],
		$_POST['email']	
	);
	$_SESSION['user_name']=$_POST['name'];
	$res = $pool->query('GET_ID',$_POST['name']);
	DatabasePool::kill();
	$_SESSION['user_id']=$res[0];
	broadcast(MessagesPool::instance()->message('USER_NEW',$_POST['fullname']));
?>
