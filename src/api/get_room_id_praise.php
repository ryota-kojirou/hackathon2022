<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["to_id"]) and isset($_GET["room_id"])){
    $to_id = $_GET["to_id"];
    $room_id = $_GET["room_id"];
}
require_once('config.php');
require_once('functions.php');

mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");

$dbh = connectDb();
$dbh->query("set names utf8");  // この記述が必要

$sth = $dbh->prepare("SELECT * FROM home_table,user_table Where home_table.from_id=user_table.uid and room_id='$room_id' and to_id='$to_id'");
$sth->execute();

$userData = array();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userData[]=array(
    'from_id'=>$row['from_id'],
    'words_of_praise'=>$row['words_of_praise'],
    'photoURL'=>$row['photoURL'],
    );
}
header('Content-type: application/json');
echo json_encode($userData);