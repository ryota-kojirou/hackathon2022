<?php
// 文字コード設定
header('Content-Type: text/html; charset=UTF-8');

// WebAPIのURL
$url = "https://ryota.nkmr.io/m1_hackathon_2022/json_output.php?host_id=eudkwp6yP2hVUpkEAw4XSawMipRd";

$options = stream_context_create(array('ssl' => array(
    'verify_peer'      => false,
    'verify_peer_name' => false
  )));

// URLの内容を取得し、json形式からstdClass形式に変換し取得
$data = json_decode(file_get_contents($url, false, $options));
// 連想配列で取得したかったら第二引数にtrueを指定↓
// $data = json_decode(file_get_contents($url), true);

print($data->status);
// dataのstatusがyesだったら(出力に成功したら)
if($data->status == "yes") {
    // 114倍の値をprint
    print $data->x114;
}