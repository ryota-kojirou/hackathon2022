<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["room_id"])){
    $room_id = $_GET["room_id"];
    //挿入部分
}
require_once('config.php');
require_once('functions.php');

mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");

$dbh = connectDb();

$sth = $dbh->prepare("SELECT DISTINCT room_id, host_id FROM room_table Where room_id='$room_id'");
$sth->execute();

$userData = array();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userData[]=array(
    'host_id'=>$row['host_id'],
    );
}
header('Content-type: application/json');
echo json_encode($userData);