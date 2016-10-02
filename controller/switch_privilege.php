<?php
	$privilege = 1;
	$response = array(
		'<img class="privilege" src="view/images/ne.png"  alt="ne"  />',
		'<img class="privilege" src="view/images/ano.png" alt="ano" />'
	);
	require_once 'functions.php';
	require_once('../model/database_pool.php');
	session_start();
	if(!can_i_do_it($privilege)){
		echo "Nem&aacute;te dostate&ccaron;n&aacute opr&aacute;vn&ecaron;n&iacute;";
		header("Location: ../index");
		exit();
	}
	$pool = DatabasePool::instance();
	$priv = get_all_privileges();
	$curr = $priv[$_GET['user']][$_GET['privilege']]['value'];
	if(!logged_in() || !$priv[$_SESSION['user_id'][0]][1] || $_GET['user']==$_SESSION['user_id'][0]){
		echo $response[$curr];
	}else{
		$curr = 1-$curr;
		$priv = $pool->query('GET_PRIVILEGE',$_GET['user'])[0][0];
		$priv[$_GET['privilege']-1] = $curr;
		$pool->query('SET_PRIVILEGE',$priv,$_GET['user']);
		unicast($_GET['user'],"Byla v&aacute;m upravena opr&aacute;vn&eacute;n&iacute;");
		echo $response[$curr];
	}
	if(!part_only()){
		header("Location:../privilege_".$privilege);
	}
?>
