<?php

// ============================================================= Back-end
Route::group(['prefix' => 'admin/settings', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::apiResource('/province', 'ProvinceApiController');
    Route::apiResource('/city', 'CityApiController');
    Route::apiResource('/subdistrict', 'SubdistrictApiController');
    Route::apiResource('/village', 'UrbanVillageApiController');
});

// ============================================================= Front-end

Route::group(['prefix' => 'app/area'], function () {
    Route::get('/provinces/', 'ProvinceApiController@index')->name('province.list');
    Route::get('/cities/', 'CityApiController@index')->name('city.list');
    Route::get('/subdistricts/', 'SubdistrictApiController@index')->name('subdistrict.list');
    Route::get('/urbanvillages/', 'UrbanVillageApiController@index')->name('urbanvillage.list');
});