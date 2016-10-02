<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$info = pathinfo($_FILES['file']['name']);
	if($info['extension']=='pdf'){
		$ret = $pool->query('ADD_TEXT',$_POST['name'],$_POST['owners'],$_POST['abstract'],$_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'],'../texts/'.$_FILES['file']['name']);
		broadcast("P&rcaron;id&aacuten nov&yacute; p%rcaron;&iacute;sp&eacutevek ".$_POST['name']." od ".$_POST['owners']);
		echo "OK";
	}
	DatabasePool::kill();
	header("Location:../privilege_5");
?>
