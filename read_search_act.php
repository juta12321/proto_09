<?php

include("functions.php");
session_start();

// POSTデータ確認
if (
  !isset($_POST['keyword']) || $_POST['keyword']==''
) {
  exit('ParamError');
}

$keyword = $_POST['keyword'];

// DB接続
$pdo = connect_to_db();


// SQL作成&実行
$sql = 'INSERT INTO search_table (id, keyword, searched_at) VALUES (NULL, :keyword, now())';
$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

?>





