<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query(
		'ADD_REGULAR_USER',
		$_POST['name'],
		$_POST['password'],
		$_POST['fullname'],
		$_POST['organization'],
		$_POST['email']	
	);
	broadcast("Nov&eacute; &ccaron;len: ".$_POST['fullname']);
	DatabasePool::kill();
	$_SESSION['user_name']=$_POST['name'];
	$_SESSION['user_id']=$pool->query('GET_ID',$_POST['name'])[0];
?>
