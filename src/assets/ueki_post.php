<?php
header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
  header('Access-Control-Allow-Methods: GET, GET, PUT');
  header('Content-Type: application/json; charset=utf-8');
  $dsn = 'mysql:host=localhost;dbname=ueki_db;charset=utf8';
  $user = 'nakamura-lab';
  $password = 'n1k2m3r4fms';
  try {
    $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    exit($e->GETMessage());
    }
  $uuid = $_GET['uuid'];
  $sex = $_GET['sex'];
  $age = $_GET['age'];
  $section = $_GET['section'];
  $selectingTime = $_GET['selectingTime'];
  $selectedItem = $_GET['selectedItem'];
  $selectedCategory = $_GET['selectedCategory'];
  $selectedPosition = $_GET['selectedPosition'];
  $mobile = $_GET['mobile'];
  $width = $_GET['width']; 
  $height = $_GET['height']; 
  $mouseTrack = $_GET['mouseTrack']; 
$sql = "INSERT INTO `selection_research_same`(`uuid`, `sex`, `age`,`section`,`selectingTime`,`selectedItem`,`selectedCategory`,`selectedPosition`,`mobile`,`width`,`height`,`mouseTrack`) SELECT ?,?,?,?,?,?,?,?,?,?,?,? ";
$stmt = $pdo -> prepare($sql);
$stmt->bindParam(1, $uuid);
$stmt->bindParam(2, $sex);
$stmt->bindParam(3, $age);
$stmt->bindParam(4, $section);
$stmt->bindParam(5, $selectingTime);
$stmt->bindParam(6, $selectedItem);
$stmt->bindParam(7, $selectedCategory);
$stmt->bindParam(8, $selectedPosition);
$stmt->bindParam(9, $mobile);
$stmt->bindParam(10, $width);
$stmt->bindParam(11, $height);
$stmt->bindParam(12, $mouseTrack);
$stmt->execute();
echo json_encode($result);
?>