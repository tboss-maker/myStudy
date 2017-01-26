<?php
/**
 * author:zhaosuji
 * time: 2017/1/25  19:34
 **/
$a = array(
    199304,'199304AA','199304BB'
);
//最好必须要使用数字必须要转成字符串
//$b = array('199304');
$b = array(199304);
foreach($a as $k=>$p){
    if(in_array($p,$b)){
        unset($a[$k]);
    }
}

//问题就是在in_array()中用于比对的数组元素,必须要是字符串,因为会进行自动转换
print_r($a);
