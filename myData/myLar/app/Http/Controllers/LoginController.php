<?php
/**
 * author:zhaosuji
 * time: 2016/11/30  19:49
 **/
namespace App\Http\Controllers;
use App\Http\Controllers;

class LoginController extends Controller{
    //后台登录
    public function adminLogin(){
        if($_REQUEST){
            return view('admin.index');
        }else{
            return view('admin.login');
        }
    }

    //后台用户注册
    public function adminRegister(){
        if($_REQUEST){

        }else{
            return view("admin.login");
        }
    }

}
