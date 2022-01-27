<?php
// DB接続
include("functions.php");
session_start();
//セッションチェック
check_session_id();

$dbn ='mysql:dbname=gsacy_d01_10;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';
$pwd = '';

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}


// SQL作成&実行1(悪いゴミステーション)------------------------------------
//----------------------------------------------------------------------
$sql = "SELECT * FROM proto_3_table WHERE score = 1";

$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";
foreach ($result as $record) {
  $output .= "

    { lat: {$record["lat"]},lng: {$record["lng"]},date: {$record["date"]}},
  ";  
  
};


// SQL作成&実行2(良いゴミステーション)------------------------------------
//----------------------------------------------------------------------
$sql_good="SELECT * FROM proto_3_table WHERE score = 0";

$stmt_good = $pdo->prepare($sql_good);

try {
  $status_good = $stmt_good->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result_good = $stmt_good->fetchAll(PDO::FETCH_ASSOC);
$output_good = "";
foreach ($result_good as $record_good) {
  $output_good .= "
    { lat: {$record_good["lat"]},lng: {$record_good["lng"]},date: {$record_good["date"]}},
  ";
}

?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <title>ゴミステーションの状態で治安を確認</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <link rel="stylesheet" href="input.css">
</head>

<body style="background-color:#EEEEEE">




    <H1>ゴミステーションの状態で治安を確認！(入力)</H1>
    
    <div style="margin-bottom:5px";>
       <button id="gio_btn">位置情報を取得</button>
    </div>

    <form  action="create.php" method="POST" enctype="multipart/form-data">

        <div>
            緯度：<input type="text" name="lat" id="lat" ></input>　経度：<input type="text" name="lng" id="lng" ></input>
        </div>       

        <div class="score">
            状態：<select name="score" id="score" size="1" style="width:90px;height:22px" > 
            <option value=""></option>
            <option value="0">良い</option>
            <option value="1">悪い</option>
            </select>
        </div>

        <div >
        <input style="margin-top:10px"  type="file" name="upfile" accept="image/*" capture="camera" />
        </div>

        <div>
            <button id="submit">データの送信</button>
        </div>

    </form>

    <!-- 表示用の地図 -->
    <div id="map" style="width:100%;height:500px;margin-top:30px"></div>


    <div style="margin-top:20px;margin-bottom:20px;text-align:center" >
    <a href="read.php" >一覧画面</a>
    </div>

    <!-- 地図用のAPI -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=【key】&callback=initMap&v=weekly"
        async>
    </script>


    <script>

         //表示位置の定義(悪いゴミステーション)
        const data = [
            <?= $output ?>
        ];
       
      //表示位置の定義(良いゴミステーション)
        const data2 = [
            <?= $output_good ?>
        ];

        //地図表示
        function initMap() {

            //指定した位置情報を中心にマップを表示
            let map;
            map = new google.maps.Map(document.getElementById("map"), {
            
                center: {
                    lat: 34.052400, lng: 131.829000,
                },

                zoom: 8,
                radius: 5,

            });
            


         //悪いゴミステーションのマッピング
          data.map(d => {
          // マーカーをつける(悪い方)
            const marker = new google.maps.Marker({

              position: { lat: d.lat, lng: d.lng },
                map: map,

                icon: {
                  url: "img/circle_red.png",
                  scaledSize: new google.maps.Size(45, 45)
                }

            });
            //クリックしたら情報を表示
            const infoWindow = new google.maps.InfoWindow({
	      	    content:"緯度:"+JSON.stringify(d.lat)+"<br>"+"経度:"+JSON.stringify(d.lng)+"<br>"+"調査日:"+d.date //情報ウィンドウのテキスト
        	  });
          
	          google.maps.event.addListener(marker, 'click', function() { //マーカークリック時の動作
	      	    infoWindow.open(map, marker); //情報ウィンドウを開く
        	  });

          });

         data2.map(d2 => {
           // マーカーをつける(良い方)
           const marker2 = new google.maps.Marker({

           position: { lat: d2.lat, lng: d2.lng },
              map: map,
              icon: {
                url: "img/circle_green.png",
                scaledSize: new google.maps.Size(45, 45)
              }
            });

            //クリックしたら情報を表示
            const infoWindow = new google.maps.InfoWindow({
	      	    content:"緯度:"+JSON.stringify(d2.lat)+"<br>"+"経度:"+JSON.stringify(d2.lng)+"<br>"+"調査日:"+JSON.stringify(d2.date) //情報ウィンドウのテキスト
        	  });

	          google.maps.event.addListener(marker2, 'click', function() { //マーカークリック時の動作


	      	    infoWindow.open(map, marker2); //情報ウィンドウを開く

        	  });


          });








        };

        //ボタンを押したら位置情報を取得してinputに入力
        $("#gio_btn").on("click", function () {

            //位置情報を取得してテキストボックスに反映
            function success(pos){
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    $('#lat').val(lat);
                    $('#lng').val(lng);


                    //取得した座標を中心に設定
                    let map2;
                    map2 = new google.maps.Map(document.getElementById("map"), {
                    
                        center: {
                            lat:pos.coords.latitude, lng: pos.coords.longitude,
                        },

                        zoom: 17,
                        radius: 5,

                    });
                        
                    //ピンを置く
                    var MyLatLng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                    var marker = new google.maps.Marker({
                        position: MyLatLng,
                        map: map2,
                    });



                    //悪いゴミステーションのマッピング
                    data.map(d => {
                    // マーカーをつける(悪い方)
                    const marker = new google.maps.Marker({

                    position: { lat: d.lat, lng: d.lng },
                        map: map2,

                        icon: {
                        url: "img/circle_red.png",
                        scaledSize: new google.maps.Size(45, 45)
                        }

                    });
                    //クリックしたら情報を表示
                    const infoWindow = new google.maps.InfoWindow({
                        content:"緯度:"+JSON.stringify(d.lat)+"<br>"+"経度:"+JSON.stringify(d.lng)+"<br>"+"調査日:"+d.date //情報ウィンドウのテキスト
                    });
                
                    google.maps.event.addListener(marker, 'click', function() { //マーカークリック時の動作

                        infoWindow.open(map2, marker); //情報ウィンドウを開く
                    });

                });
            




                    data2.map(d2 => {
                // マーカーをつける(良い方)
                const marker2 = new google.maps.Marker({

                position: { lat: d2.lat, lng: d2.lng },
                    map: map2,
                    icon: {
                        url: "img/circle_green.png",
                        scaledSize: new google.maps.Size(45, 45)
                    }
                    });

                    //クリックしたら情報を表示
                    const infoWindow = new google.maps.InfoWindow({
                        content:"緯度:"+JSON.stringify(d2.lat)+"<br>"+"経度:"+JSON.stringify(d2.lng)+"<br>"+"調査日:"+JSON.stringify(d2.date) //情報ウィンドウのテキスト
                    });

                    google.maps.event.addListener(marker2, 'click', function() { //マーカークリック時の動作
                    
                        infoWindow.open(map2, marker2); //情報ウィンドウを開く

                    });


                });








                    

                }



                function fail(pos){
                alert('位置情報の取得に失敗しました。エラーコード：');
                }

                navigator.geolocation.getCurrentPosition(success,fail);



            

                
        });




    </script>



  
</body>

</html>