<?php
/**
 *.描述：通用工具文件
 * author: kang.lu
 * 创建时间: 2016/11/8
 */

define('MIGRATE_FILE_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'db_migrate');

//存放nb_boss库数据总数
define('LIMIT_REDIS_KEY', 'SHYD_LIMIT_LIST');
//单脚本处理的数据条数(默认10000)
define('LIMIT_SIZE', 10000);
//存储在nb_boss库中的增量数据名
define('SHYD_NEW_DATA', 'SHYD_NEW_DATA');
define("SYNC_TIME",'sync_db_time');
//定义nb_boss redis设置
$redis_config = json_encode(
    array(
        //BOSS REDIS服务器
        "host" => 'localhost',
        'port' => "6379"
    )
);
//定义boss mysql配置
$mysql_config = json_encode(
    array(
        //BOSS服务器
        'host' => 'localhost',
        'username'=> 'root',
        'userpass'=> '',
        'dbname' => 'nb_boss',
        'port' => '3306'
    )
);
define("NBOSS_REDIS_CONFIG",$redis_config);
define("NBOSS_MYSQL_CONFIG",$mysql_config);

function get_db_connect($mysql_config)
{
    $conn = mysqli_connect($mysql_config['host'], $mysql_config['username'], $mysql_config['userpass'],$mysql_config['dbname']);
    if (!$conn) {
        return false;
    } else {
        return $conn;
    }
}

function close_db_connect($conn)
{
    return mysqli_close($conn);
}


//查询第三方库，失败返回false,成功返回二维数组array
function query_src_mysql($sql, $mysql_conn, $db = 'nb_boss')
{
    mysqli_select_db($mysql_conn, $db);
    $res = mysqli_query($mysql_conn, $sql);
    if (!$res) {
        return false;
    }
    $ret = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $ret[] = $row;
    }

    return $ret;
}

//查询第三方库，失败返回false,成功返回一维数组array
function query_src_mysql_row($sql, $mysql_conn, $db = 'nb_boss')
{
    mysqli_select_db($mysql_conn, $db);
    $res = mysqli_query($mysql_conn, $sql);
    if (!$res) {
        return false;
    }
    $res = mysqli_fetch_assoc($res);
    return $res;
}

function is_error_exit($res, $sql, $limit, $table_name = '')
{
    if ($res === null) {
        echo '查询数据库失败:' . $sql . ' limit = ' . var_export($limit, true);
        die;
    }
    if (empty($res)) {
        echo '查询数据库失败:' . $sql;
        die;
    }
    return;
}

/**
 * @param $aaa_redis 第三方redis配置
 * @return bool/string
 */
function get_offset($aaa_redis)
{
    return $aaa_redis->lpop(LIMIT_REDIS_KEY);
}

/**
 * 数据出错将offset返回队列
 * @param $old_offset 原来的offset
 * return bool
 */
function back_offset($aaa_redis, $old_offset)
{
    return $aaa_redis->rpush(LIMIT_REDIS_KEY, $old_offset);
}

/**
 * 根据映射数组来获取插入的对应名和对应值
 * @param array $insert_data
 * return array 键名和键值
 */
function get_key_values($insert_data)
{
    if (!$insert_data) {
        return false;
    }
    $str_fields = '';
    $str_values = '';
    foreach ($insert_data as $str_field => $str_val) {
        $str_fields .= "{$str_field},";
        if (is_string($str_val)) {
            $str_val = addslashes($str_val);
            $str_values .= "'{$str_val}',";
        } else {
            $str_values .= "'{$str_val}',";
        }
    }
    $str_fields = rtrim($str_fields, ',');
    $str_values = rtrim($str_values, ',');
    $array['fields'] = $str_fields;
    $array['values'] = $str_values;
    return $array;
}

/**
 * 返回nboss保存的新增用户缓存
 * @param $boss_redis 第三方redis配置
 * @return array
 */
function get_new_insert_data($boss_redis)
{
    return $boss_redis->zRange(SHYD_NEW_DATA, 0, -1);
}

/**
 * 返回nboss保存的新增用户缓存
 * @param $boss_redis 第三方redis配置
 * @param $value 需要删除的值
 * @return array
 */
function del_new_insert_data($boss_redis, $value)
{
    return $boss_redis->zDelete(SHYD_NEW_DATA, $value);
}

/*
 * 链接nboss库的redis
 * @param $config nboss的redis配置
 * return redis对象/false
 */
function get_boss_redis_connect($config){
    $redis = new Redis();
    //$redis->pconnect($config['host'],$config['port']);
    $redis->pconnect($config['host'],$config['port']);
    return $redis;
}

/*
 * 将错误的信息写入文件
 * @param $data 每一次执行同步脚本产生的失败数据
 * @param $mode insert表示新增数据 update 表示更新数据错误
 */
function save_error_info($data,$mode='insert')
{
    if(!$data){
        exit("错误文件写入参数错误");
    }
    $data = $data."\r\n";
    $dirname = dirname(__FILE__).DIRECTORY_SEPARATOR."error_".$mode."_data";
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