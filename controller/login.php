<?php
	require_once('../model/database_pool.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('GET_USER_ID',$_POST['name'],$_POST['password']);
	DatabasePool::kill();
	if(!empty($ret)){
		$_SESSION['user_id']=$ret[0];
		$_SESSION['user_name']=$_POST['name'];
		echo "LOGIN OK<br />";
	}else{
		echo "LOGIN FAIL<br />";
	}
	var_dump($ret);
	if(!isset($_POST['part_only']) || $_POST['part_only'] != 'yes'){
		header("Location:../index");
		exit();
	}
?>
