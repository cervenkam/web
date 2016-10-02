<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	if(can_i_do_it(array(2,3,4,5))){
		$ret = $pool->query('GET_PDF',$_GET['id']);
	}else{
		$ret = $pool->query('GET_PUBLISHED_PDF',$_GET['id']);
	}
	DatabasePool::kill();
	if(!empty($ret)){
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="text_'.$_GET['id'].'.pdf"');
		header('Content-length: '.$ret[0]['size']);
		header('Location: ../texts/'.$ret[0]['filename']);
		exit();
	}else{
		echo "DOWNLOAD FAIL<br />";
	}
	if(!part_only()){
		header("Location:../index");
		exit();
	}
?>
