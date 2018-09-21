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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user','admin\UserController@index');
Route::post('/del','admin\UserController@del');
Route::post('/add','admin\UserController@add');
Route::post('/up','admin\UserController@upp');
Route::post('/edit','admin\UserController@edit');
Route::post('/edd','admin\UserController@edd');
//Route::post('/oto','admin\PhotoController@add');
Route::post('/oto','admin\UserController@oto');
            //        添加多图
Route::post('/login','admin\UserController@login');
Route::post('/search','admin\UserController@search');
Route::post('/opp','admin\UserController@ott');
                //    查询多图
Route::get('/imagetest','admin\UserController@imagetest');


