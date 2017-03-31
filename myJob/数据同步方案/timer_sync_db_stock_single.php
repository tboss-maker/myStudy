<?php
/**
 * 备用方案
 *.描述：上海移动nb_boss脚本存量数据单条插入
 * author: suji.zhao
 * 创建时间: 2016/11/10
 */
header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('memory_limit', '2048M');
//set_time_limit(7200);

$cms_dir = dirname(dirname(dirname(__FILE__)));
include_once $cms_dir . '/nn_logic/nl_common.func.php';
include_once $cms_dir . '/nn_logic/common/v2/global.include.php';
include_once "shyd_common.php";

// 连接AAA数据库
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NL_REDIS_WRITE
));

//nboss的redis配置
$nboss_redis_config = json_decode(NBOSS_REDIS_CONFIG,true);
$nboss_redis = new Redis();
$nboss_redis->pconnect($nboss_redis_config['host'],$nboss_redis_config['port']);
if(!$nboss_redis){
    exit('boss库redis连接失败,请确认配置正确!');
}
$aaa_redis = $aaa_dc->aaa_redis();
if(!$aaa_redis->exists(LIMIT_REDIS_KEY)){
    die('未初始化或存量数据同步执行结束,请确认...');
}
$limit_start = get_offset($aaa_redis);
if (!$limit_start && $limit_start!='0') {
    die('获取起始地址失败');
}
$limit = array($limit_start, LIMIT_SIZE);

//nboss数据库的mysql配置
$nboss_mysql_config = json_decode(NBOSS_MYSQL_CONFIG,true);
//获取存量界定时间
$sync_db_time = $nboss_redis->get("sync_db_time");
$where_time = '';
if ($sync_db_time) {
    $where_time = " where device.nb_create_time<'{$sync_time}'";
}
$src_conn = get_db_connect($nboss_mysql_config);
if(!$src_conn){
    exit("nboss库连接失败!");
}
//第三方device查询
//$device_query_sql = 'select device.nb_device_id,device.nb_mac,device.nb_user_id,device.nb_create_time,device.nb_state,record.nb_licence_id,record.nb_reg_ip,record.nb_setup_addr,record.nb_modify_time,record.nb_disable from nb_device as device left join nb_device_record as record on device.nb_device_id=record.nb_device_id';
//$device_query_sql = 'select device.*,record.*,u.* from nb_device as device left join nb_device_record as record on device.nb_device_id=record.nb_device_id left join nb_user as u on device.nb_user_id=u.nb_id';
$device_query_sql = 'select device.*,record.nb_setup_addr,record.nb_licence_id,record.nb_reg_ip,record.nb_disable,u.* from nb_device as device left join nb_device_record as record on device.nb_device_id=record.nb_device_id left join nb_user as u on device.nb_user_id=u.nb_id';
$device_query_sql .= $where_time;
//查询device的sql
$device_query_sql .=  " limit {$limit[0]},{$limit[1]};";
$device_res = query_src_mysql($device_query_sql, $src_conn);

is_error_exit($device_res, $device_query_sql, $limit, 'nb_device');

