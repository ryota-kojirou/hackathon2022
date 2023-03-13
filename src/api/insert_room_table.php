<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
mb_language("uni");
mb_internal_encoding("utf-8"); //内部文字コードを変更
mb_http_input("auto");
mb_http_output("utf-8");
$mysqli = new mysqli ("localhost", "nakamura-lab", "n1k2m3r4fms");
$mysqli->select_db("m1_hackathon_2022");
if(isset($_GET["room_id"]) and isset($_GET["host_id"]) and isset($_GET["user_id"]) and isset($_GET["status"])){
    $room_id = $_GET["room_id"];
    $host_id = $_GET["host_id"];
    $user_id = $_GET["user_id"];
    $status = $_GET["status"];
    //挿入部分
    $sql = "INSERT INTO room_table (room_id, host_id, user_id, status) VALUES('$room_id', '$host_id', '$user_id', '$status')";
    $inserts = $mysqli->query($sql);
}
$mysqli->close();
?>