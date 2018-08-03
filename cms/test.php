<?php
/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-6
 * Time: 下午2:27
 */

//$_url = 'http://cdn.lelai.com/cdn_file_receiver.php';
//
//$data = array(
//    'file' =>  new \CURLFile("/home/wangyang/Desktop/123.jpg"),
//    'entity' => 'merchant',
//    'return_size' => false,
//);
//
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $_url);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$result = curl_exec($ch);
//curl_close($ch);
//print_r($result);
$a = array();
var_dump(json_decode(json_encode(''),true));
echo PHP_EOL;
if(!empty(json_encode(''))){
    echo json_encode('');
}else{
    echo 2;
};