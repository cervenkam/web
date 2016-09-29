<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	my_var_dump($_FILES);
	my_var_dump($_POST);
	$info = pathinfo($_FILES['file']['name']);
	if($info['extension']=='pdf'){
		$ret = $pool->query('ADD_TEXT',$_POST['name'],$_POST['owners'],$_POST['abstract'],$_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'],'../texts/'.$_FILES['file']['name']);
		echo "OK";
	}
	DatabasePool::kill();
	exit();
	if(!part_only()){
		header("Location:../privilege_5");
		exit();
	}
?>
