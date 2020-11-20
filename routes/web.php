<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes();
//登陆模块
Route::group(['namespace' => "Admin"], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('dologin', 'LoginController@dologin')->name('dologin');
    Route::get('logout', 'LoginController@logout')->name('logout');

});
//清除缓存
Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    return json_encode(["code" => 1, "msg" => "服务端清理缓存成功"]);

})->name('clear-cache');

Route::group(['namespace' => "Admin", 'middleware' => 'auth:admin'], function () {
    //后台控制台
    Route::get('/', 'IndexController@index')->name('/');

    //管理员日志路由
    Route::get('log', 'LogController@index')->name('log');
    Route::get('log/getlist', 'LogController@getList')->name('log/getlist');
    Route::get('log/del/{id?}', 'LogController@del')->name('log/del');
    
    // Route::get('wenxin/getaccesstoken', 'WeixinController@getAccessToken')->name('wenxin/getaccesstoken');
    


    //菜单路由
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::get('menu/getlist', 'MenuController@getList')->name('menu/getlist');
    Route::any('menu/add', 'MenuController@add')->name('menu/add');
    Route::any('menu/edit/{id?}', 'MenuController@edit')->where('id', '[0-9]+')->name('menu/edit');
    Route::get('menu/del/{id?}', 'MenuController@del')->where('id', '[0-9]+')->name('menu/del');

    //管理员路由
    Route::get('user', 'UserController@index')->name('user');
    Route::get('user/getlist', 'UserController@getList')->name('user/getlist');
    Route::any('user/add', 'UserController@add')->name('user/add');
    Route::any('user/edit/{id?}', 'UserController@edit')->where('id', '[0-9]+')->name('user/edit');
    Route::get('user/del/{id?}', 'UserController@del')->where('id', '[0-9]+')->name('user/del');
    Route::any('user/baseinfo', 'UserController@baseInfo')->name('user/baseinfo');
    Route::any('user/imgupload', 'UserController@imgUpload')->name('user/imgupload');

    //宠物二维码路由
    Route::get('codes', 'CodesController@index')->name('codes');
    Route::get('codes/getlist', 'CodesController@getList')->name('codes/getlist');
    Route::any('codes/add', 'CodesController@add')->name('codes/add');
    Route::any('codes/edit/{id?}', 'CodesController@edit')->where('id', '[0-9]+')->name('codes/edit');
    Route::get('codes/del/{id?}', 'CodesController@del')->where('id', '[0-9]+')->name('codes/del');
    Route::get('codes/export', 'CodesController@export')->name('codes/export');
    Route::post('codes/clear', 'CodesController@clear')->name('codes/clear');
    Route::post('codes/chooseDownload', 'CodesController@chooseDownload')->name('codes/chooseDownload');
    //宠物管理路由
    Route::get('pet', 'PetController@index')->name('pet');
    Route::get('pet/getlist', 'PetController@getList')->name('pet/getlist');
    Route::any('pet/add', 'PetController@add')->name('pet/add');
    Route::any('pet/edit/{id?}', 'PetController@edit')->where('id', '[0-9]+')->name('pet/edit');
    Route::get('pet/del/{id?}', 'PetController@del')->where('id', '[0-9]+')->name('pet/del');
    Route::get('pet/export', 'PetController@export')->name('pet/export');

    //宠物丢失列表
    Route::get('petlost', 'PetLostController@index')->name('petlost');
    Route::get('petlost/getlist', 'PetLostController@getList')->name('petlost/getlist');
    Route::any('petlost/add', 'PetLostController@add')->name('petlost/add');
    Route::any('petlost/edit/{id?}', 'PetLostController@edit')->where('id', '[0-9]+')->name('petlost/edit');
    Route::get('petlost/del/{id?}', 'PetLostController@del')->where('id', '[0-9]+')->name('petlost/del');
    
    //宠物返家寄语模板列表
    Route::get('petremark', 'PetRemarkController@index')->name('petremark');
    Route::get('petremark/getlist', 'PetRemarkController@getList')->name('petremark/getlist');
    Route::any('petremark/add', 'PetRemarkController@add')->name('petremark/add');
    Route::any('petremark/edit/{id?}', 'PetRemarkController@edit')->where('id', '[0-9]+')->name('petremark/edit');
    Route::get('petremark/del/{id?}', 'PetRemarkController@del')->where('id', '[0-9]+')->name('petremark/del');


    //会员路由
    Route::get('member', 'MemberController@index')->name('member');
    Route::get('member/getlist', 'MemberController@getList')->name('member/getlist');
    Route::any('member/add', 'MemberController@add')->name('member/add');
    Route::any('member/edit/{id?}', 'MemberController@edit')->where('id', '[0-9]+')->name('member/edit');
    Route::get('member/del/{id?}', 'MemberController@del')->where('id', '[0-9]+')->name('member/del');
    Route::get('member/export', 'MemberController@export')->name('member/export');

    //会员扫码日志路由
    Route::get('memberlog', 'MemberLogController@index')->name('memberlog');
    Route::get('memberlog/getlist', 'MemberLogController@getList')->name('memberlog/getlist');
    Route::get('memberlog/del/{id?}', 'MemberLogController@del')->where('id', '[0-9]+')->name('memberlog/del');

    //系统配置路由
    Route::get('config', 'ConfigController@index')->name('config');
    Route::get('config/getlist', 'ConfigController@getList')->name('config/getlist');
    Route::any('config/add', 'ConfigController@add')->name('config/add');
    Route::any('config/edit/{id?}', 'ConfigController@edit')->where('id', '[0-9]+')->name('config/edit');
    Route::any('config/editconf', 'ConfigController@editConfig')->where('id', '[0-9]+')->name('config/editconf');
    Route::any('config/imgupload', 'ConfigController@imgUpload')->name('config/imgupload');
    Route::get('config/del/{id?}', 'ConfigController@del')->where('id', '[0-9]+')->name('config/del');

    //文章管理路由
    Route::get('article', 'ArticleController@index')->name('article');
    Route::get('article/getlist', 'ArticleController@getList')->name('article/getlist');
    Route::any('article/add', 'ArticleController@add')->name('article/add');
    Route::any('article/edit/{id?}', 'ArticleController@edit')->where('id', '[0-9]+')->name('article/edit');
    Route::get('article/del/{id?}', 'ArticleController@del')->where('id', '[0-9]+')->name('article/del');
    Route::any('article/imgupload', 'ArticleController@imgUpload')->name('article/imgupload');



});



