<?php
	//can call everyone logged in
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	if(!logged_in()){
		exit();
	}
	$pool = DatabasePool::instance();
	$ret = $pool->query('GET_NEWS',$_SESSION['user_id'][0]);
	DatabasePool::kill();
	if(!empty($ret)){
		for($line=0; $line<count($ret); $line++){
			echo $ret[$line]['message'].'<br />';
		}
		$pool->query('REMOVE_NEWS',$_SESSION['user_id'][0]);
	}
	if(!part_only()){
		//This script should be called with AJAX
		exit();
	}
?>
