<?php
/**
 * author:zhaosuji
 * time: 2016/11/19  17:05
 **/
$mysql = new mysqli('localhost','root','','test');
$time = date('Y-m-d',strtotime('1990-11-26'));
$time2 = date('Y-m-d',strtotime("-1 day"));
//过去一年
$time3 = date('Y-m-d',strtotime("-1 year"));
//下周
$time4 = date('Y-m-d',strtotime("+1 week"));
//本月
$time5 = date('Y-m-d',strtotime("this month"));
//上月全部

echo $time5;exit;
//echo $time3;exit;
$sql = "select * from old_table where time2 between '{$time4}' and '{$time5}'";
$result = $mysql->query($sql);
echo '<pre>';
while($row = $result->fetch_row()){
    print_r($row);
}

//1.获取上个月第一天及最后一天.
   echo date('Y-m-01', strtotime('-1 month'));
   echo "<br/>";
   echo date('Y-m-t', strtotime('-1 month'));
   echo "<br/>";
//2.获取当月第一天及最后一天.
$BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
   echo $BeginDate;
   echo "<br/>";
   echo date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
   echo "<br/>";
//3.获取当天年份、月份、日及天数.
   echo " 本月共有:".date("t")."天";
   echo " 当前年份".date('Y');
   echo " 当前月份".date('m');
   echo " 当前几号".date('d');
   echo "<br/>";
//4.使用函数及数组来获取当月第一天及最后一天,比较实用
   function getthemonth($date)
   {
       $firstday = date('Y-m-01', strtotime($date));
       $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
       return array($firstday,$lastday);
   }
   $today = date("Y-m-d");
   $day=getthemonth($today);
   echo "当月的第一天: ".$day[0]." 当月的最后一天: ".$day[1];
   echo "<br/>";

