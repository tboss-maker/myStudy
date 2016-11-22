<?php
/**
 *.描述：初始化limit队列(一次同步只运行该脚本一次)
 * author: kang.lu
 * 创建时间: 2016/11/10
 */

header("Content-type:text/html;charset=utf-8");
$cms_dir = dirname(dirname(dirname(__FILE__)));
include_once $cms_dir . '/nn_logic/nl_common.func.php';
include_once $cms_dir . '/nn_logic/common/v2/global.include.php';
include_once 'shyd_common.php';
// 连接AAA数据库
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NL_REDIS_WRITE
));

$aaa_redis = $aaa_dc->aaa_redis();

//第三方数据库数据库配置
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

//查询总的数据条数
$src_conn = get_db_connect($nboss_mysql_config);
if(!$src_conn ){
   exit('第三方库连接失败,请确认配置正确');
}
$sql = 'select count(nb_device_id) as number from nb_device;';
$res = query_src_mysql($sql, $src_conn);
if(!$res)
{
    die('查询第三方库总数DB失败');
}
$len = ceil($res[0]['number']/LIMIT_SIZE);
for($i=0;$i<$len;$i++){
    $aaa_redis->rpush(LIMIT_REDIS_KEY, $i*LIMIT_SIZE);
};
echo '初始化成功!请运行存量迁移脚本';









