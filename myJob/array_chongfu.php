<?php
/**
 * author:zhaosuji
 * time: 2017/2/7  21:08
 **/

$a = array(
    array('nns_id'=>1,'money'=>500,'demo'=>'aaa'),
    array('nns_id'=>2,'money'=>500,'demo'=>'CCC'),
    array('nns_id'=>1,'money'=>500,'demo'=>'BBB'),
    array('nns_id'=>2,'money'=>500,'demo'=>'DDD'),
    array('nns_id'=>2,'money'=>500,'demo'=>'JJJ'),
    array('nns_id'=>3,'money'=>500,'demo'=>'YYY'),
    array('nns_id'=>4,'money'=>500,'demo'=>'UUU'),
);

//array(
//    array('nns_id'=>1,'money'=>500,'demo'=>'aaa,BBB'),
//    array('nns_id'=>2,'money'=>500,'demo'=>'CCC,DDD,JJJ'),
//    array('nns_id'=>3,'money'=>500,'demo'=>'YYY'),
//    array('nns_id'=>4,'money'=>500,'demo'=>'UUU'),
//);
$check_ids = [];
foreach($a as $value){
    if(!in_array($value['nns_id'],$check_ids)){
        $check_ids[] = $value['nns_id'];
        $temp[$value['nns_id']] = $value;
    }else {
        $temp[$value['nns_id']]['demo'] .= ','.$value['demo'];
    }
}
print_r($temp);