<?php
	function logged_in(){
		return isset($_SESSION['user_name']);
	}
	
	function part_only(){
		return isset($_POST['part_only']) && $_POST['part_only'] == 'yes';
	}
	function get_all_privileges(){
		if(logged_in()){
			$pool = DatabasePool::instance();
			$list = $pool->query('LIST_OF_PRIVILEGES');
			$all = $pool->query('GET_ALL_PRIVILEGES');
			DatabasePool::kill();
		}else{
			return array();
		}
		$arr = array();
		for($usr=0; $usr<count($all); $usr++){
			for($a=0; $a<count($list); $a++){
				$arr[$all[$usr]['username']][$list[$a]['ID']] = array(
					'name' => $list[$a]['type'],
					'value' => (int)$all[$usr]['type']{$list[$a]['ID']-1}
				);
			}
		}
		//my_var_dump($arr);
		return $arr;
	}
	
	function my_var_dump($x){
		echo '<pre>';
		var_dump($x);
		echo '</pre>';
	}
?>
