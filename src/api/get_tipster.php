<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["praise_id"])){
    $praise_id = $_GET["praise_id"];
}
require_once('config.php');
require_once('functions.php');

mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");

$dbh = connectDb();
$dbh->query("set names utf8");  // この記述が必要

$sth = $dbh->prepare("SELECT * FROM guess_table Where praise_id='$praise_id'");
$sth->execute();

$userData = array();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    $userData[]=array(
    'tipster_id'=>$row['tipster_id'],
    'guess_id'=>$row['guess_id'],
    );
}
header('Content-type: application/json');
echo json_encode($userData);