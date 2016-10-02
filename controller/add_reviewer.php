<?php
	$privilege = 3;
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	if(!can_i_do_it($privilege)){
		echo "Nem&aacute;te dostate&ccaron;n&aacute opr&aacute;vn&ecaron;n&iacute;"
		header("Location: ../index");
		exit();
	}
	$pool = DatabasePool::instance();
	$ret = $pool->query('REMOVE_REVIEWER',$_POST['user_id'],$_POST['text_id']);
	$ret = $pool->query('REMOVE_REVIEWERS_RATES',$_POST['user_id'],$_POST['text_id']);
	$ret = $pool->query('ADD_REVIEWER',$_POST['user_id'],$_POST['text_id']);
	unicast($_POST['user_id'],"Byl jste ur&ccaron;en jako recenzent p&rcaron;&iacute;sp&ecaron;vku: ".get_text_name($_POST['text_id']));
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_"+$privilege);
		exit();
	}
?>
