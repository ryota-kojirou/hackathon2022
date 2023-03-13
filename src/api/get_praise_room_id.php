<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["room_id"])){
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

$sth = $dbh->prepare("SELECT * FROM home_table Where room_id='$room_id'");
$sth->execute();

$userData = array();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userData[]=array(
    'praise_id'=>$row['praise_id'],
    'from_id'=>$row['from_id'],
    'to_id'=>$row['to_id'],
    'words_of_praise'=>$row['words_of_praise'],
    );
}
header('Content-type: application/json');
echo json_encode($userData);