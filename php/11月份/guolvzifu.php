<?php
/**
 * author:zhaosuji
 * time: 2016/11/17  16:39
 **/
//判断是否存在特殊字符
$patten = "/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
$a = '12345';
if(preg_match($patten,$a)){
    echo '存在特殊字符';
}else{
    echo '不存在特殊字符';
}

//使用str_replace替换字符
//需要被替换的字符(可以是数组)
$is_replace = ['-','+','*'];
//需要替换成的字符
$replace = ['','',''];
//目标字符串
$target = '1-3-+5';
$result = str_replace($is_replace,$replace,$target);
echo $result;
