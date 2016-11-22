<?php
/**
 * author:zhaosuji
 * time: 2016/11/17  16:26
 **/

function check_mac_format($mac){
    //正则匹配非法字符
$patten = "/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
//是否包含特殊字符,包含则直接返回错误
if(preg_match($patten,$mac)){
    return false;
}
//mac地址需要满足8位(已经兼容为空的情况)
if(strlen($mac)!=12 || $mac=='00000000'){
    return false;
}
//满足过滤条件,返回mac
return $mac;
}

function trim_all($str)
{
    $pre = array(" ","　",":","-","\t","\n","\r");
    $back =array("","","","","","","");
    return str_replace($pre,$back,$str);
}
function get_right_mac($mac)
{
    // 每两位字符之间，增加-
    $mac_two_str = str_split($mac, 2);
    $tmp_mac = '';
    foreach ($mac_two_str as $value)
    {
        if (empty($tmp_mac))
        {
            $tmp_mac = $value;
        }
        else
        {
            $tmp_mac .= '-' . $value;
        }
    }
    return $tmp_mac;
}
$a = '11%11-111-11:1 1-1';
$a = trim_all($a);
$a = check_mac_format($a);
if(!$a){
    echo '不合法!';exit;
}
$a = get_right_mac($a);
echo $a;


