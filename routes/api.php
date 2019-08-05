<?php

// ============================================================= Areas
Route::group(['as' => 'areas.'], function () {
    Route::group(['prefix' => 'app', 'as' => 'app.'], function () {
        
    });
    
    Route::group(['prefix' => 'admin/city', 'as' => 'admin.'], function () {
        Route::get('/', 'CityApiController@index')->name('city.list');
        Route::get('/{id}', 'CityApiController@show')->name('city.show');
        Route::post('/', 'CityApiController@store')->name('city.store');
        Route::post('/{id}', 'CityApiController@update')->name('city.update');
        Route::delete('/{id}', 'CityApiController@destroy')->name('city.delete');
    });

    Route::group(['prefix' => 'admin/province', 'as' => 'admin.'], function () {
        Route::get('/', 'ProvinceApiController@index')->name('province.list');
        Route::get('/{id}', 'ProvinceApiController@show')->name('province.show');
        Route::post('/', 'ProvinceApiController@store')->name('province.store');
        Route::post('/{id}', 'ProvinceApiController@update')->name('province.update');
        Route::delete('/{id}', 'ProvinceApiController@destroy')->name('province.delete');
    });

    Route::group(['prefix' => 'admin/subdistrict', 'as' => 'admin.'], function () {
        Route::get('/', 'SubdistrictApiController@index')->name('subdistrict.list');
        Route::get('/{id}', 'SubdistrictApiController@show')->name('subdistrict.show');
        Route::post('/', 'SubdistrictApiController@store')->name('subdistrict.store');
        Route::post('/{id}', 'SubdistrictApiController@update')->name('subdistrict.update');
        Route::delete('/{id}', 'SubdistrictApiController@destroy')->name('subdistrict.delete');
    });

    Route::group(['prefix' => 'admin/urbanvillage', 'as' => 'admin.'], function () {
        Route::get('/', 'UrbanVillageApiController@index')->name('urbanvillage.list');
        Route::get('/{id}', 'UrbanVillageApiController@show')->name('urbanvillage.show');
        Route::post('/', 'UrbanVillageApiController@store')->name('urbanvillage.store');
        Route::post('/{id}', 'UrbanVillageApiController@update')->name('urbanvillage.update');
        Route::delete('/{id}', 'UrbanVillageApiController@destroy')->name('urbanvillage.delete');
    });
    
});