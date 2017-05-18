package com.jdbc;

/**
 * Created by zhaosuji on 2016/11/29.
 */
public class langTest {
    public static void main(String args[]){
//        int对象
        Integer a = new Integer("555");
        Integer b = new Integer(5551);
        Integer c = new Integer(555);

//        a>b 1 a=b 0 a<b -1
        System.out.println(a.compareTo(b));
        System.out.println(b.compareTo(a));
//        bool(true)
        System.out.println(c.equals(a));
//        将对应的对象转化成对象
        System.out.println(c.toString()+1);
        System.out.println(c+1);

//        字符串对象
        String s1 = new String("123");
        String s2 = "start";
        String s3 = "end";
        String s4 = "555";
//        string对象字符的长度
        System.out.println(s1.length());
//        字符串连接操作
        System.out.println(s2+s3);
        System.out.println(s2.concat(s3));
        String target = "    this is target    ";
        System.out.println(target);
        System.out.println(target.substring(target.indexOf("this")));
        System.out.println(target.trim());

//        stringbuffer操作(可以进行字符串的修改操作)
        StringBuffer b1 = new StringBuffer("testBuffer");
        //在字符串尾部添加
        b1.append("123");
        System.out.println(b1);
//        在字符串中添加
        b1.insert(2,"222");
        System.out.println(b1);

        //math类
        Integer num1 = 30;
//        Math.random();
        Integer num2 = 50;
        //返回两者的最小值
        System.out.println(Math.min(num1,num2));
        System.out.println(Math.max(num1,num2));
        System.out.println(Math.random());
    }
}
