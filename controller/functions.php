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
				$arr[$all[$usr]['ID']][$list[$a]['ID']] = array(
					'name' => $list[$a]['type'],
					'value' => (int)$all[$usr]['type']{$list[$a]['ID']-1}
				);
			}
		}
		//my_var_dump($arr);
		return $arr;
	}

	function get_full_name(){
		return get_full_name_id($_SESSION['user_id'][0]);
	}
	function get_full_name_id($id){
		$pool = DatabasePool::instance();
		$text = $pool->query('GET_FULL_NAME',$id);
		DatabasePool::kill();
		return $text[0]['full_name'];
	}

	function get_text_name($id){
		$pool = DatabasePool::instance();
		$text = $pool->query('GET_TEXT',$id);
		DatabasePool::kill();
		return $text[0]['name'];
	}

	function get_all_users(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_USERS');
		DatabasePool::kill();
		$arr = array();
		for($line=0; $line<count($list); $line++){
			$arr[$list[$line]['ID']] = $list[$line];	
		}
		//my_var_dump($arr);
		return $arr;
	}

	function get_all_rating_types(){
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_RATING_TYPES');
		DatabasePool::kill();
		return $list;
	}

	function get_all_texts(){
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_ALL_TEXTS');
		DatabasePool::kill();
		return $list;
	}

	function get_texts_to_rate(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_TEXTS_TO_RATE');
		DatabasePool::kill();
		$arr = array();
		for($line=0; $line<count($list); $line++){
			$arr[$list[$line]['user_id']][] = $list[$line];
		}
		//my_var_dump($arr);
		return $arr;

	}
	
	function get_my_reviews(){
		if(!logged_in()){
			return array();
		}
		$pool = DatabasePool::instance();
		$list = $pool->query('GET_TEXTS_TO_REVIEW');
		DatabasePool::kill();
		$arr = array();
		for($line=0; $line<count($list); $line++){
			$arr[$list[$line]['text_id']][] = $list[$line];
			//unset($list[$line]['text_id']);
		}
		return $arr;
	}
	
	function my_var_dump($x){
		echo '<pre>';
		var_dump($x);
		echo '</pre>';
	}

	function unicast($user_id,$message){
		unicast_id($user_id,$message,$_SESSION['user_id'][0]);
	}

	function unicast_id($user_id,$message,$my_id){
		if($user_id != $my_id){
			$pool = DatabasePool::instance();
			$pool->query('ADD_NEWS',$user_id,$message);
			DatabasePool::kill();
		}
	}

	function broadcast($message){
		broadcast_id($message,$_SESSION['user_id'][0]);
	}

	function broadcast_id($message,$my_id){	
		//sending messages
		$users = get_all_users();
		$pool = DatabasePool::instance();
		foreach($users as $key => $value){
			if($key != $my_id){
				$pool->query('ADD_NEWS',$key,$message);
			}
		}
		DatabasePool::kill();
	}
?>
