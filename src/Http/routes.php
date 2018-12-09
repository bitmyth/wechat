<?php

use Illuminate\Support\Facades\Route;

/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/8
 * Time: 10:09 PM
 */

// echo
Route::get('/wechat', 'WechatController@echo')->name('wechat.echo');

//wechat config information
Route::get('/wechat/info', 'WechatController@info')->name('wechat.info');


Route::any('/wechat/payment/notify', 'WechatController@paymentNotify')->name('wechat.payment.notify');
