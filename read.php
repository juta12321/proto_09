<?php
// DB接続
include("functions.php");
session_start();
//セッションチェック
// check_session_id();

// DB接続
$pdo = connect_to_db();

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
//↓これだけ、ゴミステーションの調査数を集計する用のカウンター
$count=0;
foreach ($result as $record) {

  //表示用にdateを細分化
  $year = substr($record['date'],0,4); 
  $month = substr($record['date'],5,2); 
  $day = substr($record['date'],8,2); 

  $output .= "{
    lat: {$record["lat"]},
    lng: {$record["lng"]},
    year: {$year},
    month: {$month},
    day: {$day},
    score: {$record["score"]},
    reason: '{$record["reason"]}',
    id: {$record["id"]},
    image:'{$record["image"]}',
    image2:'{$record["image2"]}',
    image3:'{$record["image3"]}',    
  },
  ";  
    //↓これも、ゴミステーションの調査数を集計する用のカウンター
  $count++;
   
};

// SQL作成&実行2(良いゴミステーション)------------------------------------
//----------------------------------------------------------------------
$sql_good="SELECT * FROM proto_3_table WHERE score = 0 ";

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
//↓これだけ、ゴミステーションの調査数を集計する用のカウンター
$count_good=0;
foreach ($result_good as $record_good) {

  //表示用にdateを細分化
  $year = substr($record_good['date'],0,4); 
  $month = substr($record_good['date'],5,2); 
  $day = substr($record_good['date'],8,2); 

  $output_good .= "{
    lat: {$record_good["lat"]},
    lng: {$record_good["lng"]},
    year: {$year},
    month: {$month},
    day: {$day},
    score: {$record_good["score"]},
    image:'{$record_good["image"]}',
    image2:'{$record_good["image2"]}',
    image3:'{$record_good["image3"]}',  
  },
  ";
  //↓これも、ゴミステーションの調査数を集計する用のカウンター
  $count_good++;
  
}

// // SQL作成&実行3(検索エリアのゴミステーション(悪い方)の数を集計)--------------------
// //----------------------------------------------------------------------
// $sql_count = "SELECT * FROM proto_3_table WHERE score = 1 and mesh=50302382";
// $stmt_count = $pdo->prepare($sql_count);

// try {
//   $status_count = $stmt_count->execute();
// } catch (PDOException $e) {
//   echo json_encode(["sql error" => "{$e->getMessage()}"]);
//   exit();
// }

// // SQL実行の処理
// $result_count = $stmt_count->fetchAll(PDO::FETCH_ASSOC);
// $output_count = "";
// //ゴミステーション(悪い方)の調査数を集計する用のカウンター
// $count=0;
// foreach ($result_count as $record_count) {

//   //ゴミステーションの調査数を集計する用のカウンター
// $count++;
   
// };

// // SQL作成&実行3(検索エリアのゴミステーション(良い方)の数を集計)--------------------
// //----------------------------------------------------------------------
// $sql_count_good = "SELECT * FROM proto_3_table WHERE score = 0 and mesh=50302382";
// $stmt_count_good = $pdo->prepare($sql_count_good);

// try {
//   $status_count_good = $stmt_count_good->execute();
// } catch (PDOException $e) {
//   echo json_encode(["sql error" => "{$e->getMessage()}"]);
//   exit();
// }

// // SQL実行の処理
// $result_count_good = $stmt_count_good->fetchAll(PDO::FETCH_ASSOC);
// $output_count_good = "";
// //ゴミステーション(悪い方)の調査数を集計する用のカウンター
// $count_good=0;
// foreach ($result_count_good as $record_count_good) {

//   //ゴミステーションの調査数を集計する用のカウンター
// $count_good++;
   
// };













// // ユーザー情報----------------------------------------------------------
// //----------------------------------------------------------------------
// $sql_user="SELECT * FROM users_table WHERE username = '".$_SESSION['username']."'";
// $stmt_user = $pdo->prepare($sql_user);

// try {
//   $status_user = $stmt_user->execute();
// } catch (PDOException $e) {
//   echo json_encode(["sql error" => "{$e->getMessage()}"]);
//   exit();
// }

// // SQL実行の処理
// $result_user = $stmt_user->fetchAll(PDO::FETCH_ASSOC);
// $output_user = "";
// foreach ($result_user as $record_user){

//   $output_user .= "
//       {$record_user["username"]}    
    
//   ";
// }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>一覧画面</title>
  <link rel="stylesheet" href="read.css">  

</head>

