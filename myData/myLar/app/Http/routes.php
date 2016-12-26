<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('admin.login');
});
Route::get('admin/login',function(){
    return view('admin.login');
});
Route::any('admin/index',function(){
    return view('admin.index');
});
Route::get('admin/email/mailbox',function(){
    return view("admin.email.mailbox");
});
Route::get('admin/email/mail_detail',function(){
    return view("admin.email.mail_detail");
});
Route::get('admin/email/mail_compose',function(){
    return view("admin.email.mail_compose");
});
Route::any('admin/register',function(){
    return view("admin.register");
});

Route::any('admin/doRegister',['uses'=>'LoginController@adminRegister']);
Route::get('admin/editHead',function(){
    return view('admin.form_avatar');
});
Route::any('admin/adminLogin',['uses'=>'LoginController@adminLogin']);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
