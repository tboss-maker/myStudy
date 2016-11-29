<?php
/**
 * author:zhaosuji
 * time: 2016/11/28  17:22
 **/

/**
 * php redis使用
 */
$redis = new Redis();
//链接
$redis->pconnect('127.0.0.1','6379','3600');
//设置字符串和有效时间
$redis->set('test','123',30);
//获取字符串
echo $redis->get('test');
//删除某个值
$redis->delete('test');
//判断某个值存在
$redis->exists('test');
//事务处理
//开始事务

//清空redis数据库
$redis->flushAll();

//getMultipe获取多键名,返回的是一个list
$redis->set('a',111);
$redis->set('b',222);
$redis->set('c',333);
echo '<pre>';
print_r($redis->getMultiple(array('a','b','c')));

//list相关操作(从左往右添加)
$redis->lpush('list','a','b','c');
//$redis->rpush('list','a','b','c');
print_r($redis->lRange('list',0,-1));
//$redis->rPop();
//从左往右移除list
//echo $redis->lpop('list');
//返回集合长度
$redis->llen('list');
print_r($redis->lRange('list',0,-1));
//将list中第0个元素的值重新赋值为aa
$redis->lset('list',0,'aa');
//print_r($redis->lrange('list',0,-1));
//获取队列中第i个元素的值(lget和lindex一样)
echo $redis->lget('list',1);
echo $redis->lindex('list',1);

//无序集合操作
//给set添加元素()
$redis->sAdd('set','111');
$redis->sadd('set','222');
$redis->sadd('set','333');
$redis->sAdd('set','111');
//$redis->
//从集合中移除值为222的元素
//$redis->sRem('set','222');
//获取无需集合的全部元素
//print_r($redis->sMembers('set'));
//查看无需集合中是否包含有某元素
var_dump($redis->sContains('set','222'));
//无需集合的元素个数
echo $redis->ssize('set').'<br/>';
//从无需集合中随机获取一个元素,并且从原集合中删除(可以用作抽奖)
//echo $redis->sPop('set');
//从无需集合中随机获取一个元素,并且不从元集合中删除
echo $redis->sRandMember('set');
//无需集合并集并返回
$redis->sUnion('set1','set2','set3');
//无序集合并集,并将其存储到新集合newUnion中
$redis->sUnionStore('newUnion','set1','set2','set3');
print_r($redis->sMembers('set'));

//字符串操作
$redis->set('test','name');
echo $redis->get('test').'<br/>';
//在键名为test的字符串后再加上新的字符
$redis->append('test','555');
echo $redis->get('test');

//有序集合操作
$redis->zadd('score',100,'111');
$redis->zadd('score',200,'222');
$redis->zadd('score',300,'333');
//获取所有有序集合
print_r($redis->zRange('score',0,-1));
//删除有序集合中值为222的元素
$redis->zDelete('score','222');
//获取有序集合的元素个数
$redis->zSize('score');

//Hash(hashtable)操作
//给一个hash添加一条记录(key value)
$redis->hset('h','key1','value1');
$redis->hset('h','key2','value2');
$redis->hset('h','key3','value3');
//hashtable不会重复,值会覆盖
$redis->hset('h','key3','value33');
//获取名称为h的hashtable的全部数据
print_r($redis->hGetAll('h'));
//获取hashtable中健名为key1的值
echo $redis->hget('h','key1');
//判断hashtable为h的表中key1建名是否存在
var_dump($redis->hExists('h','key11'));
//返回名为h的hashtable中所有的key值
print_r($redis->hkeys('h'));
//返回名为h的hashtable中所有的value值
print_r($redis->hVals('h'));
$redis->

$arr = array(
    'name' => 'jim',
    'age' => '24',
    'money' => '500'
);
//hashtable中批量添加数据(可以追加)
$redis->hMset('h',$arr);
//print_r($redis->hgetAll('h'));
//从hashtable中批量查询数据(建名数组)
print_r($redis->hMGet('h',array('name','money')));


