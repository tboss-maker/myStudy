<?php
/**
 * author:zhaosuji
 * time: 2016/11/21  15:09
 **/
//根据不同模式不同参数查询
/**
 * model=1,2,3
 * 不同模式 对应不同传入的参数
 */
class demo{
    //购买订单数量统计
    public function count_buy_order()
    {
        //获取统计模式
        $mode = $this->arr_request['nns_mode'] ? $this->arr_request['nns_mode'] : '';
        if (empty($mode)) {
            return $this->format_return('empty', 'nns_mode');
        }
        //获取时间段
        $period = $this->arr_request['nns_period'] ? $this->arr_request['nns_period'] : '';
        if (empty($period)) {
            return $this->format_return('empty', 'nns_period');
        }
        //获取时间段
        $period = nl_get_time_by_period($period);
        //模式
        switch ($mode)
        {
            case 'overview':
            {
                //订单总览
                $period = '1';
                $ret = nl_buy_order::count_buy_order_by_status($this->aaa_dc, $period);
                break;
            }
            case 'product_num':
            {
                //根据产品id获取产品包销量
                $product_id = $this->arr_request['nns_product_id'] ? $this->request['nns_product_id'] : '';
                if(empty($product_id)){
                    return $this->format_return('empty','nns_product_id');
                }
                //生成查询条件
                $query = "nns_product='{$product_id}'";
                $ret = nl_buy_order::get_num_by_query($this->aaa_dc,$query,$period);
                if (!$ret)
                {
                    return $this->format_return(false);
                }
                break;
            }
            case 'cp_order':
            {
                $cp_id = $this->arr_request['nns_cp_id'] ? $this->arr_request['nns_cp_id'] : '';
                if(empty($cp_id)){
                    return $this->format_return('empty','nns_cp_id');
                }
                $query = "nns_cp_id='{$cp_id}'";
                $ret = nl_buy_order::get_num_by_query($this->aaa_dc,$query,$period);
                if($ret){
                    return $this->format_return(false);
                }
            }
            case 'partner_num':
            {
                $parner_id = $this->arr_request['nns_partner_id'] ? $this->arr_request['nns_partner_id'] : '';
                if(empty($parner_id)){
                    return $this->format_return('empty','nns_parner_id');
                }
                $query = "";

            }
            case 'channel_num':
            {
                ;
            }
            case 'user_from':
            {
                ;
            }
            case 'custom':
            {
                ;
            }
        }
        return $this->format_return($ret);
    }
}

class nl_buy_order{
    public static function get_num_by_query($dc,$query,$period){
        $sql = "select count(nns_id) as order_num from nns_buy_order where '{$query}' AND nns_crea_time between '{$period[0]}' and '{$period[1]}'";
        return nl_query_by_db($sql, $dc->db());
    }
}

/**
 * 根据时间格式获取查询起始时间
 * @param string period 时间格式
 * return bool/array 目标查询时间的起始和结束日期
 */
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