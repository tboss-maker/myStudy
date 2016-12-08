<?php
/**
 * Author    : zhaosuji
 * Data      : 2016/12/8
 * Time      : 22:54
 **/

//一个关联数组,根据条件,合并其中的某些元素的值
//多币种
$arr = array(
    array('currency'=>'RMB','money'=>500),
    array('currency'=>'CNY','money'=>300),
    array('currency'=>'SPAN','money'=>100)
);

function merge_by_query($arr){
    $temp = 0;
    foreach($arr as $key=>$value){
        if($value['currency']=='RMB'){
            $temp += $value['money'];
            unset($arr[$key]);
        }
        if($value['currency']=='CNY'){
            $temp += $value['money'];
            unset($arr[$key]);
        }
    }
    $arr[] = array('currency'=>'RMB','money'=>$temp);
    return $arr;
}

echo '<pre>';
print_r(merge_by_query($arr));
