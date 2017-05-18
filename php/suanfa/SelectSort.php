<?php
/**
 * author:zhaosuji
 * time: 2016/12/2  17:26
 **/
//选择排序(本质和冒泡一样)
function SelectSort($arr){
    $len = count($arr);
    if($len<=1){
        return $arr;
    }
    for($i=0;$i<$len;$i++){
        $min = $i;
        for($j=$i+1;$j<$len;$j++){
            if($arr[$min]>$arr[$j]){
                //如果不是最小,则重新赋值
                $min = $j;
            }
        }
        if($min!=$i){
            $temp = $arr[$min];
            $arr[$min] = $arr[$i];
            $arr[$i] = $temp;
        }
    }
    return $arr;
}

$arr = [6,4,8,3,2,8,9,5,1,4,3];
echo '<pre>';
print_r(SelectSort($arr));
//var_dump(SelectSort($arr),false);
