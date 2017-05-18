<?php
/**
 * author:zhaosuji
 * time: 2016/12/9  11:14
 **/

$money = array(
    array('nns_currency_type' => 'CNY', 'total_money' => 500),
    array('nns_currency_type' => '', 'total_money' => 100),
    array('nns_currency_type' => 'RMB', 'total_money' => 200),
    array('nns_currency_type' => 'EUR', 'total_money' => 400),
    array('nns_currency_type' => 'USD', 'total_money' => 500)
);

$money1 = array();

$type = array(
    'RMB' => array('icon' => '￥', 'default' => 0, 'desc' => '人民币'),
    'USD' => array('icon' => '$', 'default' => 1, 'desc' => '美元'),
    'EUR' => array('icon' => '€', 'default' => 0, 'desc' => '欧元'),
);

//处理多币种(合并CNY,RMB,)
function deal_multi_currency_count($money_arr, $type)
{
    //处理统计数据为空,或者不正确的情况
    if (!$money_arr || $money_arr === true) {
        $error_string = '￥0';
        return $error_string;
    }
    $temp = 0;
    //将币种中CNY和RMB合并
    foreach ($money_arr as $key => $value) {
        if ($value['nns_currency_type'] == 'CNY') {
            $temp += $value['total_money'];
            unset($money_arr[$key]);
        }
        if ($value['nns_currency_type'] == 'RMB') {
            $temp += $value['total_money'];
            unset($money_arr[$key]);
        }
    }
    $money_arr[] = array('nns_currency_type' => 'RMB', 'total_money' => $temp);
    //获取多币种信息
//    $type = get_config_v2('g_currency_type');

    //默认币种名称
    $default_type = '';
    foreach ($type as $k2 => $v2) {
        //获取默认币种名称
        if ($v2['default'] == 1) {
            $default_type = $k2;
        }
    }

    //默认币种金额
    $default_money_temp = 0;
    //将空元素做默认币种处理
    foreach ($money_arr as $k => $v) {
        if ($v['nns_currency_type'] == '') {
            $default_money_temp += $v['total_money'];
            unset($money_arr[$k]);
        }
        if ($v['nns_currency_type'] == $default_type) {
            $default_money_temp += $v['total_money'];
            unset($money_arr[$k]);
        }
    }
    $money_arr[] = array('nns_currency_type' => $default_type, 'total_money' => $default_money_temp);

    //缓存数组
    $temp_arr = array();
    //币种将单位转化成图标
    foreach ($money_arr as $k => $v) {
        foreach ($type as $k2 => $v2) {
            if ($v['nns_currency_type'] == $k2) {
                $v['nns_currency_type'] = $v2['icon'];
            }
        }
        //放入缓存数组
        $temp_arr[] = $v;
    }
    $string = '';
    $money_arr = $temp_arr;
    foreach($money_arr as $v){
        $string .= implode('',$v).' | ';
    }
    $string = trim($string," | ");
    return $string;
}

echo '<pre>';
//print_r(deal_multi_currency_count($money, $type));

echo deal_multi_currency_count($money,$type);
//echo deal_multi_currency_count($money1,$type);