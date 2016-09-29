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

	function get_all_rating_types(){
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_RATING_TYPES');
		return $list;
	}

	function get_all_texts(){
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_TEXTS');
		return $list;
	}

	function get_texts_to_rate(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_TEXTS_TO_RATE');
		return $list;
	}
	
	function my_var_dump($x){
		echo '<pre>';
		var_dump($x);
		echo '</pre>';
	}
?>
