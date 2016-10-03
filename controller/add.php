<?php
	$privilege = 5;
	require_once('../model/database_pool.php');
	require_once('../model/messages_pool.php');
	require_once('functions.php');
	session_start();
	if(!can_i_do_it($privilege)){
		echo MessagesPool::instance()->message('NO_PRIVILEGES');
		header("Location: ../index");
		exit();
	}
	$pool = DatabasePool::instance();
	if(isset($_GET['delete'])){
		broadcast(MessagesPool::instance()->message('TEXT_DELETED',get_text_name($_GET['id'])));
		$pool->query('REMOVE_TEXT',$_GET['id']);
		DatabasePool::kill();
		if(!part_only()){
			header("Location:../privilege_"+$privilege);
		}
		exit();
	}else if(isset($_POST['id'])){
		if(isset($_POST['file_checkbox'])){
			$info = pathinfo($_FILES['file']['name']);
			if(isset($info['extension']) && $info['extension'] =='pdf'){
				$ret = $pool->query('UPDATE_TEXT_PDF',$_POST['name'],$_POST['owners'],$_POST['abstract'],$_FILES['file']['name'],$_POST['id']);
				move_uploaded_file($_FILES['file']['tmp_name'],'../texts/'.$_FILES['file']['name']);
				broadcast(MessagesPool::instance()->message('TEXT_EDITED_FILE',$_POST['name'],$_POST['owners']));
			}
		}else{
			$ret = $pool->query('UPDATE_TEXT',$_POST['name'],$_POST['owners'],$_POST['abstract'],$_POST['id']);
			broadcast(MessagesPool::instance()->message('TEXT_EDITED',$_POST['name'],$_POST['owners']));
		}
	}else{
		$info = pathinfo($_FILES['file']['name']);
		if(isset($info['extension']) && $info['extension'] =='pdf'){
			$ret = $pool->query('ADD_TEXT',$_POST['name'],$_POST['owners'],$_POST['abstract'],$_FILES['file']['name'],$_SESSION['user_id'][0]);
			move_uploaded_file($_FILES['file']['tmp_name'],'../texts/'.$_FILES['file']['name']);
			broadcast(MessagesPool::instance()->message('TEXT_CREATED',$_POST['name'],$_POST['owners']));
		}
	}
	DatabasePool::kill();
	header("Location:../privilege_".$privilege);
	exit();
?>
