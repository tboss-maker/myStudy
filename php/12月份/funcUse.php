<?php
/**
 * author:zhaosuji
 * time: 2016/12/2  16:34
 **/
//方法参数引用,可以实现闭包
function quote(&$name,&$age,&$money){
    $name = 'jim';
    $age =  24;
    $money = 500;
}
quote($name,$age,$money);
echo $name;

