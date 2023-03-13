<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["to_id"])){
    $to_id = $_GET["to_id"];
    //挿入部分
}
require_once('config.php');
require_once('functions.php');

mb_language("uni");
mb_internal_encoding("utf-8");
mb_http_input("auto");
mb_http_output("utf-8");

$dbh = connectDb();

$sth = $dbh->prepare("SELECT * FROM home_table Where to_id='$to_id'");
$sth->execute();

$userData = array();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userData[]=array(
    'from_id'=>$row['displayName'],
    'words_of_praise'=>$row['words_of_praise'],
    'room_id'=>$row['room_id'],
    );
}

echo $user_Data;
header('Content-type: application/json');
echo json_encode($userData);