<?php
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	$pool = DatabasePool::instance();
	$ret = $pool->query('REMOVE_REVIEWER',$_GET['user_id'],$_GET['text_id']);
	$ret = $pool->query('REMOVE_REVIEWERS_RATES',$_GET['user_id'],$_GET['text_id']);
	unicast($_GET['user_id'],"Byl jste odstran&ecaron;n jako recenzent p&rcaron;&iacute;sp&ecaron;vku &quot;".get_text_name($_GET['text_id'])."&quot");
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_3");
		exit();
	}
?>

