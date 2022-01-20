<?php

// var_dump($_POST);
// exit();
include("functions.php");

if (
  !isset($_POST['username']) || $_POST['username']=='' ||
  !isset($_POST['password']) || $_POST['password']==''  
) {
  exit('ParamError');
}

$username = $_POST['username'];
$password = $_POST['password'];


// 各種項目設定
$dbn ='mysql:dbname=gsacy_d01_10;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
$pdo = connect_to_db();

// SQL作成&実行
$sql = 'INSERT INTO users_table (id, username, password, is_admin, created_at,updated_at) VALUES (NULL, :username, :password, 0, now(),now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);


// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

//戻る
header("Location:login_user.php");
exit();