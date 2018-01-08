<?php
  
$file = realpath('/Users/lixue/wepiao/wxadmin/uploads/message/cache/user.txt'); //要上传的文件    
$fields['f'] = '@'.$file; // 前面加@符表示上传图片   
print_r($fields);die;
  
$ch =curl_init();  
  
  
curl_setopt($ch,CURLOPT_URL,'http://localhost:8080/ceshi/upload.php');  
  
curl_setopt($ch,CURLOPT_POST,true);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);  
  
  
$content = curl_exec($ch);  
echo $content;
