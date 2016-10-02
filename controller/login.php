<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('GET_USER_ID',$_POST['name'],$_POST['password']);
	if(!empty($ret)){
		if(!can_he_do_it(6,$ret[0][0])){ //TODO FIXED
			echo "ACCOUNT BLOCKED";
		}else{
			$_SESSION['user_id']=$ret[0];
			$_SESSION['user_name']=$_POST['name'];
			echo "LOGIN OK<br />";
		}
	}else{
		echo "LOGIN FAIL<br />";
	}
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../index");
		exit();
	}
?>
