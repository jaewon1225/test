<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
$api_key = 'AIzaSyDVGM-H4bVy9eRPnTSz0wPB_82eXhbbqW0';
$text =  $_POST[text1];
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
        echo "Translsated Text: ".$obj['data']['translations'][0]['translatedText'];
    }
}
else
    echo "UNKNOW ERROR";
 
?>
