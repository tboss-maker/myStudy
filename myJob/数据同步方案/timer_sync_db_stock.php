<?php
/**
 * 主方案
 *.描述：上海移动nb_boss脚本存量批量数据同步
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
$aaa_redis = $aaa_dc->aaa_redis();
if (!$aaa_redis->exists(LIMIT_REDIS_KEY)) {
    die('数据同步已完成或者未运行初始化脚本,请确认!');
}
$limit_start = get_offset($aaa_redis);
if (!$limit_start && $limit_start != '0') {
    die('获取查询的起始地址失败');
}
$limit = array($limit_start, LIMIT_SIZE);

//nboss的redis配置
$nboss_redis_config = json_decode(NBOSS_REDIS_CONFIG, true);

$nboss_redis = new Redis();
$nboss_redis->pconnect($nboss_redis_config['host'], $nboss_redis_config['port']);
if (!$nboss_redis) {
    exit('boss库redis连接失败,请确认配置正确!');
}

//nboss数据库的mysql配置
$nboss_mysql_config = json_decode(NBOSS_MYSQL_CONFIG, true);
//获取存量界定时间
$sync_time = $aaa_redis->get(SYNC_TIME);
$where_time = '';
if ($sync_time) {
    $where_time = " where device.nb_create_time<'{$sync_time}'";
}
$src_conn = get_db_connect($nboss_mysql_config);
if (!$src_conn) {
    back_offset($aaa_redis, $limit_start);
    exit("nboss库连接失败!");
}
//第三方device查询
//$device_query_sql = 'select device.*,record.*,u.* from nb_device as device left join nb_device_record as record on device.nb_device_id=record.nb_device_id left join nb_user as u on device.nb_user_id=u.nb_id';
$device_query_sql = 'select device.*,record.nb_setup_addr,record.nb_licence_id,record.nb_reg_ip,record.nb_disable,u.* from nb_device as device left join nb_device_record as record on device.nb_device_id=record.nb_device_id left join nb_user as u on device.nb_user_id=u.nb_id';
$device_query_sql .= $where_time;
//查询device的sql
$device_query_sql .= " limit {$limit[0]},{$limit[1]}";
$device_res = query_src_mysql($device_query_sql, $src_conn);

is_error_exit($device_res, $device_query_sql, $limit, 'nb_device');

$insert_device_info = array();
$insert_device_fields = array();
$insert_user_info = array();
$insert_user_fields = array();
$insert_boss_user_info = array();
$insert_boss_user_fields = array();

//生成导入数据
foreach ($device_res as $key => $device_info) {
    $temp_user_id = $nboss_redis->get($device_info['nb_id']);
    if(!$temp_user_id){
        $temp = np_guid_rand();
    }else{
        $temp = $temp_user_id;
    }
    //nb_state转换
    $device_info['nb_state'] = deal_with_state($device_info['nb_state']);
    //获取插入设备信息
    $insert_device_info[] = array(
        "'{$device_info['nb_device_id']}'",
        "'{$device_info['nb_mac']}'",
        "'{$temp}'",
        "'{$device_info['nb_create_time']}'",
        "'{$device_info['nb_state']}'",
        "'{$device_info['nb_licence_id']}'",
        "'{$device_info['nb_reg_ip']}'",
        "'{$device_info['nb_setup_addr']}'",
        "'{$device_info['nb_modify_time']}'",
        "'{$device_info['nb_disable']}'",
    );

    //排除未激活盒子
    if (!empty($device_info['nb_id'])||!empty($insert_data['nb_user_id'])) {
        //过滤user重复数据
        if($temp_user_id){
            continue;
        }
        $nboss_redis->setex($device_info['nb_id'],3600*6,$temp);

        //获取插入boss_user表的信息
        $insert_boss_user_info[] = array(
            "'{$device_info['nb_id']}'",
            "'{$device_info['nb_name']}'",
            "'{$temp}'",
            "'{$device_info['nb_address']}'",
            //线上数据库没有该字段
//        'nns_login_id' => $user_info['nb_login_id'],
            "'{$device_info['nb_telephone']}'",
            "'{$device_info['nb_create_time']}'",
            "'{$device_info['nb_modify_time']}'",
            "'{$device_info['nb_level']}'",
            "'{$device_info['nb_reg_ip']}'",
            "'{$device_info['nb_mac']}'",
            "'{$device_info['nb_state']}'"
        );

        //获取插入user表的信息
        $insert_user_info[] = array(
            "'{$temp}'",
            "'{$device_info['nb_name']}'",
            "'{$device_info['nb_password']}'",
            "'{$device_info['nb_address']}'",
            "'{$device_info['nb_login_id']}'",
            "'{$device_info['nb_contact']}'",
            "'{$device_info['nb_telephone']}'",
            "'{$device_info['nb_create_time']}'",
            "'{$device_info['nb_modify_time']}'",
            "'{$device_info['nb_level']}'"
        );
    }
}

//获取插入设备字段信息
$insert_device_fields = array('nns_id', 'nns_mac', 'nns_user_id', 'nns_create_time', 'nns_state', 'nns_net_id', 'nns_ip', 'nns_addr', 'nns_modify_time', 'nns_lock');
//获取插入user表的字段
$insert_user_fields = array('nns_id', 'nns_name', 'nns_password', 'nns_addr', 'nns_login_id', 'nns_contact', 'nns_telephone', 'nns_create_time', 'nns_modify_time', 'nns_user_level');
//获取boss_user对应字段
$insert_boss_user_fields = array('nns_id', 'nns_user_name', 'nns_user_id', 'nns_attribution', 'nns_phone_number', 'nns_create_time', 'nns_modify_time', 'nns_user_rank', 'nns_ip', 'nns_mac', 'nns_state');
//sql文件生成
set_multi_replace_sql('nns_device', $insert_device_fields, $insert_device_info, $limit_start);

set_multi_insert_sql('nns_user', $insert_user_fields, $insert_user_info, $limit_start);

set_multi_insert_sql('nns_boss_user', $insert_boss_user_fields, $insert_boss_user_info, $limit_start);

if($sync_times = $aaa_redis->get('SYNC_TIMES')){
    $sync_times += 1;
}else{
    $sync_times = 1;
};
$aaa_redis->set('SYNC_TIMES',$sync_times,3600*6);
if($total=$aaa_redis->get('total_run_times')){
    $surplus = $total - $sync_times;
}

die("该脚本执行结束,运行了{$sync_times}次,还需运行{$surplus}次,请继续...");

/**
 * sql文件替换生成
 * @param $table
 * @param $fields
 * @param $data
 * @param $limit_start
 * @return bool
 */
