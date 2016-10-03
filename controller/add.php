<?php
	$privilege = 5;
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	if(!can_i_do_it($privilege)){
		echo "Nem&aacute;te dostate&ccaron;n&aacute opr&aacute;vn&ecaron;n&iacute;";
		header("Location: ../index");
		exit();
	}
	$pool = DatabasePool::instance();
	if(isset($_GET['delete'])){
		broadcast("Byl smaz&aacute;n text &quot;".get_text_name($_GET['id'])."&quot;");
		$pool->query('REMOVE_TEXT',$_GET['id']);
		DatabasePool::kill();
		if(!part_only()){
			header("Location:../privilege_"+$privilege);
			exit();
		}
	}else{
		$info = pathinfo($_FILES['file']['name']);
		if(isset($info['extension']) && $info['extension'] =='pdf'){
			$ret = $pool->query('ADD_TEXT',$_POST['name'],$_POST['owners'],$_POST['abstract'],$_FILES['file']['name'],$_SESSION['user_id'][0]);
			move_uploaded_file($_FILES['file']['tmp_name'],'../texts/'.$_FILES['file']['name']);
			broadcast("P&rcaron;id&aacuten nov&yacute; p%rcaron;&iacute;sp&eacutevek ".$_POST['name']." od ".$_POST['owners']);
		}
		DatabasePool::kill();
		header("Location:../privilege_".$privilege);
		exit();
	}
?>
