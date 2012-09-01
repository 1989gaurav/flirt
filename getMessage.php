<?php
function fail($msg) {
	$msg = addslashes($msg);
	echo "{success: false, msg: '$msg'}";
	exit;
}

if( !isset($_GET['sender']) || !isset($_GET['receiver']))
	fail('Incomplete form');

$domain = "unread";
if(isset($_GET['all']))
$domain = "all";

$sender		= $_GET['sender'];
$receiver 	= $_GET['receiver'];
require_once('lib/Db.php');
require_once('lib/Chat.php');
$conn = new Db();
$chat = new Chat();
$result = $chat->readFromDb($sender, $receiver, $domain, $conn);
if($result===Chat::INVALID_DATA) {
	fail('An unexpected error has occurred.');
} else if($result===Chat::DBERROR) {
	fail('A database error has occurred.');
}
$resp = array();
foreach($result as $res) {
	$message = $res[2];
	$t = explode(".", $res[3]);
	$time = date('h:i:s a', intval($t[0]));
$sent = $res[0]==$sender?1:0;
	array_push($resp, array('time'=>"$time", 'msg'=>"$message", 'sent'=>$sent));
}
$ress = array('success'=>true, 'messages'=>$resp);

echo json_encode($ress);

?>