<?php
/**
 * author:zhaosuji
 * time: 2016/12/2  17:09
 **/
//冒泡排序
function bubleSort($arr){
    $len = count($arr);
    if ($len<=1){
        return $arr;
    }
    for($i=0;$i<$len;$i++){
        for($j=$i+1;$j<$len;$j++){
            if($arr[$i]>$arr[$j]){
                $temp = $arr[$i];
                $arr[$i] = $arr[$j];
                $arr[$j] = $temp;
            }
        }
    }
    return $arr;
}
$arr = [2,1,7,8,4,3,2,11,21];
echo '<pre>';
print_r(bubleSort($arr));
