<?php
/**
 * author:zhaosuji
 * time: 2016/11/18  14:03
 **/
//redis储存数组
//核心:将数组转码(json_encode)
$target_arr = array('name'=>'jim','age'=>20,'money'=>500);
$redis = new Redis();
$redis->pconnect('127.0.0.1','6379');
$target_arr = json_encode($target_arr);
//将数组转成json,然后存储
$redis->set('test',$target_arr);

//从缓存中获取数组(使用json_decode)
$temp = $redis->get('test');
$result = json_decode($temp,true);
echo '<pre>';
print_r($result);