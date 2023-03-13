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
if(isset($_GET["tipster_id"]) and isset($_GET["guess_id"]) and isset($_GET["praise_id"])){
    $tipster_id = $_GET["tipster_id"];
    $guess_id = $_GET["guess_id"];
    $praise_id = $_GET["praise_id"];
    //挿入部分
    $sql = "INSERT INTO guess_table (tipster_id, guess_id, praise_id) VALUES('$tipster_id', '$guess_id', '$praise_id')";
    $inserts = $mysqli->query($sql);
}
$mysqli->close();
?>