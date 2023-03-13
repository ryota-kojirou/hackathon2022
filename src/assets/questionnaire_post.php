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
  $q1 = $_GET['q1'];
  $q2 = $_GET['q2'];
  $q3 = $_GET['q3'];
  $badID = $_GET['badID'];
  
$sql = "INSERT INTO `selection_research_same_questionnaire`(`uuid`, `q1`, `q2`, `q3`,`badID`) SELECT ?,?,?,?,? ";
//$sql = "INSERT INTO `selection_research_same_questionnaire`(`uuid`, `q1`, `q2`, `badID`) SELECT ?,?,?,? ";
$stmt = $pdo -> prepare($sql);
$stmt->bindParam(1, $uuid);
$stmt->bindParam(2, $q1);
$stmt->bindParam(3, $q2);
$stmt->bindParam(4, $q3);
$stmt->bindParam(5, $badID);
$stmt->execute();
echo json_encode($result);
?>