<body>
  
  
  <header class="header">

    <div class="main-header">

      <div class="header-inner">
        <div class="logo">
          <img src="img/logo.png" >
        </div>

        <div class="header-text">
          日本で唯一の<br>
          治安情報閲覧サイト<b><font color="#166678"> 治安Easy</font></b><br>
        </div>

        <nav class="header-nav">
          

          <div class="header-nav-item">
            <a class="header-button header-explain">メニュー１</a>
          </div>


          <div class="header-nav-item">
            <a class="header-button header-login">ログイン</a>
          </div>        



        </nav>

      </div>
    </div>

  </header>

  <div class="main-body">

  
      <!-- ユーザー名表示 -->

      <!-- <div style="text-align:right">
        <//?= $_SESSION["username"] ?> 様　<a href="logout_user.php">ログアウトする</a>
      </div> -->

      <!-- 住所入力 -->

      <div class="body-about">
        <input id="acd-check1" class="acd-check" type="checkbox">
        <label class="acd-label" for="acd-check1"><img src="img/popup.png" ></label>
        <div class="acd-content">
        <p>texttexttexttexttexttexttexttexttexttexttexttexttexttexttexttexttext</p>
       
      </div>
    </div>


        <div class="text-input">

          <h2>治安を調べたい場所の情報を入力してください</h2><span style="font-size:10px"> ※β版のため福岡県福岡市のみサービス提供中です</span><br>
          <!-- 都道府県 -->
          <select name="都道府県">
          <option value="選択肢1">福岡県</option>
          </select>
          <!-- 市町村 -->
          <select type="text" id="addressInput1">
          <option type="text" id="addressInput2">福岡市</option>
          </select>
          <input class="input" type="text" id="addressInput3" placeholder="駅や建物名などキーワードを入力してください" name="keyword">
          <button id="searchGeo">検索</button>

          

        </div>
      <!-- </form> -->

      <div>
          <input type="hidden" id="lat" name="lat_geo">
          <input type="hidden" id="lng" name="lng_geo">
      </div>
      

      <div id="map" class="map"></div>

  </div>



  <script
      src="https://maps.googleapis.com/maps/api/js?key=【key】&callback=initMap&v=weekly"
      async>
  </script>
    
  <!-- jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <!-- <legend>一覧画面</legend> -->
    <div style="text-align:center;margin-top:10px">
      <a href="login_admin.php">-管理画面-</a>
    </div>
    

 

    <script> 

      //googlemapの表示
      function initMap() {

        //指定した位置情報を中心にマップを表示(初期画面)
        let map;
        map = new google.maps.Map(document.getElementById("map"), {
        
          center: {
            lat: 39.05240000, lng: 136.82900000,
          },

          zoom: 5.5  ,
          radius: 5,

          });

        }


        //検索ボタンを押したらその場所を中心に地図表示
        //郵便番号から位置情報検索
        $('#searchGeo').on('click', function getLatLng() {

          // 入力した住所を取得します。
          var addressInput = document.getElementById('addressInput1').value+" "+document.getElementById('addressInput2').value+" "+document.getElementById('addressInput3').value;
          console.log(addressInput)
          // Google Maps APIのジオコーダを使います。
          var geocoder = new google.maps.Geocoder();

          // ジオコーダのgeocodeを実行します。
          // 第１引数のリクエストパラメータにaddressプロパティを設定します。
          // 第２引数はコールバック関数です。取得結果を処理します。
          geocoder.geocode({
            address: addressInput
          },

          function (results, status){
            var latlng = "";
            if (status == google.maps.GeocoderStatus.OK){
              // 取得が成功した場合
              // 結果をループして取得します。
              for (var i in results){
                if (results[i].geometry){
                  // 緯度を取得します。
                  var lat = results[i].geometry.location.lat();
                  // 経度を取得します。
                  var lng = results[i].geometry.location.lng();
                  // val()メソッドを使ってvalue値を設定できる
                  // idがlat(またはlng)のvalue値に、変数lat(またはlng)を設定する

                  $('#lat').val(lat);
                  $('#lng').val(lng);

                  //メッシュ変換コード
                  //緯度
                  var p = String(Math.floor((lat * 60) / 40));
                  var a = (lat * 60) % 40;
                  var q = String(Math.floor(a / 5));
                  var b = a % 5;
                  var r = String(Math.floor((b * 60) / 30));
                  var c = (b * 60) % 30;
                  var s = String(Math.floor(c / 15));
                  var d = c % 15;
                  var t = String(Math.floor(d / 7.5));
                  //経度
                  var u = String(Math.floor(lng - 100));
                  var f = lng - 100 - u;
                  var v = String(Math.floor((f * 60) / 7.5));
                  var g = (f * 60) % 7.5;
                  var w = String(Math.floor((g * 60) / 45));
                  
                  //一次メッシュ
                  const mesh1=p+u;
                  //二次メッシュ
                  const mesh2=q+v;
                  //三次メッシュ
                  const mesh3=r+w;

                  //メッシュ全部
                  const mesh = mesh1+mesh2+mesh3;
                  console.log(mesh)

                  //メッシュからその場所の中心緯度経度を求める

                  const mesh_center_lat1 = Number(String(mesh).substr(0,2))/1.5*3600
                  const mesh_center_lat2 = Number(String(mesh).substr(4,1))*5*60             
                  const mesh_center_lat3 = Number(String(mesh).substr(6,1))*30
                  const mesh_center_lat =(mesh_center_lat1 + mesh_center_lat2 + mesh_center_lat3)/3600

                  const mesh_center_lng1 = (Number(String(mesh).substr(2,2))+100)*3600
                  const mesh_center_lng2 = Number(String(mesh).substr(5,1))*7.5*60
                  const mesh_center_lng3 = Number(String(mesh).substr(7,1))*45
                  const mesh_center_lng =(mesh_center_lng1 + mesh_center_lng2 + mesh_center_lng3)/3600

                  console.log(mesh_center_lat+","+mesh_center_lng)
                  

                  //ajaxでmeshに該当するゴミステーションの数を取得
                  $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"mesh":mesh},
                  })
                  
                  // Ajaxリクエストが成功した場合
                  //ajax処理後の情報を基に治安率を算出
                  .done(function (ajax0) {
                    const ajax1 = ajax0.replace('/',''); 
                    const ajax2 = ajax1.replace(/<script>/g,''); 
                    const ajax3 = ajax2.replace(/id: /g,''); 
                    const ajax4 = ajax3.replace(/\r?\n/g,''); 
                    const ajax5 = ajax4.replace(/{/g,'').split(","); 
                    //悪いゴミステーションの数
                    const ajax =ajax5[0].split("}").length-1
                    //良いゴミステーションの数
                    const ajax_good =ajax5[1].split("}").length-1
                   
                    console.log("このエリアの悪いゴミステーションは"+ajax+"ヶ所")
                    console.log("このエリアの良いゴミステーションは"+ajax_good+"ヶ所")
                    //治安率
                    const ajax_percent =String(ajax_good/(ajax+ajax_good)*100).substr(0,4)
                    console.log("このエリアのゴミステーション治安率は"+ajax_percent+"%です。")
                    
                    
                    //地図上にゴミステーション治安率を表示
                    let map;
                    map = new google.maps.Map(document.getElementById("map"), {
                  
                      center: {
                        lat:lat , lng: lng,
                      },

                      zoom: 15  ,
                      radius: 5,
                      //scrollwheel: false,
                      zoomControl: false
                    });



                    //infowindow表示のためのmaker作成
                    const marker = new google.maps.Marker({
                      map: map,
                      position:{lat:lat,lng:lng}
                    });

                    //infowindow表示
                    const infoWindow = new google.maps.InfoWindow({
                      content: "<div>このエリアのゴミステーション治安率は"+ajax_percent+"%です。</div>"
                    });
                  
                    infoWindow.open(map, marker);                    


                    // //infowindow表示のためのmaker2作成
                    // const marker2 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat+0.012,lng:mesh_center_lng+0.0065}
                    // });

                    // //infowindow表示
                    // const infoWindow2 = new google.maps.InfoWindow({
                    //   content: "<div>このエリアのゴミステーション治安率は</div>"
                    // });
                  
                    // infoWindow2.open(map, marker2);      



                    // //infowindow表示のためのmaker3作成
                    // const marker3 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat+0.012,lng:mesh_center_lng+0.0185}
                    // });


                    // //infowindow表示
                    // const infoWindow3 = new google.maps.InfoWindow({
                    //   content: "<div>このエリアのゴミステーション治安率は</div>"
                    // });
                  
                    // infoWindow3.open(map, marker3);      




                    // //infowindow表示のためのmaker4作成
                    // const marker4 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat+0.004,lng:mesh_center_lng+0.0185}
                    // });


                    //   //infowindow表示
                    //   const infoWindow4 = new google.maps.InfoWindow({
                    //     content: "<div>このエリアのゴミステーション治安率は</div>"
                    //   });
                    
                    //   infoWindow4.open(map, marker4);      


                    // //infowindow表示のためのmaker5作成
                    // const marker5 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat-0.0048,lng:mesh_center_lng+0.0185}
                    // });


                    //   //infowindow表示
                    //   const infoWindow5 = new google.maps.InfoWindow({
                    //     content: "<div>このエリアのゴミステーション治安率は</div>"
                    //   });
                    
                    //   infoWindow5.open(map, marker5);                          



                    // //infowindow表示のためのmaker6作成
                    // const marker6 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat-0.0048,lng:mesh_center_lng+0.0065}
                    // });
                      
                    //   //infowindow表示
                    //   const infoWindow6 = new google.maps.InfoWindow({
                    //     content: "<div>このエリアのゴミステーション治安率は</div>"
                    //   });
                    
                    //   infoWindow6.open(map, marker6);      




                    // //infowindow表示のためのmaker7作成
                    // const marker7 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat-0.0048,lng:mesh_center_lng-0.0065}
                    // });


                    //   //infowindow表示
                    //   const infoWindow7 = new google.maps.InfoWindow({
                    //     content: "<div>このエリアのゴミステーション治安率は</div>"
                    //   });
                    
                    //   infoWindow7.open(map, marker7);      



                    // //infowindow表示のためのmaker8作成
                    // const marker8 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat+0.004,lng:mesh_center_lng-0.0065}
                    // });                      

                    //   //infowindow表示
                    //   const infoWindow8 = new google.maps.InfoWindow({
                    //     content: "<div>このエリアのゴミステーション治安率は</div>"
                    //   });
                    
                    //   infoWindow8.open(map, marker8);      



                    // //infowindow表示のためのmaker9作成
                    // const marker9 = new google.maps.Marker({
                    //   map: map,
                    //   position:{lat:mesh_center_lat+0.012,lng:mesh_center_lng-0.0065}
                    // });                      
                          

                    //   //infowindow表示
                    //   const infoWindow9 = new google.maps.InfoWindow({
                    //     content: "<div>このエリアのゴミステーション治安率は</div>"
                    //   });
                    
                    //   infoWindow9.open(map, marker9);      



                    // マップにメッシュを追加(中心)      
                    // var code = mesh;
                    // console.log(code)
                    // for(var i=0;i<4;i++){
                    //   var loc =  meshcode2latlng.quater(code);
                    //   var rectangle = new google.maps.Rectangle({
                    //     strokeColor: '#0000ff',
                    //     strokeWeight: 0.5,
                    //     fillColor: '#ffffff00',
                    //     map: map,
                    //     bounds: {
                    //       south: loc.south,
                    //       west: loc.west,
                    //       north: loc.north+0.006248 ,
                    //       east: loc.east+0.009375
                    //     }
                    //   });
                    // }

                    // // マップにメッシュを追加(中心東向き)
                    // var mesh_n=mesh.substr(0,7)+0
                    // console.log(mesh_n)
                    // for(var m=0;m<10;m++){
                    //   var code = Number(mesh_n)+m;
                    //   for(var i=0;i<4;i++){
                    //     var loc =  meshcode2latlng.quater(code);
                    //     var rectangle = new google.maps.Rectangle({
                    //       strokeColor: '#ff0000',
                    //       strokeWeight: 0.5,
                    //       fillColor: '#ffffff00',
                    //       map: map,
                    //       bounds: {
                    //         south: loc.south,
                    //         west: loc.west,
                    //         north: loc.north+0.006248,
                    //         east: loc.east+0.009375
                    //       }
                    //     });
                    //   }
                    // };


                    // //マップにメッシュを追加(中心+1)※上限の場合追加しない
                    // if(!(Number(mesh.substr(-2,1))+1===10)){
                    //   var mesh_n=mesh.substr(0,7)+0
                    //   for(var m3=0;m3<10;m3++){
                    //     var code = Number(mesh_n)+10+m3;

                    //     for(var i=0;i<4;i++){
                    //       var loc =  meshcode2latlng.quater(code);
                    //       var rectangle = new google.maps.Rectangle({
                    //         strokeColor: '#ff0000',
                    //         strokeWeight: 0.5,
                    //         fillColor: '#ffffff00',
                    //         map: map,
                    //         bounds: {
                    //           south: loc.south,
                    //           west: loc.west,
                    //           north: loc.north+0.006248,
                    //           east: loc.east+0.009375
                    //         }
                    //       });
                    //     }
                    //   }
                    // }

                    // //マップにメッシュを追加(中心+2)※上限の場合追加しない
                    // if(!(Number(mesh.substr(-2,1))+2===10)){
                    //   var mesh_n=mesh.substr(0,7)+0
                    //   for(var m5=0;m5<10;m5++){
                    //     var code = Number(mesh_n)+20+m5;
                    //     for(var i=0;i<4;i++){
                    //       var loc =  meshcode2latlng.quater(code);
                    //       var rectangle = new google.maps.Rectangle({
                    //         strokeColor: '#ff0000',
                    //         strokeWeight: 0.5,
                    //         fillColor: '#ffffff00',
                    //         map: map,
                    //         bounds: {
                    //           south: loc.south,
                    //           west: loc.west,
                    //           north: loc.north+0.006248,
                    //           east: loc.east+0.009375
                    //         }
                    //       });
                    //     }
                    //   }
                    // }


                    // //マップにメッシュを追加(中心+3)※上限の場合追加しない
                    // if(!(Number(mesh.substr(-2,1))+3===10)){
                    //   var mesh_n=mesh.substr(0,7)+0
                    //   for(var m5=0;m5<10;m5++){
                    //     var code = Number(mesh_n)+30+m5;
                    //     for(var i=0;i<4;i++){
                    //       var loc =  meshcode2latlng.quater(code);
                    //       var rectangle = new google.maps.Rectangle({
                    //         strokeColor: '#ff0000',
                    //         strokeWeight: 0.5,
                    //         fillColor: '#ffffff00',
                    //         map: map,
                    //         bounds: {
                    //           south: loc.south,
                    //           west: loc.west,
                    //           north: loc.north+0.006248,
                    //           east: loc.east+0.009375
                    //         }
                    //       });
                    //     }
                    //   }
                    // }


                   //マップにメッシュを追加※追加エリアは目的地をメッシュ上で上方向に2桁範囲内まで
                    for(mesh_r=0;mesh_r<10;mesh_r++){
                    if(!(Number(mesh.substr(-2,1))+mesh_r>9)){
                      var mesh_n=mesh.substr(0,7)+0
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r)*10+m;
                        var loc =  meshcode2latlng.quater(code);
                        for(var i=0;i<4;i++){
                            var rectangle = new google.maps.Rectangle({
                              strokeColor: '#ff69b4',
                              strokeWeight: 0.5,
                              fillColor: '#ffffff00',
                              map: map,
                              bounds: {
                                south: loc.south,
                                west: loc.west,
                                north: loc.north+0.006248,
                                east: loc.east+0.009375
                              }
                              
                            });
                         
                        }
                      }
                    }
                  }

                  //検索目的地のメッシュのみ色付け
                  var loc =  meshcode2latlng.quater(mesh);
                  var rectangle = new google.maps.Rectangle({
                    strokeColor: '#ff69b4',
                    strokeWeight: 0.5,
                    fillColor: '#0067c0',
                    map: map,
                    bounds: {
                      south: loc.south,
                      west: loc.west,
                      north: loc.north+0.006248 ,
                      east: loc.east+0.009375
                    }
                  });

                // //検索目的地北側のメッシュのみ色付け-①
                //   //北側のメッシュ指定するために、メッシュコードに+10する
                //   //メッシュコードでは、+10した際に桁が繰り上がると少し計算が違うので、まずは繰り上がらない範囲だけ計算
                //   if((String(Number(mesh)+10).substr(6,2))>9){
                //     const loc_north =  meshcode2latlng.quater(Number(mesh)+10);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_north.south,
                //         west: loc_north.west,
                //         north: loc_north.north+0.006248 ,
                //         east: loc_north.east+0.009375
                //       }
                //     });
                //   }
                 
                // //検索目的地北側のメッシュのみ色付け-②
                //   //北側に繰り上がる場合の計算方法
                //   else if((String(Number(mesh)+10).substr(6,2))<10){
                //     //メッシュ1~4桁
                //     const keta1 = String(mesh).substr(0,4)  
                //     //メッシュ5桁目
                //     const keta2 =Number(String(mesh).substr(4,1))+1
                //     //メッシュ6桁目
                //     const keta3 =String(mesh).substr(5,1)
                //     //メッシュ7~8桁目
                //     const keta4 =String(Number(mesh)+10).substr(6,2)
                //     const mesh_north =keta1 +keta2 +keta3 +keta4
                //     const loc_north =  meshcode2latlng.quater(Number(mesh_north));
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_north.south,
                //         west: loc_north.west,
                //         north: loc_north.north+0.006248 ,
                //         east: loc_north.east+0.009375
                //       }
                //     });
                //   }

                // //検索目的地南側のメッシュのみ色付け-①
                //   //南側のメッシュ指定するために、メッシュコードに-10する
                //   //メッシュコードでは、-10した際に桁が繰り下がると少し計算が違うので、まずは繰り下がらない範囲だけ計算
                //   if((String(Number(mesh)-10).substr(6,2))<90){
                //     const loc_south =  meshcode2latlng.quater(Number(mesh)-10);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_south.south,
                //         west: loc_south.west,
                //         north: loc_south.north+0.006248 ,
                //         east: loc_south.east+0.009375
                //       }
                //     });
                //   }

                // //検索目的地南側のメッシュのみ色付け-②
                //   //南側に繰り下がる場合の計算方法
                //   else if((String(Number(mesh)-10).substr(6,2))>89){
                //     //メッシュ1~4桁
                //     const keta1 = String(mesh).substr(0,4)  
                //     //メッシュ5桁目
                //     const keta2 =Number(String(mesh).substr(4,1))-1
                //     //メッシュ6桁目
                //     const keta3 =String(mesh).substr(5,1)
                //     //メッシュ7~8桁目
                //     const keta4 =String(Number(mesh)-10).substr(6,2)
                //     const mesh_south =keta1 +keta2 +keta3 +keta4
                //     const loc_south =  meshcode2latlng.quater(Number(mesh_south));
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_south.south,
                //         west: loc_south.west,
                //         north: loc_south.north+0.006248 ,
                //         east: loc_south.east+0.009375
                //       }
                //     });
                //   }
                  
                // //検索目的地西側のメッシュのみ色付け-①
                //   //西側のメッシュ指定するために、メッシュコードに-1する
                //   //メッシュコードでは、-1した際に桁が繰り下がると少し計算が違うので、まずは繰り下がらない範囲だけ計算
                //   if((String(Number(mesh)-1).substr(7,1))<9){
                //     const loc_west =  meshcode2latlng.quater(Number(mesh)-1);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_west.south,
                //         west: loc_west.west,
                //         north: loc_west.north+0.006248 ,
                //         east: loc_west.east+0.009375
                //       }
                //     });
                //   }

                // //検索目的地西側のメッシュのみ色付け-②
                //   //西側に繰り下がる場合の計算方法
                //   else if((String(Number(mesh)-1).substr(7,1))>8)
                //     //メッシュ1~4桁
                //     var keta1 = String(mesh).substr(0,4)  
                //     //メッシュ5桁目
                //     var keta2 = Number(String(mesh).substr(4,1))
                //     //メッシュ6桁目
                //     var keta3 = Number(String(mesh).substr(5,1))-1
                //     //メッシュ7桁目
                //     var keta4 =String(Number(mesh)).substr(6,1)
                //     //メッシュ8桁目
                //     var keta5 =9
                  
                //     var mesh_west =keta1 +keta2 +keta3 +keta4+keta5
                //     var loc_west =  meshcode2latlng.quater(Number(mesh_west));
                //     var rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_west.south,
                //         west: loc_west.west,
                //         north: loc_west.north+0.006248 ,
                //         east: loc_west.east+0.009375
                //       }
                //     });
                  
  
                // //検索目的地東側のメッシュのみ色付け-①
                //   //東側のメッシュ指定するために、メッシュコードに+1する
                //   //メッシュコードでは、+1した際に桁が繰り上がると少し計算が違うので、まずは繰り上がらない範囲だけ計算
                //   if((String(Number(mesh)+1).substr(7,1))>0){
                //     const loc_west =  meshcode2latlng.quater(Number(mesh)+1);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_west.south,
                //         west: loc_west.west,
                //         north: loc_west.north+0.006248 ,
                //         east: loc_west.east+0.009375
                //       }
                //     });
                //   }

                // //検索目的地東側のメッシュのみ色付け-②
                //   //東側に繰り上がる場合の計算方法
                //   else if((String(Number(mesh)+1).substr(7,1))<1)
                //     //メッシュ1~4桁
                //     var keta1 = String(mesh).substr(0,4)  
                //     //メッシュ5桁目
                //     var keta2 = Number(String(mesh).substr(4,1))
                //     //メッシュ6桁目
                //     var keta3 = Number(String(mesh).substr(5,1))+1
                //     //メッシュ7桁目
                //     var keta4 =String(Number(mesh)).substr(6,1)
                //     //メッシュ8桁目
                //     var keta5 =0
                  
                //     var mesh_east =keta1 +keta2 +keta3 +keta4+keta5
                //     var loc_east =  meshcode2latlng.quater(Number(mesh_east));
                //     var rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_east.south,
                //         west: loc_east.west,
                //         north: loc_east.north+0.006248 ,
                //         east: loc_east.east+0.009375
                //       }
                //     });

                // //検索目的地北東のメッシュのみ色付け-①
                //   //東側のメッシュ指定するために、メッシュコードに+11する
                //   //メッシュコードでは、+11した際に桁が繰り上がると少し計算が違うので、まずは繰り上がらない範囲だけ計算
                //   if((String(Number(mesh)+1).substr(7,1))>0&&(String(Number(mesh)+10).substr(6,1))>0){
                //     const loc_northeast =  meshcode2latlng.quater(Number(mesh)+11);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_northeast.south,
                //         west: loc_northeast.west,
                //         north: loc_northeast.north+0.006248 ,
                //         east: loc_northeast.east+0.009375
                //       }
                //     });
                //   }

                // //検索目的地北東のメッシュのみ色付け-②
                //   //北東に繰り上がる場合の計算方法
                //   else if((String(Number(mesh)+1).substr(7,1))<1||(String(Number(mesh)+10).substr(6,1))<1){
                //     //斜めの表示はちょっと面倒。全部で三段階あって、まずは東側も北側も繰り上がる場合
                //     if((String(Number(mesh)+1).substr(7,1))<1&&(String(Number(mesh)+10).substr(6,1))<1){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))+1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))+1
                //       //メッシュ7桁目
                //       var keta4 =0
                //       //メッシュ8桁目
                //       var keta5 =0

                //       var mesh_northeast =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northeast =  meshcode2latlng.quater(Number(mesh_northeast));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_northeast.south,
                //           west: loc_northeast.west,
                //           north: loc_northeast.north+0.006248 ,
                //           east: loc_northeast.east+0.009375
                //         }
                //       });
                //     }

                //     //次に東側にのみ繰り上がる場合
                //     else if(String(Number(mesh)+1).substr(7,1)<1){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))+1
                //       //メッシュ7桁目
                //       var keta4 =Number(String(mesh).substr(6,1))+1
                //       //メッシュ8桁目
                //       var keta5 =0

                //       var mesh_northeast =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northeast =  meshcode2latlng.quater(Number(mesh_northeast));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_northeast.south,
                //           west: loc_northeast.west,
                //           north: loc_northeast.north+0.006248 ,
                //           east: loc_northeast.east+0.009375
                //         }
                //       });

                //     }

                //     //最後に北側にのみ繰り上がる場合
                //     else if(String(Number(mesh)+10).substr(6,1)<1){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))+1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))
                //       //メッシュ7桁目
                //       var keta4 = 0
                //       //メッシュ8桁目
                //       var keta5 =Number(String(mesh).substr(7,1))+1

                //       var mesh_northeast =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northeast =  meshcode2latlng.quater(Number(mesh_northeast));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_northeast.south,
                //           west: loc_northeast.west,
                //           north: loc_northeast.north+0.006248 ,
                //           east: loc_northeast.east+0.009375
                //         }
                //       });


                //     }

                //   }

                // //検索目的地南東のメッシュのみ色付け-①
                //   //南東のメッシュ指定するために、メッシュコードに-9する
                //   //メッシュコードでは、-9した際に桁が繰り下がると少し計算が違うので、まずは繰り下がらない範囲だけ計算
                //   if((String(Number(mesh)+1).substr(7,1))>0&&(String(Number(mesh)-10).substr(6,1))<9){
                //     const loc_southeast =  meshcode2latlng.quater(Number(mesh)-9);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_southeast.south,
                //         west: loc_southeast.west,
                //         north: loc_southeast.north+0.006248 ,
                //         east: loc_southeast.east+0.009375
                //       }
                //     });
                //   }
                //   //繰り下がったりする場合の処理
                //   else if((String(Number(mesh)+1).substr(7,1))<1||(String(Number(mesh)-10).substr(6,1))>8){
                //     //斜めの表示はちょっと面倒。全部で三段階あって、まずは東側も南側も繰り上がる下がる場合
                //     if((String(Number(mesh)+1).substr(7,1))<1&&(String(Number(mesh)-10).substr(6,1))>8){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))-1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))+1
                //       //メッシュ7桁目
                //       var keta4 =9
                //       //メッシュ8桁目
                //       var keta5 =0

                //       var mesh_southeast =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_southeast =  meshcode2latlng.quater(Number(mesh_southeast));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_southeast.south,
                //           west: loc_southeast.west,
                //           north: loc_southeast.north+0.006248 ,
                //           east: loc_southeast.east+0.009375
                //         }
                //       });
                //     }     

                //     //次に東側にのみ繰り上がる場合
                //     else if(String(Number(mesh)+1).substr(7,1)<1){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))+1
                //       //メッシュ7桁目
                //       var keta4 =Number(String(mesh).substr(6,1))-1
                //       //メッシュ8桁目
                //       var keta5 =0

                //       var mesh_southeast =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_southeast =  meshcode2latlng.quater(Number(mesh_southeast));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_southeast.south,
                //           west: loc_southeast.west,
                //           north: loc_southeast.north+0.006248 ,
                //           east: loc_southeast.east+0.009375
                //         }
                //       });
                //     }

                //     //最後に南側にのみ繰り下がる場合
                //     else if(String(Number(mesh)-10).substr(6,1)>8){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))-1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))
                //       //メッシュ7桁目
                //       var keta4 = 9
                //       //メッシュ8桁目
                //       var keta5 =Number(String(mesh).substr(7,1))+1

                //       var mesh_southeast =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_southeast =  meshcode2latlng.quater(Number(mesh_southeast));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_southeast.south,
                //           west: loc_southeast.west,
                //           north: loc_southeast.north+0.006248 ,
                //           east: loc_southeast.east+0.009375
                //         }
                //       });

                //     }
                //   }

                // //検索目的地北西のメッシュのみ色付け-①
                //   //北西のメッシュ指定するために、メッシュコードに+9する
                //   //メッシュコードでは、+9した際に桁が繰り下がると少し計算が違うので、まずは繰り下がらない範囲だけ計算
                //   if((String(Number(mesh)-1).substr(7,1))<9&&(String(Number(mesh)+10).substr(6,1))>0){
                //     const loc_northwest =  meshcode2latlng.quater(Number(mesh)+9);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_northwest.south,
                //         west: loc_northwest.west,
                //         north: loc_northwest.north+0.006248 ,
                //         east: loc_northwest.east+0.009375
                //       }
                //     });
                //   }
                //   //繰り下がったりする場合の処理
                //   else if((String(Number(mesh)-1).substr(7,1))>8||(String(Number(mesh)+10).substr(6,1))<1){
                //     //斜めの表示はちょっと面倒。全部で三段階あって、まずは西側も北側も繰り上がる下がる場合
                //     if((String(Number(mesh)-1).substr(7,1))>8&&(String(Number(mesh)+10).substr(6,1))<1){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))+1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))-1
                //       //メッシュ7桁目
                //       var keta4 =0
                //       //メッシュ8桁目
                //       var keta5 =9

                //       var mesh_northwest =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northwest =  meshcode2latlng.quater(Number(mesh_northwest));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_northwest.south,
                //           west: loc_northwest.west,
                //           north: loc_northwest.north+0.006248 ,
                //           east: loc_northwest.east+0.009375
                //         }
                //       });

                //     } 

                //     //次に西側にのみ繰り下がる場合
                //     else if(String(Number(mesh)-1).substr(7,1)>8){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))-1
                //       //メッシュ7桁目
                //       var keta4 =Number(String(mesh).substr(6,1))+1
                //       //メッシュ8桁目
                //       var keta5 =9

                //       var mesh_northwest =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northwest =  meshcode2latlng.quater(Number(mesh_northwest));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_northwest.south,
                //           west: loc_northwest.west,
                //           north: loc_northwest.north+0.006248 ,
                //           east: loc_northwest.east+0.009375
                //         }
                       
                //       });

                //     }

                //     //最後に北側にのみ繰り上がる場合
                //     else if(String(Number(mesh)+10).substr(6,1)<1){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))+1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))
                //       //メッシュ7桁目
                //       var keta4 = 0
                //       //メッシュ8桁目
                //       var keta5 =Number(String(mesh).substr(7,1))-1

                //       var mesh_northwest =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northwest =  meshcode2latlng.quater(Number(mesh_northwest));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_northwest.south,
                //           west: loc_northwest.west,
                //           north: loc_northwest.north+0.006248 ,
                //           east: loc_northwest.east+0.009375
                //         }
                //       });
                //     }
                //   }



                // //検索目的地北西のメッシュのみ色付け-①
                //   //南西のメッシュ指定するために、メッシュコードに-11する
                //   //メッシュコードでは、-11した際に桁が繰り下がると少し計算が違うので、まずは繰り下がらない範囲だけ計算
                //   if((String(Number(mesh)-1).substr(7,1))<9&&(String(Number(mesh)-10).substr(6,1))<9){
                //     const loc_southwest =  meshcode2latlng.quater(Number(mesh)-11);
                //     const rectangle = new google.maps.Rectangle({
                //       strokeColor: '#ff69b4',
                //       strokeWeight: 0.5,
                //       fillColor: '#0067c0',
                //       map: map,
                //       bounds: {
                //         south: loc_southwest.south,
                //         west: loc_southwest.west,
                //         north: loc_southwest.north+0.006248 ,
                //         east: loc_southwest.east+0.009375
                //       }
                //     });                    
                //   }
                //   else if((String(Number(mesh)-1).substr(7,1))>8||(String(Number(mesh)-10).substr(6,1))>8){
                //     //斜めの表示はちょっと面倒。全部で三段階あって、まずは西側も南側も繰り下がる場合
                //     if((String(Number(mesh)-1).substr(7,1))>8&&(String(Number(mesh)-10).substr(6,1))>8){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))-1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))-1
                //       //メッシュ7桁目
                //       var keta4 =9
                //       //メッシュ8桁目
                //       var keta5 =9

                //       var mesh_southwest =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_northwest =  meshcode2latlng.quater(Number(mesh_southwest));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_southwest.south,
                //           west: loc_southwest.west,
                //           north: loc_southwest.north+0.006248 ,
                //           east: loc_southwest.east+0.009375
                //         }
                //       });
                //     }    
                //     //次に西側にのみ繰り下がる場合
                //     else if(String(Number(mesh)-1).substr(7,1)>8){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))-1
                //       //メッシュ7桁目
                //       var keta4 =Number(String(mesh).substr(6,1))-1
                //       //メッシュ8桁目
                //       var keta5 =9

                //       var mesh_southwest =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_southwest =  meshcode2latlng.quater(Number(mesh_southwest));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_southwest.south,
                //           west: loc_southwest.west,
                //           north: loc_southwest.north+0.006248 ,
                //           east: loc_southwest.east+0.009375
                //         }
                       
                //       });

                //     }                  
                //     //最後に南側にのみ繰り下がる場合
                //     else if(String(Number(mesh)-10).substr(6,1)>8){
                //       //メッシュ1~4桁
                //       var keta1 = String(mesh).substr(0,4)  
                //       //メッシュ5桁目
                //       var keta2 = Number(String(mesh).substr(4,1))-1
                //       //メッシュ6桁目
                //       var keta3 = Number(String(mesh).substr(5,1))
                //       //メッシュ7桁目
                //       var keta4 = 9
                //       //メッシュ8桁目
                //       var keta5 =Number(String(mesh).substr(7,1))-1

                //       var mesh_southwest =keta1 +keta2 +keta3 +keta4+keta5
                //       var loc_southwest =  meshcode2latlng.quater(Number(mesh_southwest));
                //       var rectangle = new google.maps.Rectangle({
                //         strokeColor: '#ff69b4',
                //         strokeWeight: 0.5,
                //         fillColor: '#0067c0',
                //         map: map,
                //         bounds: {
                //           south: loc_southwest.south,
                //           west: loc_southwest.west,
                //           north: loc_southwest.north+0.006248 ,
                //           east: loc_southwest.east+0.009375
                //         }
                //       });
                //     }





                  // }      








                    // console.log("北東："+Number(loc.north+0.006248)+","+Number(loc.east+0.009375))
                    // console.log("北西："+Number(loc.north+0.006248)+","+loc.west)  
                    // console.log("南東："+loc.south+","+Number(loc.east+0.009375))
                    // console.log("南西："+loc.south+","+loc.west)

                    // const northeast_lat = Number(loc.north+0.006248);
                    // const northeast_lng =Number(loc.east+0.009375);

                    //北東から左下に入ってるか判定
                    // if ((data[0].lat < northeast_lat)&&(data[0].lng < northeast_lng)){
                    // }
                    // if(data[0].lat>)
                  


                  //マップにメッシュを追加※追加エリアは目的地をメッシュ上で下方向に2桁範囲内まで
                  for(mesh_r2=0;mesh_r2<10;mesh_r2++){
                    if(!(Number(mesh.substr(-2,1))-mesh_r2<0)){
                      var mesh_n=mesh.substr(0,7)+0
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r2)*-10+m;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                    }
                  }


                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の上のブロック
                  for(mesh_r3=0;mesh_r3<10;mesh_r3++){
                    var mesh_out_up=mesh.substr(0,4)+(Number(mesh.substr(4,1))+1)+mesh.substr(5,1)+0+mesh.substr(-1,1)
                      var mesh_n=mesh_out_up.substr(0,7)+0
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r3)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }

                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の左のブロック
                  for(mesh_r4=0;mesh_r4<10;mesh_r4++){
                    var mesh_out_left=mesh.substr(0,5)+(Number(mesh.substr(5,1))-1)+mesh.substr(6,1)+0
                      var mesh_n=mesh_out_left.substr(0,7)+0
    
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r4)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }


                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の左上のブロック
                  for(mesh_r5=0;mesh_r5<10;mesh_r5++){
                    var mesh_out_left_up=mesh.substr(0,4)+(Number(mesh.substr(4,1))+1)+(Number(mesh.substr(5,1))-1)+0+0
                      var mesh_n=mesh_out_left_up.substr(0,7)+0                      
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r5)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }


                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の左上のブロック
                  for(mesh_r6=0;mesh_r6<10;mesh_r6++){
                    var mesh_out_left_up=mesh.substr(0,4)+(Number(mesh.substr(4,1)))+(Number(mesh.substr(5,1))-1)+0+0
                      var mesh_n=mesh_out_left_up.substr(0,7)+0
 
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r6)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }

                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の下のブロック
                  for(mesh_r7=0;mesh_r7<10;mesh_r7++){
                    const mesh_out_under=mesh.substr(0,4)+(Number(mesh.substr(4,1))-1)+mesh.substr(5,1)+0+mesh.substr(-1,1)
                      const mesh_n=mesh_out_under.substr(0,7)+0
                      for(var m=0;m<10;m++){
                        const code = Number(mesh_n)+Number(mesh_r7)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }

                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の左下のブロック
                  for(mesh_r8=0;mesh_r8<10;mesh_r8++){
                    var mesh_out_left_under=mesh.substr(0,4)+(Number(mesh.substr(4,1)-1))+(Number(mesh.substr(5,1))-1)+0+0
                      var mesh_n=mesh_out_left_under.substr(0,7)+0
 
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r8)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }


                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の右のブロック
                  for(mesh_r9=0;mesh_r9<10;mesh_r9++){
                    var mesh_out_right=mesh.substr(0,4)+(Number(mesh.substr(4,1)))+(Number(mesh.substr(5,1))+1)+0+0
                      var mesh_n=mesh_out_right.substr(0,7)+0
 
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r9)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }

                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の右下のブロック
                  for(mesh_r10=0;mesh_r10<10;mesh_r10++){
                    var mesh_out_right_under=mesh.substr(0,4)+(Number(mesh.substr(4,1))-1)+(Number(mesh.substr(5,1))+1)+0+0
                      var mesh_n=mesh_out_right_under.substr(0,7)+0
 
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r10)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }

                  //マップにメッシュを追加※追加エリアはメッシュ上で目的地の右上のブロック
                  for(mesh_r11=0;mesh_r11<10;mesh_r11++){
                    var mesh_out_right_up=mesh.substr(0,4)+(Number(mesh.substr(4,1))+1)+(Number(mesh.substr(5,1))+1)+0+0
                      var mesh_n=mesh_out_right_up.substr(0,7)+0
 
                      for(var m=0;m<10;m++){
                        var code = Number(mesh_n)+Number(mesh_r11)*10+m;;
                        for(var i=0;i<4;i++){
                          var loc =  meshcode2latlng.quater(code);
                          var rectangle = new google.maps.Rectangle({
                            strokeColor: '#ff69b4',
                            strokeWeight: 0.5,
                            fillColor: '#ffffff00',
                            map: map,
                            bounds: {
                              south: loc.south,
                              west: loc.west,
                              north: loc.north+0.006248,
                              east: loc.east+0.009375
                            }
                          });
                        }
                      }
                  }








                  // // マップにメッシュを追加(中心+1東向き)
                  // for(var m3=0;m3<10-(mesh.substr(-2,1));m3++){
                  //   var code = Number(mesh)+m3*10;
                  //   for(var i=0;i<4;i++){
                  //     var loc =  meshcode2latlng.quater(code);
                  //     var rectangle = new google.maps.Rectangle({
                  //       strokeColor: '#ff0000',
                  //       strokeWeight: 0.5,
                  //       fillColor: '#ffffff00',
                  //       map: map,
                  //       bounds: {
                  //         south: loc.south,
                  //         west: loc.west,
                  //         north: loc.north+0.006248,
                  //         east: loc.east+0.009375
                  //       }
                  //     });
                  //   }
                  // };

                  // // マップにメッシュを追加(中心西向き)
                  // for(var m4=0;m4<mesh.substr(-2,1);m4++){
                  //   var code = Number(mesh)-m4*10;
                  //   for(var i=0;i<4;i++){
                  //     var loc =  meshcode2latlng.quater(code);
                  //     var rectangle = new google.maps.Rectangle({
                  //       strokeColor: '#ff0000',
                  //       strokeWeight: 0.5,
                  //       fillColor: '#ffffff00',
                  //       map: map,
                  //       bounds: {
                  //         south: loc.south,
                  //         west: loc.west,
                  //         north: loc.north+0.006248,
                  //         east: loc.east+0.009375
                  //       }
                  //     });
                  //   }
                  // };















                  

                  // マップにメッシュを追加(中心-1東向き)      
                  // for(var i2=0;i2<3;i2++){
                  //   var code = Number(mesh)-10+i2;
                  //   for(var i=0;i<4;i++){
                  //     var loc =  meshcode2latlng.quater(code);
                  //     var rectangle = new google.maps.Rectangle({
                  //       strokeColor: '#ff0000',
                  //       strokeWeight: 0.5,
                  //       fillColor: '#ffffff00',
                  //       map: map,
                  //       bounds: {
                  //         south: loc.south,
                  //         west: loc.west,
                  //         north: loc.north+0.00625,
                  //         east: loc.east+0.009375
                  //       }
                  //     });
                  //   }
                  // }

                  //   // マップにメッシュを追加(中心+1h東向き) 
                  //    for(var i4=0;i4<3;i4++){
                  //      var code = Number(mesh)+10+i4;
                  //      console.log(code)
                  //      for(var i=0;i<4;i++){
                  //        var loc =  meshcode2latlng.quater(code);
                  //        var rectangle = new google.maps.Rectangle({
                  //          strokeColor: '#ff0000',
                  //          strokeWeight: 0.5,
                  //          fillColor: '#ffffff00',
                  //          map: map,
                  //          bounds: {
                  //            south: loc.south-0.000002,
                  //            west: loc.west,
                  //            north: loc.north+0.00625,
                  //            east: loc.east+0.009375
                  //          }
                  //        });
                  //      }
                  //    }; 


                  //表示位置の定義(悪いゴミステーション)
                  const data = [
                    <?= $output ?>
                  ];
                  
                  //表示位置の定義(良いゴミステーション)
                  const data2 = [
                    <?= $output_good ?>
                  ];


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
                    
                    const contentstr = "<b>" + 
                    "最新調査日 : " + d.year + "年" +  d.month + "月" + d.day +  "日"+
                    "</b><br>" + 
                    "状態 : 悪い ( " + d.reason + " ) "+ 
                    "<br>" + 
                    '<img src=upload/' + d.image + " width=300>" +"<br>"+
                    '<img src=upload/' + d.image2 + " width=300>" +"<br>"+
                    '<img src=upload/' + d.image3 + " width=300>" +"<br>"
                    //' alt onerror="this.onerror = null; this.src=''' + 
                    
                    //'<p style="text-align:right;"> <a href="#">詳細なデータを見る</a>'
                                        

                    //クリックしたら情報を表示
                    const infoWindow = new google.maps.InfoWindow({
	      	            content:contentstr
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
; 
                  
                    const contentstr_good = "<b>" + 
                    "最新調査日 : " + d2.year + "年" +  d2.month + "月" + d2.day +  "日"+
                    "</b><br>" + 
                    "状態 : 良い"+ 
                    "<br>" + 
                    '<img src=upload/' + d2.image + " width=300>" +"<br>"+
                    '<img src=upload/' + d2.image2 + " width=300>" +"<br>"+
                    '<img src=upload/' + d2.image3 + " width=300>" +"<br>"

                  //クリックしたら情報を表示
                  const infoWindow = new google.maps.InfoWindow({
	      	          content:contentstr_good
        	        });

	                google.maps.event.addListener(marker2, 'click', function() { //マーカークリック時の動作
                    infoWindow.open(map, marker2); //情報ウィンドウを開く
                  });

                });


                        
                    })


                    // Ajaxリクエストが失敗した場合
                    .fail(function (XMLHttpRequest, textStatus, errorThrown) {
                      alert(errorThrown);
                    });


                  //取得した位置情報を中心に表示(ZOOMは禁止に)
                  let map;
                  map = new google.maps.Map(document.getElementById("map"), {
                    
                    center: {
                      lat:lat , lng: lng,
                    },

                    zoom: 14  ,
                    radius: 5,
                    // scrollwheel: false
                    
                  });







                // そもそも、ループを回して、検索結果にあっているものをiに入れていっているため
                // 精度の低いものもでてきてしまう。その必要はないから、一回でbreak               
                break;
                }
              }

            } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS){
              alert("住所が見つかりませんでした。");
            } 
            
            else if (status == google.maps.GeocoderStatus.ERROR){
              alert("サーバ接続に失敗しました。");
            }

            else if (status == google.maps.GeocoderStatus.INVALID_REQUEST) {
              alert("リクエストが無効でした。");
            }

            else if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
              alert("リクエストの制限回数を超えました。");
            }
            
            else if (status == google.maps.GeocoderStatus.REQUEST_DENIED) {
              alert("サービスが使えない状態でした。");
            }
            
            else if (status == google.maps.GeocoderStatus.UNKNOWN_ERROR) {
              alert("原因不明のエラーが発生しました。");
            }
          })           
           
        });

        
        // マップにメッシュを追加する素材
        (function (exports) {
          function sliceMeshcode(meshcode) {
            var p, u, q, v, r, w, m, n;
            meshcode = String(meshcode)            
            p = parseInt(meshcode.slice(0, 2));
            u = parseInt(meshcode.slice(2, 4));
            q = parseInt(meshcode.slice(4, 5));
            v = parseInt(meshcode.slice(5, 6));
            r = parseInt(meshcode.slice(6, 7));
            w = parseInt(meshcode.slice(7, 8));
            m = parseInt(meshcode.slice(8, 9));
            n = parseInt(meshcode.slice(9, 10));
            return { "p": p, "q": q, "r": r, "u": u, "v": v, "w": w, "m": m, "n": n };
          }

          exports.quater = function (meshcode) {
            var south, west, north, east;
            var lat, lng;
            var code = sliceMeshcode(meshcode);
            lat = code.p / 1.5 * 3600 + code.q * 5 * 60 + code.r * 30;
            lng = (code.u + 100) * 3600 + code.v * 7.5 * 60 + code.w * 45;
            south = lat + ((code.m > 2 ? (code.n > 2 ? ((code.n + code.m) > 5 ? 3 : 2) : 2) : (code.n > 2 ? 1 : 0))) * 7.5;
            north = lat + ((code.m > 2 ? (code.n > 2 ? ((code.n + code.m) > 5 ? 3 : 2) : 2) : (code.n > 2 ? 1 : 0)) + 1) * 7.5;
            west = lng + ((code.m % 2 == 0 ? (code.n % 2 == 0 ? ((code.n % 2 + code.m % 2) > 1 ? 3 : 2) : 2) : (code.n % 2 == 0 ? 1 : 0))) * 11.25;
            east = lng + ((code.m % 2 == 0 ? (code.n % 2 == 0 ? ((code.n % 2 + code.m % 2) > 1 ? 3 : 2) : 2) : (code.n % 2 == 0 ? 1 : 0)) + 1) * 11.25;
            return { "south": south / 3600, "west": west / 3600, "north": north / 3600, "east": east / 3600 };
          }

        })(typeof exports === 'undefined' ? this.meshcode2latlng = {} : exports);







    </script>

</body>

</html>