<?php
	$privilege = 2
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
		$type = 'NOT_PUBLISH';
		$text = 'odstran&ecaron;n';
	}else{
		$type = 'PUBLISH';
		$text = 'publikov&aacuten';
	}
	broadcast("P&rcaron;&iacute;sp&ecaron;vek &quot".get_text_name($_GET['id'])."&quot byl ".$text." u&zcaron;ivatelem ".get_full_name());
	$ret = $pool->query($type,$_GET['id']);
	DatabasePool::kill();
	if(!part_only()){
		header("Location:../privilege_".$privilege);
		exit();
	}
?>
