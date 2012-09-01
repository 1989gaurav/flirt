<?php

require_once( 'Db.php' );

class Chat {
	const
		INVALID_DATA=1,
		CHAT_ADD_SUCCESS=2,
		READ_FROM_DB_SUCCESS=3,
		DBERROR=100;
		
	public function addToDb($sender, $receiver, $message, Db $conn) {
		$time = time()+34200;
		$query = sprintf("insert into chat (sender, receiver, message, time, isread) values('%s', '%s', '%s', $time, false)",
				$sender,
				$receiver,
				$message
			);
		$result = $conn->query( $query );
		if( $result==false ) return self::DBERROR;

		return self::CHAT_ADD_SUCCESS;
	}
	
	public function readFromDb($sender, $receiver, $domain, Db $conn) {
		$time = time()+34200;
$last = "";
if($domain=="unread") $last = "and isread=false";
		$query = "select sender, receiver, message, time from chat where ( sender='$sender' and receiver='$receiver' ) or ( sender='$receiver' and receiver='$sender' ) $last order by time asc;";
		$result = $conn->query( $query );
		if( $result==false ) return self::DBERROR;
		$ifread = $this->markRead($sender,$time,$conn);
		if( $ifread==false ) return self::DBERROR;
		return $conn->getAllRows($result);
	}

	public function getUserUpdates($receiver, Db $conn) {
		$time = time()+34200;
		$query = "select sender, message, time from chat where receiver='$receiver' and isread=false order by time asc;";
		$result = $conn->query( $query );
		if( $result==false ) return self::DBERROR;
		$ifread = $this->markReadByReciever($receiver,$time,$conn);
		if( $ifread==false ) return self::DBERROR;
		return $conn->getAllRows($result);
	}
	
	private function markRead($sender, $time, Db $conn) {
		$query = "update chat set isread=true where sender='$sender' and time<$time;";
		$result = $conn->query( $query );
		if( $result==false ) return false;
		return true;
	}

        private function markReadByReciever($receiver, $time, Db $conn) {
		$query = "update chat set isread=true where receiver='$receiver';";
		$result = $conn->query( $query );
		if( $result==false ) return false;
		return true;
	}
}


?>
	