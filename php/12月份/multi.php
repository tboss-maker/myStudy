<?php
/**
 * Author    : zhaosuji
 * Data      : 2016/12/24
 * Time      : 0:38
 **/
//mysql事务处理
$mysql = new mysqli("localhost",'root','root','test','3306');
$sql1 = "begin";
$sql2 = "insert into user(`name`,`mobile`) values('jim','110')";
$sql3 = "insert into user(`name`,`mobile`) values('tom','120')";
$ret1 = $mysql->query($sql1);
$ret2 = $mysql->query($sql2);
$ret3 = $mysql->query($sql3);
if(!$ret1 || !$ret2 || !$ret3){
    $sql = "rollback";
}else{
    $sql = "commit";
}
$mysql->query($sql);
//不推荐采用multi_query()方法,因为multi_query只会监控第一条出错的sql,而不会监控所有,并且对于事务不能灵活支持


