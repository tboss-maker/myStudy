<?php
/**
 * author:zhaosuji
 * time: 2016/11/29  9:19
 **/
/**
 * 过滤二维数组打印数据
 * @param $src_data
 * @param $allow_key
 */
$arr = [
    ['a'=>1,'b'=>2,'c'=>3],
    ['a'=>4,'b'=>5,'c'=>6]
];
$allow = ['a','c'];
function filter_two_print_data($src_data,$allow_key=array()){
    if(!$src_data){
        return false;
    }
    foreach($src_data as $key=>$value){
        $item = array();
        foreach($allow_key as $k){
            $item[$k] = $value[$k];
        }
        $print_data[] = $item;
//        $print_data[$key] = $item;
    }
    return $print_data;
}
echo '<pre>';
print_r(filter_print_data($arr,$allow));

/**
 * 过滤一维数组打印数据
 */
function filter_one_print_data($src_data,$allow_key=array()){
    if(!$src_data){
        return false;
    }
    $print_data = array();
    foreach($src_data as $key=>$value){
        if(!in_array($key,$allow_key)){
            continue;
        }
        $print_data[$key] = $value;
    }
    return $print_data;
}
