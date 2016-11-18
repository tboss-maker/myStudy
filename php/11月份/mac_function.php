<?php
/**
 * author:zhaosuji
 * time: 2016/11/18  12:57
 **/

/**
 * mac地址处理
 * @param $mac
 * @param int $mac_len默认12位
 * @retrun mixed bool/string 处理失败返回false;成功返回正确格式的mac地址12位的16进制(例如:11-11-11-11-11-11)
 *
 */
function mac_format_deal($mac,$mac_len=12){
    //首先过滤掉-,:,空格
    $first = array(' ',':','-',"\t","\n","\r");
    $map = array('','','','','','');
    $mac = str_replace($first,$map,$mac);

    //开始验证mac是否合法
    $patten = "/[g-zG-Z\'.,:-;*\/?\"~`\[!\]\|@#$%^&+=\\\)(<>{}]/";
    //是否包含特殊字符(键盘上面的),包含则直接返回错误
    if(preg_match($patten,$mac)){
        return false;
    }

    //mac地址长度不符合指定长度(不包含-等符号的纯字符串长度)
    if(strlen($mac)!=$mac_len){
        return false;
    }

    //对字符串进行规范化处理(每两位字符之间，增加-)
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
$a = '123+';
$patten = "/[g-zG-Z\'.,:-;*\/?\"~`\[!\]\|@#$%^&+=\\\)(<>{}]/";
if (preg_match($patten,$a)){
    echo '有错误字符';
}else{
    echo '没有错误字符';
};
//$a='1111111G-1:1-1-1';
//$a = mac_format_deal($a);
//if(!$a){
//    echo '失败';exit;
//}
//echo $a;