function set_multi_replace_sql($table, $fields, $data)
{
    if (empty($fields) || empty($data)) {
        return false;
    }
    $keys = '';
    foreach ($fields as $field) {
        $keys .= $field . ',';
    }
    $keys = rtrim($keys, ',');
    $keys = '(' . $keys . ')';
    $values = '';
    foreach ($data as $value) {
        $values .= '(';
        $values .= implode(',', $value);
        $values .= '),';
    }
    $values = rtrim($values, ',');
    $sql = "replace into {$table} {$keys} values {$values};\r\n";
    
    $dirname = dirname(__FILE__) . DIRECTORY_SEPARATOR . "save_stock_sql" . DIRECTORY_SEPARATOR . 'save_sql_' . $table;
    if (!is_dir($dirname)) {
        $ret = mkdir($dirname, 0777, true);
        if (!$ret) {
            return false;
        }
    }
    $filename = $dirname . DIRECTORY_SEPARATOR . 'save_sql_' . $table . '.sql';
    $ret = file_put_contents($filename, $sql, FILE_APPEND);
    if (!$ret) {
        return false;
    }
    return true;
}

/**
 * sql文件新增生成
 * @param $table
 * @param $fields
 * @param $data
 * @param $limit_start
 * @return bool
 */
function set_multi_insert_sql($table, $fields, $data)
{
    if (empty($fields) || empty($data)) {
        return false;
    }
    $keys = '';
    foreach ($fields as $field) {
        $keys .= $field . ',';
    }
    $keys = rtrim($keys, ',');
    $keys = '(' . $keys . ')';
    $values = '';
    foreach ($data as $value) {
        $values .= '(';
        $values .= implode(',', $value);
        $values .= '),';
    }
    $values = rtrim($values, ',');
    $sql = "insert into {$table} {$keys} values {$values};\r\n";

    $dirname = dirname(__FILE__) . DIRECTORY_SEPARATOR . "save_stock_sql" . DIRECTORY_SEPARATOR . 'save_sql_' . $table;
    if (!is_dir($dirname)) {
        $ret = mkdir($dirname, 0777, true);
        if (!$ret) {
            return false;
        }
    }
    $filename = $dirname . DIRECTORY_SEPARATOR . 'save_sql_' . $table . '.sql';
    $ret = file_put_contents($filename, $sql, FILE_APPEND);
    if (!$ret) {
        return false;
    }
    return true;
}

function deal_with_state($nb_state)
{
    switch ($nb_state) {
        case 1:
            $new_state = 0;
            break;
        case 2:
            $new_state = 4;
            break;
        case 3;
            $new_state = 5;
            break;
        case 4:
            $new_state = 0;
            break;
        default:
            $new_state = 6;
            break;
    }
    return $new_state;
}


