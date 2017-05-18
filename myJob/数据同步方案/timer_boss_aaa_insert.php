<?php
/**
 * @name        timer_ .php
 * @abstract    nb_boss库新增数据同步到nns_aaa库
 * @encoding    UTF-8
 * @copyright  (c) starcor
 * @author      suji.zhao <suji.zhao@starcor.cn>
 * @datetime    2016/11/2
 */
header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit','512M');
//set_time_limit(7200);

// 引入文件
$product_dir = dirname(dirname(dirname(__FILE__)));
include $product_dir . '/nn_logic/nl_common.func.php';
include $product_dir . '/nn_logic/common/v2/global.include.php';
include 'shyd_common.php';

// 连接AAA数据库
$aaa_dc = nl_get_aaa_dc(array(
    "db_policy" => NL_DB_WRITE,
    "cache_policy" => NL_REDIS_WRITE
));

$nboss_redis_config = json_decode(NBOSS_REDIS_CONFIG, true);
$boss_redis = get_boss_redis_connect($nboss_redis_config);
$error_data = array();
if (!$boss_redis) {
    exit('nboss库连接失败,请确认配置正确!');
}

//获取nboss库缓存的新增数据
$insert_data_arr = get_new_insert_data($boss_redis);

//同步新增数据
$insert_data_len = count($insert_data_arr);
if ($insert_data_len == 0) {
    die('没有需要同步的新增数据,请确认!');
}
$error_data = array();
//遍历数据
foreach ($insert_data_arr as $key => $insert_data) {
    //解析数据
    $insert_data = json_decode($insert_data, true);
    //nb_state重新定义
    if ($insert_data['nb_state'] == '1') {
        $params['nns_state'] = 0;
    } elseif ($insert_data['nb_state'] == '2') {
        $params['nns_state'] = 4;
    } elseif ($insert_data['nb_state'] == '3') {
        $params['nns_state'] = 5;
    } elseif ($insert_data['nb_state'] == '4') {
        $params['nns_state'] = 0;
    } else {
        $params['nns_state'] = 6;
    }
    $params['nns_id'] = $insert_data['nb_device_id'];
    $params['nns_mac'] = $insert_data['nb_mac'];
    //user_id重新获取
    if(!empty($insert_data['nb_id'])||!empty($insert_data['nb_user_id'])){
        $params['nns_user_id'] = $new_user_id = np_guid_rand();
    }
    $params['nns_create_time'] = $insert_data['nb_create_time'];
    $params['nns_state'] = $insert_data['nb_state'];
    if(!empty($insert_data['nb_licence_id'])){
        $params['nns_net_id'] = $insert_data['nb_licence_id'];
    }
    if(!empty($insert_data['nb_modify_time'])){
        $params['nns_modify_time'] = $insert_data['nb_modify_time'];
    }

    if(!empty($insert_data['nb_reg_ip'])){
        $params['nns_ip'] = $insert_data['nb_reg_ip'];
    }
    if(!empty($insert_data['nb_disable'])){
        $params['nns_lock'] = $insert_data['nb_disable'];
    }
    if(!empty($insert_data['nb_setup_addr'])){
        $params['nns_addr'] = $insert_data['nb_setup_addr'];
    }

    //换机数据同步
    if (!empty($insert_data['old_device_id'])) {
        //如果存在有旧的device_id,则将其停用
        $sql2 = "update nns_device set nns_state=4 where nns_id='{$insert_data['old_device_id']}';";
        $result2 = nl_execute_by_db($sql2, $aaa_dc->db());
        if (!$result2) {
            save_error_info($sql2, 'swap');
        }
        //判断数据是否是新机是否已经存在
        $sql = "select count(nns_id) as num from nns_device where nns_id='{$insert_data['nb_device_id']}';";
        $result1 = nl_query_by_db($sql, $aaa_dc->db());
        if (!$result1) {
            //錯誤日志
            save_error_info($sql, 'swap');
        }
        if ($result1[0]['num'] > 0) {
            //执行更新操作
            $update_sql = "update nns_device set nns_user_id=(select nns_user_id from nns_boss_user where nns_id='{$insert_data['nb_user_id']}') where nns_id='{$insert_data['nb_device_id']}';";
            $result3 = nl_execute_by_db($update_sql, $aaa_dc->db());
            if (!$result3) {
                save_error_info($update_sql, 'swap');
            }
            if (!$result1 || !$result2 || !$result3) {
                //收集错误数据
                $error_data[] = $insert_data;
            }
            //同步完成的数据移除队列
            del_new_insert_data($boss_redis, $insert_data_arr[$key]);
            continue;
        }
    }

    $key_value = get_key_values($params);
    $sql2 = "replace into nns_device ({$key_value['fields']}) values({$key_value['values']})";
    //将对应数据写入device表
    $result2 = nl_execute_by_db($sql2, $aaa_dc->db());
    if (!$result2) {
        save_error_info($sql2);
    }
    if (!empty($insert_data['nb_id'])||!empty($insert_data['nb_user_id'])) {
        //通过设备表的user_id查user表的数据,同步到AAA库
        //user表参数
        $params1['nns_id'] = $new_user_id;
        $params1['nns_name'] = $insert_data['nb_name'];
        $params1['nns_password'] = $insert_data['nb_password'];
        $params1['nns_addr'] = $insert_data['nb_address'];
        $params1['nns_login_id'] = $insert_data['nb_login_id'];
        $params1['nns_contact'] = $insert_data['nb_contact'];
        $params1['nns_telephone'] = $insert_data['nb_telephone'];
        $params1['nns_create_time'] = $insert_data['nb_create_time'];
        $params1['nns_modify_time'] = $insert_data['nb_modify_time'];
        $params1['nns_user_level'] = $insert_data['nb_level'];
        $params1['nns_user_is_category'] = $insert_data['nb_level'];
        $params1['nns_boss_user_rank'] = $insert_data['nb_level'];

        //boss_user表参数
        $params2['nns_id'] = $insert_data['nb_id'];
        $params2['nns_user_name'] = $insert_data['nb_name'];
        //线上数据库没有该字段
//        $params2['nns_login_id'] = $insert_data['nb_login_id'];
        $params2['nns_user_id'] = $new_user_id;
        $params2['nns_user_password'] = $insert_data['nb_password'];
        $params2['nns_phone_number'] = $insert_data['nb_telephone'];
        $params2['nns_create_time'] = $insert_data['nb_create_time'];
        $params2['nns_modify_time'] = $insert_data['nb_modify_time'];
        if(!empty($insert_data['nb_label_id'])){
            $params2['nns_area_code'] = $insert_data['nb_label_id'];
        }

        $params2['nns_attribution'] = $insert_data['nb_address'];

        if(!empty($insert_data['nb_reg_ip'])){
            $params2['nns_ip'] = $insert_data['nb_reg_ip'];
        }
        $params2['nns_mac'] = $insert_data['nb_mac'];
        $params2['nns_state'] = $insert_data['nb_state'];
        //用户级别
        $params2['nns_user_rank'] = $insert_data['nb_level'];

        //同步数据至user表
        $key_value = get_key_values($params1);
        $sql3 = "replace into nns_user ({$key_value['fields']}) values({$key_value['values']});";
        //将对应数据写入user表
        $result3 = nl_execute_by_db($sql3, $aaa_dc->db());
        if ($result3 === false) {
            save_error_info($sql3);
        }

        //同步数据至user_boss表
        $key_value = get_key_values($params2);
        $sql4 = "replace into nns_boss_user ({$key_value['fields']}) values({$key_value['values']})";
        //将对应数据写入user表
        $result4 = nl_execute_by_db($sql4, $aaa_dc->db());
        if (!$result4) {
            save_error_info($sql4);
        }
        if ($result2===false || !$result3===false || !$result4===false) {
            //收集错误数据
            $error_data[] = $insert_data;
        }
    }
    //数据操作成功与否,都将其从队列中删除
    del_new_insert_data($boss_redis, $insert_data_arr[$key]);
}

if(count($error_data)>1) {
    //将同步失败的数据写入文件
    die('数据同步已完成,有部分错误数据请确认...');
}else{
    die("数据同步完成,该次同步无错误数据...");
}


