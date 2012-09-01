<?php
function fail($msg) {
    $msg = addslashes($msg);
    echo "{success: false, msg: '$msg'}";
    exit;
}

if( !isset($_GET['receiver']) )
    fail('Incomplete form');

$receiver     = $_GET['receiver'];
require_once('lib/Db.php');
require_once('lib/Chat.php');
$conn = new Db();
$chat = new Chat();
$result = $chat->getUserUpdates($receiver, $conn);
if($result===Chat::INVALID_DATA) {
    fail('An unexpected error has occurred.');
} else if($result===Chat::DBERROR) {
    fail('A database error has occurred.');
}
$resp = array();
foreach($result as $res) {
    $message = $res[1];
    $t = explode(".", $res[2]);
    $time = date('i:s a', intval($t[0]));
    $sender = $res[0];
    array_push($resp, array('time'=>"$time", 'msg'=>"$message" , 'sender'=>"$sender"));
}

$ress = array('success'=>true, 'messages'=>$resp);
echo json_encode($ress);

?> 	