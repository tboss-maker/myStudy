<?php
/**
 * author:zhaosuji
 * time: 2016/12/2  17:17
 **/
//快速排序
function QuickSort($arr){
    $len = count($arr);
    if($len<=1){
        //递归出口
        return $arr;
    }
    $mid = $arr[0];
    $left = array();
    $right = array();
    for($i=1;$i<$len;$i++){
        //需要注意:$i从1开始,因为已经取出一个作为对比值
        if($arr[$i]<=$mid){
            $left[] = $arr[$i];
        }else{
            $right[] = $arr[$i];
        }
    }
    //递归
    $left = QuickSort($left);
    $right = QuickSort($right);
    return array_merge($left,[$mid],$right);
}

$arr = [6,4,3,8,3,66,1,3,2,2,8];
echo '<pre>';
print_r(QuickSort($arr));
