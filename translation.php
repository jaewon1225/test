<html>
<head>
</head>
<body>
<?php
//이미지 -> 텍스트 변환
  $filename =  date("YmdHis").".jpg";
  move_uploaded_file($_FILES['imageform']['tmp_name'], $filename);

  $client_secret = "T1hYWWZkV2lKdmh0TUlrVWRJUWRHakpVWnhZVWRoSVo=";
  $url = "https://1464f1962ec246f78d43a81570f890f4.apigw.ntruss.com/custom/v1/2227/03d0fe469502affac6c2f54393e8beec2aa98d871cffb9bf9f696aceddf62dac/general";
  //$image_url = "YOUR_IMAGE_URL";
  $image_file = $filename;

  $params->version = "V2";
  $params->requestId = "uuid";
  $params->timestamp = time();
  $image->format = "jpg";
  //$image->url = $image_url;
   $image->data = base64_encode(file_get_contents($image_file));
  $image->name = "demo";
  $images = array($image);
  $params->images = $images;
  $json = json_encode($params);

  $is_post = true;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, $is_post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
  $headers = array();
  $headers[] = "X-OCR-SECRET: ".$client_secret;
  $headers[] = "Content-Type:application/json; charset=utf-8";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  $err = curl_error($ch);
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close ($ch);

  //echo $status_code;

  if($status_code == 200) {
    
    echo $response."<br>\n";
    echo "---------------------------------<br>\n";

    $arr = json_decode($response, true);
    print_r($arr);
    echo "<br>\n";
    echo "---------------------------------<br>\n";
  
    function flatten($l, $result = []){
          foreach ($l as $value) {
              if(is_array($value)) {
                  $result = flatten($value, $result);
              } else {
                  $result[] = $value;
              }
          }
          return $result;
    }

  $result = flatten($arr);
  $result2 = [];
  
  $j=count($result);
  //echo $result[17];
  $k = 0;

  echo "이미지에서 변환된 텍스트 : ";
  for($i=17; $i<$j; $i=$i + 12){
    echo $result[$i]." ";
    $result2[$k] = $result[$i];
    $k++;
  }

  } else {
    echo "ERROR: ".$response;
  }

  //번역
  $g = count($result2);

  $api_key = 'AIzaSyDVGM-H4bVy9eRPnTSz0wPB_82eXhbbqW0';
  
  for($i = 0; $i <= $g; $i++){
    $text = $text.$result2[$i]." ";
  }

  $source="en";
  $target="ko";
  
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&q=' . rawurlencode($text);
  $url .= '&target='.$target;
  $url .= '&source='.$source;
  
  $response = file_get_contents($url);
  $obj =json_decode($response,true); //true converts stdClass to associative array.
  if($obj != null)
  {
      if(isset($obj['error']))
      {
          echo "Error is : ".$obj['error']['message'];
      }
      else
      {
          echo "<br> 번역완료 : ".$obj['data']['translations'][0]['translatedText'];
      }
  }
  else
      echo "UNKNOW ERROR";

?>
</body>
</html>

