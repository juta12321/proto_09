<?php

include("functions.php");
session_start();

//メッシュコードをHTMLから持ってくる
$mesh=filter_input(INPUT_POST,"mesh");

// DB接続
$pdo = connect_to_db();




// SQL作成&実行1(悪いゴミステーションの数を集計)------------------------------------
//----------------------------------------------------------------------
$sql = "SELECT * FROM proto_3_table WHERE mesh = $mesh and score = 1" ;

$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output_mesh = "";
foreach ($result as $record) {
  $output_mesh .= "{id: {$record["id"]}}";  
};


// SQL作成&実行1(良いゴミステーションの数を集計)------------------------------------
//----------------------------------------------------------------------
$sql = "SELECT * FROM proto_3_table WHERE mesh = $mesh and score = 0" ;

$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output_mesh_good = "";
foreach ($result as $record) {
  $output_mesh_good .= "{id: {$record["id"]}}";  
};




?>

<script>

<?=$output_mesh?>,<?=$output_mesh_good?>

</script>



