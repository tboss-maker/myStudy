<?php
/**
 * author:zhaosuji
 * time: 2017/3/21  20:44
 **/
$xml='<?xml version="1.0" encoding="UTF-8"?> 
<message module="AAA" method="webtvStbSync" version="1.0">
  <loginID>userTest006</loginID>
  <stbID>ZSJ007990108080001CB3828ET04016Y</stbID>
  <status>1</status>
  <userid>userTest006</userid>
  <provinceID>20</provinceID>
  <timestamp>20170322030260</timestamp>
  <mac>08A5HOE72OC8</mac>
  <cityCode></cityCode>
  <presubTime>0</presubTime>
</message>
';
//要发送的xml
$url = "http://imgotvepg.itv.cmvideo.cn:58097/nn_boss/nb_api/huawei/aaa_api.php?webtvStbSync.php";
//$header = array('Content-type:text/xml');//定义content-type为xml
$ch = curl_init(); //初始化curl
curl_setopt($ch, CURLOPT_URL, $url);//设置链接
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);//POST数据
$response = curl_exec($ch);//接收返回信息
if(curl_errno($ch)){//出错则显示错误信息
    print curl_error($ch);
}
curl_close($ch); //关闭curl链接
echo $response;//显示返回信息