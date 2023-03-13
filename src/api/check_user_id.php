<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
if(isset($_GET["uid"])){
    $uid = $_GET["uid"];
}
require_once('config.php');
require_once('functions.php');

mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");

$dbh = connectDb();
$dbh->query("set names utf8");  // この記述が必要

$sql_2 = "SELECT exists (select * from user_table where uid = '$uid')";
$sth = $dbh->prepare($sql_2);
$sth->execute();

while($row = $sth->fetch(PDO::FETCH_ASSOC)){
    foreach ($row as $object) {
        $status = $object;
    }
}
echo json_encode($status);
header('Content-type: application/json');