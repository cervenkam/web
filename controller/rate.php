<?php
	$privilege = 4;
	require_once('../model/database_pool.php');
	require_once('functions.php');
	session_start();
	if(!can_i_do_it($privilege)){
		echo "Nem&aacute;te dostate&ccaron;n&aacute opr&aacute;vn&ecaron;n&iacute;"
		header("Location: ../index");
		exit();
	}
	$pool = DatabasePool::instance();
	if(isset($_GET['delete'])){
		$ret = $pool->query('NOT_RATE',$_SESSION['user_id']['ID'],$_GET['text_id'],$_GET['type_id']);
		broadcast("U&zcaron;ivatel ".get_full_name()." zru&scaron;il hodnocen&iacute; u p&rcaron;&iacute;sp&ecaron;vku &quot;".get_text_name($_GET['text_id'])."&quot;");
	}else{
		if($_POST['rate'] >= 1 && $_POST['rate'] <= 5){
			$ret = $pool->query('RATE',$_SESSION['user_id']['ID'],$_POST['text_id'],$_POST['rate'],$_POST['type_id']);
			broadcast("U&zcaron;ivatel ".get_full_name()." ohodnotil p&rcaron;&iacute;sp&ecaron;vek &quot;".get_text_name($_POST['text_id'])."&quot;");
		}
	}
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_".$privilege);
		exit();
	}
?>
