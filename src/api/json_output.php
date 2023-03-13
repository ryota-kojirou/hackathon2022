<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["host_id"])){
    $host_id = $_GET["host_id"];
    //挿入部分
}
require_once('config.php');
require_once('functions.php');

mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");

$dbh = connectDb();

$sth = $dbh->prepare("SELECT * FROM room_table Where host_id='$host_id' AND auto_id=(SELECT MAX(auto_id) FROM room_table Where host_id='$host_id')");
$sth->execute();

$userData = array();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userData[]=array(
    'status'=>$row['status']
    );
}
if ($userData[0]["status"]=='active'){
    $return = "true";
}else {
    $return = "false";
}

$results = array(
    "status" => "$return"
);

header('Content-type: application/json');
echo json_encode($results);