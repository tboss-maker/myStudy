<?php
/**
 * author:zhaosuji
 * time: 2016/10/31  15:46
 **/
$conn = new MongoClient();
//可以简写为
//$conn=new Mongo(); #连接本地主机,默认端口.
//$conn=new Mongo(“172.21.15.69″); #连接远程主机
//$conn=new Mongo(“xiaocai.loc:10086″); #连接指定端口远程主机
//$conn=new Mongo(“xiaocai.loc”,array(“replicaSet”=>true)); #负载均衡
//$conn=new Mongo(“xiaocai.loc”,array(“persist”=>”t”)); #持久连接
//$conn=new Mongo(“mongodb://sa:123@localhost”); #带用户名密码

#选择test数据库
$db=$conn->test;
//$db=$conn->selectDB("test"); #第二种写法

#选择集合(选择"表")
$collection=$db->user;
//$collection=$db->selectCollection("user"); #第二种写法

#插入操作

$data=array("uid"=>"zz123","user_name"=>"张三");
$result=$collection->insert($data); #简单插入
echo "插入数据的id".$data["_id"];

exit;

#插入操作   安全插入
$data=array("uid"=>"zz124","user_name"=>"李四");
$result=$collection->insert($data,true); #用于等待MongoDB完成操作,以便确定是否成功.(当有大量记录插入时使用该参数会比较有用)

#修改操作
$where=array("uid"=>"zz123result");
$newdata=array("user_name"=>"张三三","tel"=>"123456789");
$result=$collection->update($where,array('$set'=>$newdata));

#替换更新
$where=array("uid"=>"zz24");
$newdata=array("user_age"=>"22","tel"=>"123456789");
$result=$collection->update($where,$newdata);


#批量更新
$where=array("uid"=>'zz');
$newdata=array("user_name"=>"zz","money"=>1000);
$result=$collection->update($where,array('$set'=>$newdata),array('multiple'=>true));

#自动累加
$where=array('money'=>1000);
$newdata=array('user_name'=>'edit');
$result=$collection->update($where,array('$set'=>$newdata,'$inc'=>array('money'=>-5)));


#删除节点
$where=array('uid'=>'zz124');
$result=$collection->update($where,array('$unset'=>'tel'));//删除节点tel

#删除数据
$collection->remove(array('uid'=>'zz124'));

#删除指定MongoId
$id = new MongoId('4d638ea1d549a02801000011');
$collection->remove(array('_id'=>(object)$id));

#查询数据   注:$gt为大于、$gte为大于等于、$lt为小于、$lte为小于等于、$ne为不等于、$exists不存在
echo 'count:'.$collection->count()."<br>"; #全部
echo 'count:'.$collection->count(array('uid'=>'zz123'))."<br>"; #可以加上条件
echo 'count:'.$collection->count(array('age'=>array('$gt'=>10,'$lte'=>30)))."<br>"; #大于50小于等于74
echo 'count:'.$collection->find()->limit(5)->skip(0)->count(true)."<br>"; #获得实际返回的结果数

#集合中所有文档
$cursor = $collection->find()->snapshot();
foreach ($cursor as $id => $value) {
    echo "$id: "; var_dump($value);
    echo "<br>";
}

#查询一条数据
$cursor = $collection->findOne();

#排除列  false为不显示
$cursor = $collection->find()->fields(array("age"=>false,"tel"=>false));
#指定列  true为显示
$cursor = $collection->find()->fields(array("user_name"=>true));

#(存在tel,age节点) and age!=0 and age<50
$where=array('tel'=>array('$exists'=>true),'age'=>array('$ne'=>0,'$lt'=>50,'$exists'=>true));
$cursor = $collection->find($where);

#分页获取结果集
$cursor = $collection->find()->limit(5)->skip(0);

#排序
$cursor = $collection->find()->sort(array('age'=>-1,'type'=>1)); #1表示降序 -1表示升序,参数的先后影响排序顺序
#索引
$collection->ensureIndex(array('age' => 1,'money'=>-1)); #1表示降序 -1表示升序
$collection->ensureIndex(array('age' => 1,'money'=>-1),array('background'=>true)); #索引的创建放在后台运行(默认是同步运行)
$collection->ensureIndex(array('age' => 1,'money'=>-1),array('unique'=>true)); #该索引是唯一的



#取得查询结果
$cursor = $collection->find();
$array=array();
foreach ($cursor as $id => $value) {
    $array[]=$value;
}

#关闭连接
$conn->close(); 