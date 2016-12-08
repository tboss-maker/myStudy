<?php
/**
 * author:zhaosuji
 * time: 2016/12/6  11:31
 **/
$arr = [1,2,3,4,5,6,7,8];

//function findMax($arr){
//    if(count($arr)<=1){
//        return $arr[0];
//    }
//    $max = $arr[0];
//    foreach($arr as $k=>$value){
//        if($max<$arr[$k]){
//             $max=$arr[$k];
//        }
//    }
//    return $max;
//}
//最大值
function findMax($arr){
   $max = $arr[0];
    for($i=0;$i<count($arr);$i++){
        if($max<$arr[$i]){
            $max=$arr[$i];
        }
    }
    return $max;
}

//最小值
function findMin($arr){
    $min = $arr[0];
    for($i=0;$i<count($arr);$i++){
        if($min>$arr[$i]){
            $min = $arr[$i];
        }
    }
    return $min;
}

//数组反转
function revergeArr($arr){
    $len = count($arr);
    for($i=0;$i<$len/2;$i++){
        $temp = $arr[$i];
        $arr[$i] = $arr[$len-1-$i];
        $arr[$len-1-$i] = $temp;
    }
    return $arr;
}

$arr = [
    [1,2,3],
    [4,5,6],
    [7,8,9]
];

foreach($arr as $value){
    foreach($value as $v){
        echo $v."\r\n";
    }
}
//echo '<pre>';
//print_r(revergeArr($arr));
//echo findMin($arr);

