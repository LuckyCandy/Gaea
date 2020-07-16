<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* 用户登录 */
Route::post('/login', 'LoginController@login');
/* 登录组 */
Route::middleware('auth:api')->group(function (){
    Route::middleware('admin.action')->group(function (){
        /* 根据id获取用户信息 */
        Route::get('/user/{user}', 'UserController@getById')->where('user', '[0-9]+');
        /* 根据id修改用户信息 */
        Route::post('/user/update/{id}', 'UserController@update');
        /* 获取用户列表 */
        Route::get('/user/list', 'UserController@list');
        /* 禁用or解禁用户 */
        Route::post('/user/block/{user}', 'UserController@blockOrNot')->where('user', '[0-9]+');
        /* 重置密码 */
        Route::post('/user/password/reset', 'UserController@resetPassword');
        /* 新增用户 */
        Route::post('/user/create', 'UserController@create');
        /* 日志列表 */
        Route::get('/log/list', 'LogController@list');
    });
    /* 获取用户信息 */
    Route::get('/user', 'UserController@user');
    /* 修改用户信息 */
    Route::post('/user/update', 'UserController@update');
    /*Redis Manager*/
    Route::namespace('Redis')->prefix('redis')->group(function(){
        /* 创建 */
        Route::post('/client/create', 'ClientController@create');
        /* 更新 */
        Route::post('/client/update/{id}', 'ClientController@update');
        /* 删除 */
        Route::put('/client/delete/{id}', 'ClientController@update');
        /* 选择服务 */
        Route::get('/client/choose/{id}', 'ClientController@setOnWork');
        /* 列表 */
        Route::get('/client/list', 'ClientController@list');
        /* 需要使用到redis的操作 */
        Route::middleware('custom.redis')->group(function() {
            /* 批量查询key */
            Route::post('/keys', 'Controller@keys');
            /* 获取key信息 */
            Route::post('/get', 'Controller@get');
            /* 删除key */
            Route::post('/delete', 'Controller@remove');
            /* 其他操作 */
            Route::post('/op', 'Controller@op');
        });
    });
});
