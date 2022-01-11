<?php
//  var_dump($_POST);
//  exit();

// POSTデータ確認

if (
  !isset($_POST['lat']) || $_POST['lat']=='' ||
  !isset($_POST['lng']) || $_POST['lng']=='' ||
  !isset($_POST['score']) || $_POST['score']==''
  
) {
  exit('ParamError');
}

$lat = $_POST['lat'];
$lng = $_POST['lng'];
$score = $_POST['score'];




// 各種項目設定
$dbn ='mysql:dbname=gsacy_d01_10;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}




// SQL作成&実行
$sql = 'INSERT INTO proto_3_table (id, date, lat, lng, score) VALUES (NULL, now(), :lat, :lng, :score)';


$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
$stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
$stmt->bindValue(':score', $score, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}


//戻る
header('Location:input.php');
exit();