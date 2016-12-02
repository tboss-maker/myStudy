<?php
/**
 * author:zhaosuji
 * time: 2016/12/2  17:44
 **/
//顺序查找
function normalSearch($arr,$key){
    $len = count($arr);
    if($len<=1){
        return $arr[0];
    }
    for($i=0;$i<$len;$i++){
        if($arr[$i]==$key){
            return 1;
        }
    }
    return -1;
}

//二分查找(必须是有序表)
function binsearch($x,$a){
    $c=count($a);
    $lower=0;
    $high=$c-1;
    while($lower<=$high){
        $middle=intval(($lower+$high)/2);
        if($a[$middle]>$x)
            $high=$middle-1;
        elseif($a[$middle]<$x)
            $lower=$middle+1;
        else
            return $middle;
    }
    return -1;
}

$arr = [1,3,2,5,4];
echo normalSearch($arr,11);
