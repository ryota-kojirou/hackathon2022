<?php
header('Content-Type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
$mysqli = new mysqli ("localhost", "nakamura-lab", "n1k2m3r4fms");
$mysqli->set_charset('utf8'); 
$mysqli->select_db("m1_hackathon_2022");
if(isset($_GET["from_id"]) and isset($_GET["to_id"]) and isset($_GET["words_of_praise"]) and isset($_GET["room_id"]) ){
    $from_id = $_GET["from_id"];
    $to_id = $_GET["to_id"];
    $words_of_praise = $_GET["words_of_praise"];
    $room_id = $_GET["room_id"];
    //挿入部分
    $sql = "INSERT INTO home_table (from_id, to_id, words_of_praise, room_id) VALUES('$from_id', '$to_id', '$words_of_praise', '$room_id')";
    $inserts = $mysqli->query($sql);
}
$mysqli->close();
?>