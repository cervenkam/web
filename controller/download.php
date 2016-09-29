<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('GET_PDF',$_GET['id']);
	DatabasePool::kill();
	if(!empty($ret)){
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="text_'.$_GET['id'].'.pdf"');
		header('Content-length: '.$ret[0]['size']);
		echo $ret[0]['file'];
		exit();
	}else{
		echo "DOWNLOAD FAIL<br />";
	}
	if(!part_only()){
		header("Location:../index");
		exit();
	}
?>
