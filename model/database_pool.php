<?php
	class DatabasePool{
		private static $inst = null;

		public static function init($dns,$username,$password){
			DatabasePool::$inst = new DatabasePool($dns,$username,$password);
			DatabasePool::$inst->query('SET_UTF8');
		}
		public static function instance(){
			if(DatabasePool::$inst === null){
				DatabasePool::init('mysql:host=localhost;dbname=web','web','password');
			}
			return DatabasePool::$inst;
		}
		public static function kill(){
			DatabasePool::$inst = null;
		}

		private $QUERIES = array(
		//SET
				//no params
			'SET_UTF8' => 'SET NAMES utf8',
		//SELECT
			//no params
			'GET_ALL_TEXTS' =>        'SELECT * FROM texts',
			'GET_ALL_USERS' =>        'SELECT * FROM users',
			'GET_ALL_RATING_TYPES' => 'SELECT * FROM rating_types',
			'LIST_OF_PRIVILEGES' =>   'SELECT * FROM privileges',
			'GET_TEXTS_TO_REVIEW' =>  'SELECT * FROM reviewers',
			'GET_ALL_PRIVILEGES' =>   'SELECT ID,type FROM users',
			'GET_TEXTS_TO_RATE' =>    'SELECT users.ID AS user_id, texts.ID AS text_id, ratings.type AS type, ratings.mark AS mark FROM texts INNER JOIN ratings ON ratings.text_id = texts.ID INNER JOIN users ON ratings.user_id = users.ID',
			//one param
			'LIST_OF_MY_TEXTS' =>     'SELECT * FROM texts WHERE user_id=?',
			'GET_PRIVILEGE' =>        'SELECT type FROM users WHERE username=?',
			'GET_ID' =>               'SELECT ID FROM users WHERE username=?',
			'GET_PDF' =>              'SELECT file, LENGTH(file) AS size FROM texts WHERE texts.ID=?',
			//two params
			'GET_USER_ID' =>          'SELECT ID FROM users WHERE username=? AND password=?',
		//INSERT
			//one param
			'ADD_REVIEWER' =>     'INSERT INTO reviewers VALUES( ?, ?)',
			//four params
			'ADD_TEXT' =>         'INSERT INTO texts VALUES(NULL, ?, ?, ?, ?,NULL)',
			'RATE' =>             'INSERT INTO ratings VALUES( ?, ?, ?, ?)',
			//five params
			'ADD_REGULAR_USER' => 'INSERT INTO users VALUES(NULL, NULL, ?, ?, ?, ?, ?)',
		//UPDATE
			//one param
			'PUBLISH' =>       'UPDATE texts SET published=NOW() WHERE ID=?',
			'NOT_PUBLISH' =>   'UPDATE texts SET published=NULL WHERE ID=?',
			//two params
			'SET_PRIVILEGE' => 'UPDATE users SET type=? WHERE username=?',
		//DELETE
			//one param
			'REMOVE_REVIEWER' => 'DELETE FROM reviewers WHERE user_id=? AND text_id=?',
			'REMOVE_REVIEWERS_RATES' => 'DELETE FROM ratings WHERE user_id=? AND text_id=?',
			//three params
			'NOT_RATE' => 'DELETE FROM ratings WHERE ratings.user_id=? AND ratings.text_id=? AND ratings.type=?'
		);

		public $db;

		private function __construct($dns,$username,$password){
			$this->db = new PDO($dns,$username,$password);
		}

		private function query_array($query,$data){
			try{
				$stmt = $this->db->prepare($query);
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
			return $this->query_array($this->QUERIES[$query],$arr);
		}
	}
?>
