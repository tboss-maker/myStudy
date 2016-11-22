<?php
/**
 * author:zhaosuji
 * time: 2016/11/19  20:33
 **/
//遇到查询条件很多,并且有限制时
function query_something($query1,$query2){
    $query_contion1 = '';
    $query_contion2 = '';
    if($query1=1){
        $query_contion1 .= '';
    }else{
        $query_contion1 .= 'where name=1 ';
    }

    if($query2=2){
        $query_contion2 .= '';
    }else{
        $query_contion2 .= 'and where age=3';
    }

    $query = $query_contion1.$query_contion2;
    $sql = "select * from table_name {$query}";
    return $sql;
}
