<?php

// ============================================================= Back-end
Route::prefix(config('admin.prefix'))->middleware('auth:admin')->name('admin.')->group(function() {
    Route::group(['prefix' => 'settings'], function () {
        Route::apiResource('/province', 'ProvinceApiController');
        Route::apiResource('/city', 'CityApiController');
        Route::apiResource('/subdistrict', 'SubdistrictApiController');
        Route::apiResource('/village', 'VillageApiController');
    });
    
    Route::apiResource('volunteers', 'VolunteerApiController');
});

// ============================================================= Front-end
Route::prefix('app')->group(function () {
    Route::prefix('area')->group(function() {
        Route::get('provinces', 'ProvinceApiController@index')->name('province.list');
        Route::get('cities', 'CityApiController@index')->name('city.list');
        Route::get('subdistricts', 'SubdistrictApiController@index')->name('subdistrict.list');
        Route::get('urbanvillages', 'VillageApiController@index')->name('village.list');
    });
    
    Route::post('volunteer/signup', 'VolunteerApiController@store');
});