<?php
/**
 *.描述：初始化limit队列(一次同步只运行该脚本一次)
 * author: suji.zhao
 * 创建时间: 2016/11/10
 */
header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('memory_limit','2048M');
//set_time_limit(7200);

$cms_dir = dirname(dirname(dirname(__FILE__)));
include_once $cms_dir . '/nn_logic/nl_common.func.php';
include_once $cms_dir . '/nn_logic/common/v2/global.include.php';
include_once 'shyd_common.php';

//AAA服务器
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NL_REDIS_WRITE
));

$aaa_redis = $aaa_dc->aaa_redis();

//nboss数据库的mysql配置
$nboss_mysql_config = json_decode(NBOSS_MYSQL_CONFIG,true);
//nboss的redis配置
$nboss_redis_config = json_decode(NBOSS_REDIS_CONFIG,true);

$nboss_redis = new Redis();
$nboss_redis->pconnect($nboss_redis_config['host'],$nboss_redis_config['port']);
if(!$nboss_redis){
    exit('boss库redis连接失败,请确认配置正确!');
}
//获取开始时间
$sync_time = $nboss_redis->get(SYNC_TIME);

//存储存量界定时间到AAA
$where_time = '';
if($sync_time) {
    $ret = $aaa_redis->setex(SYNC_TIME,3600,$sync_time);
    if (!$ret) {
        exit("AAA redis库出错");
    }
    $where_time = " where nb_create_time<'{$sync_time}'";
}
//清空偏移量数据池
$aaa_redis->delete(LIMIT_REDIS_KEY);
//查询总的数据条数
$src_conn = get_db_connect($nboss_mysql_config);
if(!$src_conn ){
   exit('第三方库连接失败,请确认配置正确');
}
//查询需要同步的数据总量(设备用户为一对一关系)
$sql = 'select count(nb_device_id) as number from nb_device';
//正式测试
$sql .= $where_time;
$res = query_src_mysql($sql, $src_conn);
if($res===false)
{
    die('查询第三方库总数DB失败');
}
$num = $res[0]['number'];
$len = ceil($num/LIMIT_SIZE);
for($i=0;$i<$len;$i++){
    $aaa_redis->rpush(LIMIT_REDIS_KEY, $i*LIMIT_SIZE);
};
$aaa_redis->set('total_run_times',$len,3600*10);
$aaa_redis->delete('SYNC_TIMES');
echo "初始化成功!存量数据总共有{$num}条,需要运行{$len}次同步脚本,请运行存量迁移脚本...";
die;









