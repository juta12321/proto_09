<?php

session_start();
include("functions.php");
check_session_id();


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


// DB接続
$pdo = connect_to_db();

//画像をフォルダに保存
//画像の保存
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  // 送信が正常に行われたときの処理
  $uploaded_file_name = $_FILES['upfile']['name'];
  $temp_path  = $_FILES['upfile']['tmp_name'];
  $directory_path = 'upload/';

  $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
  $unique_name = date('YmdHis').md5(session_id()) . '.' . $extension;
  $save_path = $directory_path . $unique_name;

  if (is_uploaded_file($temp_path)) {
    if (move_uploaded_file($temp_path, $save_path)) {
      chmod($save_path, 0644);
      $img = '<img src="'. $save_path . '" >';
    } else {
        exit('Error:アップロードできませんでした');
      }
    
    } else {
        exit('Error:画像がありません');
      }
  } else {
     exit('Error:画像が送信されていません');
    } 





// SQL作成&実行 ※reasonを付けないといけないけど付けてない
$sql = 'INSERT INTO proto_3_table (id, date, lat, lng, score,image) VALUES (NULL, now(), :lat, :lng, :score, :image)';


$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
$stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
$stmt->bindValue(':score', $score, PDO::PARAM_STR);
$stmt->bindValue(':image', $save_path, PDO::PARAM_STR);


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