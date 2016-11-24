<?php
/**
 * author:zhaosuji
 * time: 2016/11/20  16:57
 **/
function nl_get_time_by_period($period=''){
    //获取日期条件
    if($period==''){
        //无参数(返回空数组)
        return array();
    }
    if($period=='yesterday'){
        //昨天
        $time = date('Y-m-d',strtotime("-1 day"));
        $time1 = date('Y-m-d',strtotime("-1 day"));
    }elseif($period=='this week'){
        //上一周
        $time = date('Y-m-d',strtotime("-1 week"));
        $time1 = date('Y-m-d');
    }elseif($period=='this month'){
        //本月第一天
        $time = date('Y-m-01',strtotime('this month'));
        //本月最后一天
        $time1 = date('Y-m-d',strtotime("$time + 1 month - 1 day"));
    }elseif($period=='this season'){
        //本季度
        //获取当前季度
        $season = ceil(date('m')/3);
        //当前季度开始日期
        $begin_month = $season*3-2;
        $time = date("Y-$begin_month-01");
        //当前季度结束日期
        $over_month = $season*3;
        $time1 = date("Y-$over_month-31");
    }elseif($period=='last season'){
        //上季度
        $last_season = ceil(date('m')*3)-1;
        //上一季度不能为非正整数
        $last_season = $last_season <= 0 ? $last_season : 1;
        //开始日期
        $begin_month = $last_season*3-2;
        $time = date("Y-$begin_month-01");
        //结束日期
        $over_month = $last_season*3;
        $time1 = date("Y-$over_month-31");
    }else{
        //参数格式错误
        return false;
    }
    $time_arr = array($time,$time1);
    return 	$time_arr;
}
echo '<pre>';
print_r(nl_get_time_by_period('last season'));