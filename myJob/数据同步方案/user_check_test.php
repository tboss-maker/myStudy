<?php
/**
 *.描述：上海移动nb_boss脚本存量数据异常用户排查
 * author: suji.zhao
 * 创建时间: 2016/11/10
 */
header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '2048M');
//set_time_limit(7200);

//为了节省资源和时间,设置排查的异常用户id数量;
$user_num = 10;

$cms_dir = dirname(dirname(dirname(__FILE__)));
include_once $cms_dir . '/nn_logic/nl_common.func.php';
include_once $cms_dir . '/nn_logic/common/v2/global.include.php';
include_once "shyd_common.php";

// 连接AAA数据库
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NL_REDIS_WRITE
));

//nboss数据库的mysql配置
$nboss_mysql_config = json_decode(NBOSS_MYSQL_CONFIG, true);
$src_conn = get_db_connect($nboss_mysql_config);
if (!$src_conn) {
    exit("nboss库连接失败!");
}
$aaa_redis = $aaa_dc->aaa_redis();

$aaa_sql = 'select nns_user_id from nns_boss_user;';
$aaa_result = nl_query_by_db($aaa_sql, $aaa_dc->db());
$boss_sql = 'select nb_id from nb_user;';
$boss_res = query_src_mysql($boss_sql, $src_conn);
foreach($aaa_result as $a1){
    $temp1[] = $a1['nns_user_id'];
}
foreach($boss_res as $a2){
    $temp2[] = $a2['nb_id'];
}

$aaa_result = $temp1;
$boss_res = $temp2;

$error_data = array();
//计数器
$count_val = 0;
foreach ($boss_res as $v1) {
    if (!in_array($v1, $aaa_result)) {
        $count_val += 1;
        $error_data[] = $v1;
        if($count_val==$user_num){
            break;
        }
    }
}
if(!count($error_data)){
    die('没有错误用户信息...');
}

save_error_check(json_encode($error_data));
die('错误用户信息写入完毕...');

function save_error_check($data)
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
    $filename = $dirname.DIRECTORY_SEPARATOR.'error_user_id.'.date('Ymd',time()).'.txt';
    $ret = file_put_contents($filename,$data,FILE_APPEND);
    if(!$ret){
        return false;
    }
    return true;
}

