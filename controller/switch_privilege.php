<?php
	$response = array(
		'<img class="privilege" src="view/images/ne.png"  alt="ne"  />',
		'<img class="privilege" src="view/images/ano.png" alt="ano" />'
	);
	require_once 'functions.php';
	require_once('../model/database_pool.php');
	session_start();
	$pool = DatabasePool::instance();
	$priv = get_all_privileges();
	//my_var_dump($priv);
	$curr = $priv[$_GET['user']][$_GET['privilege']]['value'];
	if(!logged_in() || !$priv[$_SESSION['user_name']][1]){
		echo $response[$curr];
	}else{
		$curr = 1-$curr;
		$priv = $pool->query('GET_PRIVILEGE',$_GET['user'])[0][0];
		$priv[$_GET['privilege']-1] = $curr;
		$pool->query('SET_PRIVILEGE',$priv,$_GET['user']);
		echo $response[$curr];
	}
	if(!part_only()){
		header("Location:../privilege_1");
	}
?>