//开始循环处理查询的设备数据，转换后导入AAA库
foreach ($device_res as $key => $device_info) {
    $temp_user_id = $nboss_redis->get($device_info['nb_id']);
    if(!$temp_user_id){
        $temp = np_guid_rand();
    }else{
        $temp = $temp_user_id;
    }
    //nb_state转换
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
            'nns_user_id' => $temp,
            'nns_create_time' => $device_info['nb_create_time'],
            'nns_state' => $device_info['nb_state'],
            'nns_net_id' => $device_info['nb_licence_id'],
            'nns_ip' => $device_info['nb_reg_ip'],
            'nns_addr' => $device_info['nb_setup_addr'],
            'nns_modify_time' => $device_info['nb_modify_time'],
            'nns_lock' => $device_info['nb_disable']
        );
        $key_value = get_key_values($insert_device_info);
        $insert_device_sql = "replace into nns_device ({$key_value['fields']}) values({$key_value['values']});";
        $result2 = nl_execute_by_db($insert_device_sql, $aaa_dc->db());
        if (!$result2) {
            save_error_check($insert_device_sql);
        }

    //排除未激活盒子
    if (!empty($device_info['nb_id'])||!empty($insert_data['nb_user_id'])) {
        //过滤user重复数据
        if ($temp_user_id) {
            continue;
        }
        $nboss_redis->setex($device_info['nb_id'],3600*6,$temp);
        //获取插入user表的信息
        $nns_user_info = array(
            'nns_id' => $temp,
            'nns_name' => $device_info['nb_name'],
            'nns_password' => $device_info['nb_password'],
            'nns_addr' => $device_info['nb_address'],
            'nns_login_id' => $device_info['nb_login_id'],
            'nns_contact' => $device_info['nb_contact'],
            'nns_telephone' => $device_info['nb_telephone'],
            'nns_create_time' => $device_info['nb_create_time'],
            'nns_modify_time' => $device_info['nb_modify_time'],
            'nns_user_level' => $device_info['nb_level'],
            'nns_user_is_category' => $device_info['nb_level']
        );
        $key_value = get_key_values($nns_user_info);
        $insert_user_sql = "insert into nns_user ({$key_value['fields']}) values({$key_value['values']});";
        $result3 = nl_execute_by_db($insert_user_sql, $aaa_dc->db());
        if (!$result3) {
            save_error_check($insert_user_sql, 'user');
        }

        //获取插入boss_user表的信息
        $nns_user_boss_info = array(
            'nns_id' => $temp,
            'nns_user_name' => $device_info['nb_name'],
            'nns_user_id' => $device_info['nb_id'],
            'nns_attribution' => $device_info['nb_address'],
            //线上数据库没有该字段
//        'nns_login_id' => $user_info['nb_login_id'],
            'nns_phone_number' => $device_info['nb_telephone'],
            'nns_create_time' => $device_info['nb_create_time'],
            'nns_modify_time' => $device_info['nb_modify_time'],
            'nns_user_rank' => $device_info['nb_level'],
            'nns_ip' => $device_info['nb_reg_ip'],
            'nns_mac' => $device_info['nb_mac'],
            'nns_state' => $device_info['nb_state']
        );
        $key_value = get_key_values($nns_user_boss_info);
        $insert_user_boss_sql = "insert into nns_boss_user ({$key_value['fields']}) values({$key_value['values']});";
        $result4 = nl_execute_by_db($insert_user_boss_sql, $aaa_dc->db());
        if (!$result4) {
            save_error_check($insert_user_boss_sql, 'boss_user');
        }
    }
}

if($sync_times = $aaa_redis->get('SYNC_TIMES')){
    $sync_times += 1;
}else{
    $sync_times = 1;
};
$aaa_redis->set('SYNC_TIMES',$sync_times,3600*10);
if($total=$aaa_redis->get('total_run_times')){
    $surplus = $total - $sync_times;
}
die("脚本执行完成,运行了{$sync_times}次,还需运行{$surplus}次,请继续...");

/*
 * 将错误的信息写入文件
 * @param $data 每一次执行同步脚本产生的失败数据
 * @param $mode insert表示新增数据 update 表示更新数据错误
 */
function save_error_check($data,$mode='device')
{
    if(!$data){
        exit("错误文件写入参数错误");
    }
    $data = $data."\r\n";
    $dirname = dirname(__FILE__).DIRECTORY_SEPARATOR."error_check_data";
    if(!is_dir($dirname)){
        $ret = mkdir($dirname,0777, true);
        if(!$ret){
            return false;
        }
    }
    $filename = $dirname.DIRECTORY_SEPARATOR.'error_'.$mode.'_data.'.date('Ymd',time()).'.txt';
    $ret = file_put_contents($filename,$data,FILE_APPEND);
    if(!$ret){
        return false;
    }
    return true;
}




