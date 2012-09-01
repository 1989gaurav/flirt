<?php
function fail($msg) {
	$msg = addslashes($msg);
	echo "{success: false, msg: '$msg'}";
	exit;
}

if( !isset($_GET['sender']) || !isset($_GET['receiver']) || !isset($_GET['message']))
	fail('Incomplete form');

$sender		= $_GET['sender'];
$receiver 	= $_GET['receiver'];
$message 	= $_GET['message'];

$order   = array("\n", "<br>", "\n\n", "<br><br>");
$replace = '';

$message = str_replace($order, $replace, $message);

require_once('lib/Db.php');
require_once('lib/Chat.php');
$conn = new Db();
$chat = new Chat();

$result = $chat->addToDb($sender, $receiver, $message, $conn);

switch($result) {
case Chat::CHAT_ADD_SUCCESS:
	break;
case Chat::INVALID_DATA:
	fail('An unexpected error has occurred.');
case Chat::DBERROR:
	fail('A database error has occurred.');
}

echo "{success:true, msg:'Chat has been added.'}";

?>
