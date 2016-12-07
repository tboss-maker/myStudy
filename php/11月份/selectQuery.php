<?php
/**
 * author:zhaosuji
 * time: 2016/11/24  10:06
 **/
//查询条件数组组装(数组之前考虑去空)
$query['name'] = 'lion';
$query['age'] = 24;
$query['money'] = 500;
$where = '';
foreach($query as $field=>$value){
    //或条件
    $where .= " {$field} = '{$value}' ||";
    //并且条件
//    $where .= " {$field} = '{$value}' &&";
}
//去除多余字符
$where = rtrim($where,'||');
echo $where;



