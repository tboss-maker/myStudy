<?php
/**
 * author:zhaosuji
 * time: 2016/11/24  10:06
 **/
//查询条件数组组装(数组之前考虑去空)
$query['name'] = '1';
$query['age'] = "";
$query['money'] = '';
/**
 * 组合删选条件
 */
function get_query_string($query_arr){
    $where = '';
    foreach($query_arr as $field=>$value){
        //或条件
//        $where .= " {$field} = '{$value}' ||";
        if(empty($value)){
            continue;
        }
        //并且条件
        $where .= " {$field} = '{$value}' &&";
    }
    //去除多余字符
    $where = rtrim($where,'&&');
    if(empty($where)){
        //如果查询条件为空
        $where = '';
    }else{
//        $where = 'where '.$where;
        $where = 'and '.$where;
    }
    return $where;
}



