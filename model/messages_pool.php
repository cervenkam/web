<?php
	class MessagesPool{
		
		private static $inst = null;
		
		public static function instance(){
			if(MessagesPool::$inst === null){
				MessagesPool::$inst = new MessagesPool();
			}
			return MessagesPool::$inst;
		}
		
		private $MESSAGES = array(
			'TEXT_DELETED' => 'Byl smaz&aacute;n text ?',
			'TEXT_EDITED' => 'Upraven p&rcaron;&iacute;sp&ecaron;vek ? od ?',
			'TEXT_EDITED_FILE' => 'Upraven p&rcaron;&iacute;sp&ecaron;vek ? od ? i se souborem PDF',
			'TEXT_CREATED' => 'P&rcaron;id&aacuten nov&yacute; p%rcaron;&iacute;sp&eacutevek ? od ?',
			'NO_PRIVILEGES' => 'Nem&aacute;te dostate&ccaron;n&aacute opr&aacute;vn&ecaron;n&iacute;',
			'PRIVILEGES_CHANGED' => 'Byla v&aacute;m upravena opr&aacute;vn&eacute;n&iacute;'
			'REVIEWER_SET' => 'Byl jste ur&ccaron;en jako recenzent p&rcaron;&iacute;sp&ecaron;vku ?',
			'REVIEWER_UNSET' => 'Byl jste odstran&ecaron;n jako recenzent p&rcaron;&iacute;sp&ecaron;vku ?',
			'TEXT_STATUS' => 'P&rcaron;&iacute;sp&ecaron;vek ? byl ? u&zcaron;ivatelem ?',
			'MARK_DELETED' => 'U&zcaron;ivatel ? zru&scaron;il hodnocen&iacute; u p&rcaron;&iacute;sp&ecaron;vku ?',
			'MARK_ADDED' => 'U&zcaron;ivatel ? ohodnotil p&rcaron;&iacute;sp&ecaron;vek ?',
			'USER_NEW' => 'Nov&yacute; &ccaron;len ?',
			'NOT_PUBLISHED' => 'skryt',
			'PUBLISHED' => 'publikov&aacute;n',
		);

		private function message_array($message,$parameters){
			$output = $message;
			for($param=0; $param<count($parameters); $param++){
				$output = preg_replace('/\?/',$parameters[$param],$output,1);
			}
			return $output;
		}	

		public function message($message){
			$numargs = func_num_args();
			$arr = array();
			for($a = 1; $a < $numargs; $a++){
				$arr[$a-1] = func_get_arg($a);
			}
			return $this->message_array($this->MESSAGES[$message],$arr);
		}
	}
?>
