<?php
/**
 * author:zhaosuji
 * time: 2016/11/10  11:25
 * 将块文件读入新数据库中，异常数据生成写入失败文件
 **/

header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 1);
error_reporting(E_ALL);
//set_time_limit(7200);

//载入文件
require 'migrate_common.php';
$product_dir = dirname(dirname(dirname(__FILE__)));
include $product_dir . '/nn_logic/nl_common.func.php';
////include $product_dir . '/nn_logic/nl_log_v2.func.php';
//include $product_dir . '/nn_logic/common/v2/global.include.php';
//include $product_dir . '/nn_logic/scaaa/core/c_device_v2.class.php';
//include $product_dir . '/nn_logic/scaaa/core/c_user_v2.class.php';

//$module_name = 'timer_write_new_db';
//nl_log_v2_init_epg($module_name);
//nl_log_v2_info($module_name, '脚本开始');

// 连接AAA数据库
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NP_KV_CACHE_TYPE_MEMCACHE
));

//获取offset
$offset = get_offset();
//判断是否需要写入
//if(!$offset){
//    die('无数据需要写入,请确认!');
//}
//获取数据文件前缀
$file_pre = get_config_param('data_file_pre');
//获取AAA对应表名
$table_name = get_config_param('table');
//拼凑文件路径
$file_name = $file_pre.$table_name.$offset.'.txt';

$file_name = 'data.txt';
//获取并解析需要插入的数据
$insert_datas = file_get_contents($file_name);
$insert_datas = json_decode($insert_datas,true);

//获取插入条数配置
$insert_size = get_config_param('insert_size');
//获取插入sql
$insert_sql = get_config_param('insert_sql');

//将数据按照插入条数配置进行分割
$insert_data = array_chunk($insert_datas,$insert_size);
//$insert_data = array_chunk($insert_datas,2);

for($i=0;$i<count($insert_data);$i++){
    $insert_values ='';

    foreach($insert_data[$i] as $value){
        $insert_values .= '('.implode(',',$value).'),';
    }
    $insert_values = rtrim($insert_values,',');

    //生成插入语句
    $sql = $insert_sql.$insert_values.';';
    $mysql = new mysqli('localhost','root','','test');
    $result = $mysql->query($sql);
    //$result = nl_c_device_v2::add_device_shyd($aaa_dc, $params);
    if(!$result){
//        //写入日志
//        nl_log_v2_info($module_name, '数据库同步失败,失败的sql是:'.$sql);
        //写入错误文件
        make_file_dir($table_name,'error');
    }
}
//nl_log_v2_info($module_name, '脚本结束');
die('文本写入结束,请去查看日志!');



