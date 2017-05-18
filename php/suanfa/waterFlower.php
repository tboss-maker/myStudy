<?php
/**
 * Author    : zhaosuji
 * Data      : 2016/12/12
 * Time      : 21:11
 **/

//打印水仙花数
function getFlower(){
    $target = array();
    for($t=1;$t<10;$t++){
        for ($h=0;$h<10;$h++){
            for($o=0;$o<10;$o++){
                $num = pow($t,3)+pow($h,3)+pow($o,3);
                if($t*100+$h*10+$o==$num){
                    $target[] = $num;
                }
            }
        }
    }
    return $target;
}

echo '<pre>';
print_r(getFlower());
