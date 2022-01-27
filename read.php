<?php
// DB接続
include("functions.php");
session_start();
//セッションチェック
check_session_id();

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
  },
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
    score: {$record_good["score"]}
  },
  ";
}


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
  
</head>

<body>

  <h1>治安Easy!!! (閲覧)</h1>

    <!-- ユーザー名表示 -->

    <div style="text-align:right">
      <?= $_SESSION["username"] ?> 様　<a href="logout_user.php">ログアウトする</a>
    </div>

    <!-- 住所入力 -->
    <!-- <form action="read_search_act.php" method="POST"> ※POSTとjqueryのonclick両方同時にできる方法ないかなあ-->
      <div>
        <span>住所を入力してください。</span><br>
        <input type="text" id="addressInput" placeholder="西鉄平尾駅" name="keyword">
        <button id="searchGeo">検索</button>
      </div>
    <!-- </form> -->

    <div>
        <input type="hidden" id="lat" name="lat_geo">
        <input type="hidden" id="lng" name="lng_geo">
    </div>
    

    <div id="map" style="width:100%;height:650px;margin-top:10px"></div>


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

      //表示位置の定義(悪いゴミステーション)
      const data = [
        <?= $output ?>
      ];
       
      //表示位置の定義(良いゴミステーション)
      const data2 = [
        <?= $output_good ?>
      ];

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
          var addressInput = document.getElementById('addressInput').value;

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
                  console.log(lat+","+lng)
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





                  
                  let map;
                  map = new google.maps.Map(document.getElementById("map"), {
                    
                    center: {
                      lat:lat , lng: lng,
                    },

                    zoom: 15  ,
                    radius: 5,
                  });

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
                      console.log(mesh_out_left_up)
                      
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
	      	            content:"調査日:"+d.year+"年"+d.month+"月"+d.day+"日"+"<br>"+"状態:悪い"+"<br>"+"理由:"+d.reason+"<br>"+"<img src="+d.image+" height='150px'>"
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
	      	          content:"調査日:"+d2.year+"年"+d2.month+"月"+d2.day+"日"+"<br>"+"状態:良い" //情報ウィンドウのテキスト
        	        });

	                google.maps.event.addListener(marker2, 'click', function() { //マーカークリック時の動作
                    infoWindow.open(map, marker2); //情報ウィンドウを開く
                  });

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