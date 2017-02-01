<?php

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

Route::group(['as' => 'admin::', 'prefix' => 'admin', 'namespace'=>'Panel'], function () {

    Route::get('/logout', 'UserController@logout')->name('logout');

    Route::group(['middleware' => ['redirectAuthenticatedPanelUser']], function () {
        Route::get('/login', function(){
            return view('panel.user.login');
        })->name('login');

        Route::post('/login', 'UserController@postLogin')->name('postLogin');
    });

    Route::group(['middleware' => ['redirectUnauthenticatedPanelUser']], function () {
        Route::get('/', 'IndexController@index');

        Route::get('/{action}/{modelName}/{id?}', 'ActionsController@act')
            ->where('action', 'show|sort|create|edit')
            ->where('modelName', '[a-z0-9_]+')
            ->where('id', '[0-9]+')
            ->name('act');

        Route::post('/{action}/{modelName}/{id?}', 'ActionsController@act')
            ->where('action', 'save|drop|imageadd|imagedrop|gallerysort|imagetitles|bindexercise|unbindexercise|bindrecipe|unbindrecipe|bindsupplement|unbindsupplement')
            ->where('modelName', '[a-z0-9_]+')
            ->where('id', '[0-9]+');

        // Export lists to a .csv file
        //Route::post('/excel/export/{modelName}/{format}', 'ExcelController@export')->name('exportAsExcel');
    });
});