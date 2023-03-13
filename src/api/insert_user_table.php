<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");
$mysqli = new mysqli ("localhost", "nakamura-lab", "n1k2m3r4fms");
$mysqli->set_charset('utf8');  // この記述が必要
$mysqli->select_db("m1_hackathon_2022");
if(isset($_GET["uid"]) and isset($_GET["displayName"]) and isset($_GET["photoURL"]) ){
    $uid = $_GET["uid"];
    $displayName = $_GET["displayName"];
    $photoURL = $_GET["photoURL"];
    //挿入部分
    $sql = "INSERT INTO user_table (uid, displayName, photoURL) VALUES('$uid', '$displayName', '$photoURL')";
    $inserts = $mysqli->query($sql);
}
$mysqli->close();
?>