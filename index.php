<?php
$url = 'https://storage.cloud.google.com/image-trans-12/test.jpg';
 
$output_dir = "uploads/";
if(isset($_FILES["file"]))
{
 
    $fileName = md5(date('Y-m-d H:i:s:u')).$_FILES["file"]["name"]; //unique filename
 
    //move the file to uploads folder
    move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$fileName);
     
     
    //multipart form post using CURL
    $filePath = realpath($output_dir.$fileName);
    $post = array('apikey' => 'AIzaSyDVGM-H4bVy9eRPnTSz0wPB_82eXhbbqW0',
                    'mode' => 'document_photo',
                    'file' =>'@'.$filePath);
         
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result=curl_exec ($ch);
    curl_close ($ch);
    echo $result;
 
    /*
    //If you want to return only text use this.
    $json = json_decode($result,true);
    if($json && isset($json['text_block']))
    {
        $textblock =$json['text_block'][0];
        echo $textblock['text'];
    }*/
     
    //remove the file
    unlink($filePath);
 }
?>
