<?php
/**
 *.描述：上海移动存量时间注入
 * author: suji.zhao
 * 创建时间: 2016/11/10
 */
header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 0);
error_reporting(E_ALL);
$cms_dir = dirname(dirname(dirname(__FILE__)));
include_once $cms_dir . '/nn_logic/nl_common.func.php';
include_once $cms_dir . '/nn_logic/common/v2/global.include.php';
include_once "shyd_common.php";
$nboss_redis_config = json_decode(NBOSS_REDIS_CONFIG, true);
$boss_redis = get_boss_redis_connect($nboss_redis_config);
$error_data = array();
if (!$boss_redis) {
    exit('nboss库连接失败,请确认配置正确!');
}

$result = $boss_redis->zRange(SHYD_NEW_DATA,0,-1);
if($result){
    //清除增量数据
    $result = $boss_redis->delete(SHYD_NEW_DATA);
}
$sync_db_time = $boss_redis->get("sync_db_time");
if($sync_db_time){
    //清除垃圾存量时间
    $boss_redis->delete("sync_db_time");
}
//添加存量需要的时间
$ret = $boss_redis->setex("sync_db_time",3600*6,date("Y-m-d H:i:s",time()));
if($ret){
    echo '增量缓存清除完毕,存量时间注入成功...';
}else{
    echo '存量时间注入失败...';
}
die;


