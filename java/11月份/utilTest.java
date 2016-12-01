package com.jdbc;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.*;
import javax.xml.crypto.Data;

/**
 * Created by zhaosuji on 2016/11/30.
 * util提供了一些实用的方法和数据结构
 */
public class utilTest {
    public static void main(String args[]){
        Date time = new Date();
        //获取时间戳
        System.out.println(time.getTime());
//        System.out.println(time.getYear());
//        格式化时间
        DateFormat newTime = new SimpleDateFormat("YYYY-MM-dd HH:mm:ss");
        String t1 = newTime.format(new Date());
        System.out.println(t1);

        //Calendar类操作
//      创建Calendar对象
        Calendar ca = Calendar.getInstance();
//        可以初始化calendar对象(重新赋值)
        ca.setTime(new Date());
        System.out.println(ca.get(ca.YEAR));
        //随机数类型
        Random a = new Random();
        //返回32位的整型随机数
        System.out.println(a.nextInt());
        //返回long型随机数
        System.out.println(a.nextLong());
        System.out.println(a.nextDouble());
        System.out.println(a.nextDouble());
    }
}
