<?php
/**
 *.描述：上海移动nb_boss脚本存量数据同步
 * author: kang.lu
 * 创建时间: 2016/11/10
 */
header("Content-type:text/html;charset=utf-8");
$cms_dir = dirname(dirname(dirname(__FILE__)));
include_once $cms_dir . '/nn_logic/nl_common.func.php';
include_once $cms_dir . '/nn_logic/common/v2/global.include.php';
include_once "shyd_common.php";

// 连接AAA数据库
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NL_REDIS_WRITE
));
$aaa_redis = $aaa_dc->aaa_redis();
if(!$aaa_redis->exists(LIMIT_REDIS_KEY)){
    die('数据同步已完成或者未运行初始化脚本,请确认!');
}
$limit_start = get_offset($aaa_redis);
if (!$limit_start && $limit_start!='0') {
    die('获取查询的起始地址失败');
}
$limit = array($limit_start, LIMIT_SIZE);
//$limit = array(0,2);

//第三方数据库配置
//$nboss_mysql_config = array(
//    'host' => '127.0.0.1',
//    //端口
//    'port' => '3306',
//    //用户名
//    'username' => 'root',
//    //用户密码
//    'userpass' => '',
//);

//nboss数据库的mysql配置
$nboss_mysql_config = array(
    'host' => '192.168.95.55',
    //端口
    'port' => '3306',
    //用户名
    'username' => 'nn_cms',
    //用户密码
    'userpass' => 'nn_cms1234',
);

$src_conn = get_db_connect($nboss_mysql_config);
//第三方device查询
$device_query_sql = 'select device.nb_device_id,device.nb_mac,device.nb_user_id,device.nb_create_time,device.nb_state,record.nb_licence_id,record.nb_reg_ip,record.nb_setup_addr,record.nb_modify_time,record.nb_disable from nb_device as device left join nb_device_record as record on device.nb_device_id=record.nb_device_id';
//查询device的sql
$device_sql = $device_query_sql . " limit {$limit[0]},{$limit[1]}";
$device_res = query_src_mysql($device_sql, $src_conn);
is_error_exit($device_res, $device_sql, $limit, 'nb_device');

//开始循环处理查询的设备数据，转换后导入AAA库
foreach ($device_res as $key => $device_info) {
    //nb_state重新定义
    if ($device_info['nb_state'] == '1') {
        $insert_device_info['nns_state'] = 0;
    } elseif ($device_info['nb_state'] == '2') {
        $insert_device_info['nns_state'] = 4;
    } elseif ($device_info['nb_state'] == '3') {
        $insert_device_info['nns_state'] = 5;
    } elseif ($device_info['nb_state'] == '4'){
        $insert_device_info['nns_state'] = 0;
    } else {
        $insert_device_info['nns_state'] = 6;
    }
    $insert_device_info = array(
        'nns_id' => $device_info['nb_device_id'],
        'nns_mac' => $device_info['nb_mac'],
        'nns_user_id' => $device_info['nb_user_id'],
        'nns_create_time' => $device_info['nb_create_time'],
        'nns_state' => $device_info['nb_state'],
        'nns_net_id' => $device_info['nb_licence_id'],
        'nns_ip' => $device_info['nb_reg_ip'],
        'nns_addr' => $device_info['nb_setup_addr'],
        'nns_modify_time' => $device_info['nb_modify_time'],
        'nns_lock' => $device_info['nb_disable']

    );
    $key_value = get_key_values($insert_device_info);
    $insert_device_sql = "insert into nns_device ({$key_value['fields']}) values({$key_value['values']})";
    $result = nl_execute_by_db($insert_device_sql, $aaa_dc->db());
    if(!$result){
        save_error_info('nns_device',$insert_device_sql);
        continue;
    }

    $user_id = $device_info['nb_user_id'];
    //从第三方查询user信息
    $user_sql = "select nb_id,nb_name,nb_password,nb_address,nb_login_id,nb_contact,nb_telephone,nb_create_time,nb_modify_time,nb_level from nb_user where nb_id='" . $user_id . "'";
    $user_info = query_src_mysql_row($user_sql, $src_conn);
    //获取插入user_boss表的信息
    if (!$user_info) {
        save_error_info('nb_user',$user_sql);
        continue;
    }

    //获取插入user表的信息
    $new_user_id = np_guid_rand();
    $nns_user_info = array(
        'nns_id' => $new_user_id,
        'nns_name' => $user_info['nb_name'],
        'nns_addr' => $user_info['nb_address'],
        'nns_login_id' => $user_info['nb_login_id'],
        'nns_contact' => $user_info['nb_contact'],
        'nns_telephone' => $user_info['nb_telephone'],
        'nns_create_time' => $user_info['nb_create_time'],
        'nns_modify_time' => $user_info['nb_modify_time'],
        'nns_user_level' => $user_info['nb_level']
    );
    $key_value = get_key_values($nns_user_info);
    $insert_user_sql = "insert into nns_user ({$key_value['fields']}) values({$key_value['values']})";
    $result = nl_execute_by_db($insert_user_sql, $aaa_dc->db());
    if (!$result) {
        save_error_info('nns_user',$insert_user_sql);
        continue;
    }

    //获取插入boss_user表的信息
    $nns_user_boss_info = array(
        'nns_id' => $new_user_id,
        'nns_user_name' => $user_info['nb_name'],
        'nns_user_id' => $user_info['nb_id'],
        'nns_attribution' => $user_info['nb_address'],
        //线上数据库没有该字段
//        'nns_login_id' => $user_info['nb_login_id'],
        'nns_phone_number' => $user_info['nb_telephone'],
        'nns_create_time' => $user_info['nb_create_time'],
        'nns_modify_time' => $user_info['nb_modify_time'],
        'nns_user_rank' => $user_info['nb_level'],
        'nns_ip' => $device_info['nb_reg_ip'],
        'nns_mac' => $device_info['nb_mac']
    );
    $key_value = get_key_values($nns_user_boss_info);
    $insert_user_boss_sql = "insert into nns_boss_user ({$key_value['fields']}) values({$key_value['values']})";
    $result = nl_execute_by_db($insert_user_boss_sql, $aaa_dc->db());
    if (!$result) {
        save_error_info('nns_boss_user',$insert_user_boss_sql);
        continue;
    }
}
die('该脚本运行结束,请确认后继续执行');





