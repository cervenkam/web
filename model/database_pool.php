<?php
	class DatabasePool{
		private static $inst = null;

		public static function init($dns,$username,$password){
			DatabasePool::$inst = new DatabasePool($dns,$username,$password);
		}
		public static function instance(){
			if(DatabasePool::$inst === null){
				echo 'Database not initialized, call "init" function first';
			}
			return DatabasePool::$inst;
		}

		private $QUERIES = array(
			'ADD_REGULAR_USER' => 'INSERT INTO users VALUES(NULL, 1, ?, ?, ?, ?, ?)',
			'LIST_OF_MY_TEXTS' => 'SELECT * FROM texts WHERE user_id=?',
			'GET_USER' => 'SELECT ID FROM users WHERE name=? AND password=?'
		);

		public $db;

		private function __construct($dns,$username,$password){
			$this->db = new PDO($dns,$username,$password);
		}

		private function query_array($query,$data){
			try{
				$stmt = $this->db->prepare($query);
				for($a=0; $a<count($data); $a++){
					$stmt->bindParam($a,$data[$a]);
				}
				$stmt->execute($data);
				return $stmt->fetchAll();
			}catch(PDOException $e){
				return false;
			}	
		}

		public function query($query){
			$numargs = func_num_args();
			$arr = array();
			for($a = 1; $a < $numargs; $a++){
				$arr[$a-1] = func_get_arg($a);
			}
			return query_array($query,$data);
		}
	}
?>
