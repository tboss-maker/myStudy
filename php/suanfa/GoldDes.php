<?php
/**
 * Author    : zhaosuji
 * Data      : 2016/12/11
 * Time      : 16:03
 **/
//从第三个数开始,每个数是前两个数的和(黄金分割问题)
//1,1,2,3,5,8,13

//兔子问题(获取某个月的兔子数量)
function getRubByMonth($month){
    $fir = 1;
    $sec = 1;
    //从第三个月开始
    for($i=3;$i<=$month;$i++){
        //保存前一个月值
        $temp = $sec;
        //后一个月的值等于前两个月值
        $sec = $fir+$sec;
        //前两个月值重新赋值
        $fir = $temp;
    }
    //返回当月的兔子数量
    return $sec;
}

//兔子问题(获取到某个月位置的所有兔子数量)
function getTotalRub($month){
    $fir = 1;
    $sec = 1;
    $total = 2;
    //特殊情况(如果只算第一个月的数量)
    if($month<=1){
        $total=1;
    }
    for($i=3;$i<=$month;$i++){
        $temp = $sec;
        $sec = $fir+$sec;
        $fir = $temp;
        $total += $sec;
    }
    return $total;
}
echo getRubByMonth(6).'<br/>';
echo getTotalRub(1);






