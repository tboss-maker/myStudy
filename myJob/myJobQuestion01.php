<?php
/**
 * author:zhaosuji
 * time: 2017/1/23  9:50
 * 场景:需要传入多视频的多属性,批量操作中可能既有增加又有更改操作
 **/

//数据结构为表结构式的对应
$video_id_arr = '1 , 2 , 3';
$attr_arr  = '5 | 2,5 | 1,2,5';
$product_id = '12345';


//1.数据结构重组
$video_id_arr = explode(',',$video_id_arr);
$attr_arr = explode('|',$attr_arr);

$new_arr = [];
for($i=0;$i<count($video_id_arr);$i++){
    $new_arr[$i]['video_id'] = $video_id_arr[$i];
    $new_arr[$i]['attr'] = $attr_arr[$i];
}

//2.根据product_id去查出产品包拥有的所有视频
$product_has_video_arr = getVideoByProductId($product_id);

//2.1(优化逻辑)这里如果产品包如果没有数据的话,就不用进行下面的过滤操作,直接跳到步骤5,新增数据添加

//3.根据产品包拥有的视频信息过滤传入的数据,将其分为两部分(1.用于修改的 2.用于增加的)
$update_arr = [];
$add_arr = [];
foreach($new_arr as $value){
    if(in_array($value['video_id'],$product_has_video_arr)){
        //存在的数据为修改数据
        $update_arr[] = $value;
    }else{
        //不存在的为新增数据
        $add_arr[] = $value;
    }
}
//3.1(优化逻辑)如果过滤的更新数据数组为空,则直接跳到步骤5,新增数据添加

//4.执行更新操作,可以逐一添加也可以批量添加
foreach($update_arr as $value){
    //执行更新操作
//    update_data(value);
}

//4.1(逻辑优化)更新完成后,对新增数据数组判断$add_arr,如果为空则直接结束操作
if(count($add_arr)==0){
    return;
}

//5.执行添加操作
foreach($add_arr as $value){
    //执行添加操作
//    add_data($value);
}

//6.结束执行
//return;

echo '修改数据:';
print_r($update_arr);
echo '添加数据:';
print_r($add_arr);


function getVideoByProductId($product_id){
    $arr = [1,2,5,6,7];
    return $arr;
}