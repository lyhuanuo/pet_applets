<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['namespace'  => "Api"], function () {
    //登录
    Route::any('login',                                         'LoginController@login')->name('login');
    
    Route::any('getPhone',                                      'LoginController@getPhone')->name('getPhone');
    //会员信息
    Route::get('getmemberinfo',                                 'MemberController@getMemberInfo')->name('getmemberinfo');
    //保存会员信息
    Route::post('savememberinfo',                               'MemberController@saveMemberInfo')->name('savememberinfo');
    
    Route::post('sendsms',                                      'MemberController@sendSms')->name('sendsms');
    //更换手机号
    Route::post('changePhone',                               'MemberController@changePhone')->name('changePhone');
    //获取地址
    Route::any('geographic',                                    'IndexController@geographic')->name('geographic');

    //二维码扫码信息
    Route::get('getcodeinfo',                                   'IndexController@getCodeInfo')->name('getcodeinfo');
    //获取扫码日志
    Route::any('getloglist',                                    'IndexController@getLogList')->name('getloglist');
    //保存扫码记录信息
    Route::any('savelog',                                       'IndexController@saveLog')->name('saveLog');
    //操作指南
    Route::get('getmanual',                                     'IndexController@getManual')->name('getmanual');
    //用户协议
    Route::get('getagreement',                                  'IndexController@getAgreement')->name('getagreement');

    //获取用户宠物列表
    Route::get('getpetlist',                                    'PetController@getPetList')->name('getpetlist');
    //获取单一宠物信息
    Route::get('getpetinfo',                                    'PetController@getPetInfo')->name('getpetinfo');

    Route::post('editpetinfo',                                  'PetController@editPetInfo')->name('editpetinfo');
    
    Route::post('imgupload',                                    'PetController@imgUpload')->name('imgupload');

    //添加宠物绑订二维码
    Route::post('addpet',                                       'PetController@addPet')->name('addpet');
    
    //删除宠物
    Route::post('delpet',                                       'PetController@delPet')->name('delpet');
   
    //获取放假寄语模板信息
    Route::get('getmessage',                                       'PetController@getMessageList')->name('getmessage');
    
    //宠物丢失添加
    Route::post('lostadd',                                      'PetController@lostAdd')->name('lostadd');
    //宠物丢失信息修改
    Route::post('lostedit',                                     'PetController@lostEdit')->name('lostedit');
    Route::post('statusedit',                                     'PetController@statusEdit')->name('statusedit');
    //宠物丢失列表
    Route::get('lostlist',                                      'PetController@lostPetList')->name('lostlist');
    //单个宠物丢失信息
    Route::get('lostinfo',                                      'PetController@getPetLostInfo')->name('lostinfo');

    //支付宝授权登录

    Route::any('aliLogin',                                      'LoginController@aliLogin')->name('aliLogin');
    //支付宝获取手机号
    Route::post('getAliPhone',                                  'LoginController@getAliPhone')->name('getAliPhone');



    
    
});
