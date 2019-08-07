<?php

// ============================================================= Areas
Route::group(['prefix' => 'admin/city', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/', 'CityApiController@index')->name('city.list');
    Route::get('/{id}', 'CityApiController@show')->name('city.show');
    Route::post('/', 'CityApiController@store')->name('city.store');
    Route::post('/{id}', 'CityApiController@update')->name('city.update');
    Route::delete('/{id}', 'CityApiController@destroy')->name('city.delete');
});

Route::group(['prefix' => 'admin/province', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/', 'ProvinceApiController@index')->name('province.list');
    Route::get('/{id}', 'ProvinceApiController@show')->name('province.show');
    Route::post('/', 'ProvinceApiController@store')->name('province.store');
    Route::post('/{id}', 'ProvinceApiController@update')->name('province.update');
    Route::delete('/{id}', 'ProvinceApiController@destroy')->name('province.delete');
});

Route::group(['prefix' => 'admin/subdistrict', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/', 'SubdistrictApiController@index')->name('subdistrict.list');
    Route::get('/{id}', 'SubdistrictApiController@show')->name('subdistrict.show');
    Route::post('/', 'SubdistrictApiController@store')->name('subdistrict.store');
    Route::post('/{id}', 'SubdistrictApiController@update')->name('subdistrict.update');
    Route::delete('/{id}', 'SubdistrictApiController@destroy')->name('subdistrict.delete');
});

Route::group(['prefix' => 'admin/urbanvillage', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/', 'UrbanVillageApiController@index')->name('urbanvillage.list');
    Route::get('/{id}', 'UrbanVillageApiController@show')->name('urbanvillage.show');
    Route::post('/', 'UrbanVillageApiController@store')->name('urbanvillage.store');
    Route::post('/{id}', 'UrbanVillageApiController@update')->name('urbanvillage.update');
    Route::delete('/{id}', 'UrbanVillageApiController@destroy')->name('urbanvillage.delete');
});

Route::group(['prefix' => 'admin/member', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::apiResource('type', 'MemberTypeApiController');
    Route::apiResource('subtype', 'SubMemberTypeApiController');
    Route::apiResource('unit', 'UnitVolunteerApiController');
});

Route::group(['prefix' => 'admin/member', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::apiResource('type', 'MemberTypeApiController');
    Route::apiResource('subtype', 'SubMemberTypeApiController');
    Route::apiResource('unit', 'UnitVolunteerApiController');
});

Route::group(['prefix' => 'admin/events', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::put('/report/{report}', 'EventReportApiController@update')->name('event-report.update');
    Route::delete('/report/{report}', 'EventReportApiController@destroy')->name('event-report.delete');
    Route::get('/report-only-transed', 'EventReportApiController@onlyTrashed')->name('event-report.only-transed');
});        

Route::group(['prefix' => 'app/events', 'middleware'=>'auth:api'], function () {
    Route::apiResource('/report', 'EventReportApiController');
    Route::apiResource('/partisipants', 'EventPartisipantApiController');
    Route::apiResource('/activities', 'EventActivityApiController');
});

Route::group(['prefix' => 'app/area'], function () {
    Route::get('/provinces/', 'ProvinceApiController@index')->name('province.list');
    Route::get('/cities/', 'CityApiController@index')->name('city.list');
    Route::get('/subdistricts/', 'SubdistrictApiController@index')->name('subdistrict.list');
    Route::get('/urbanvillages/', 'UrbanVillageApiController@index')->name('urbanvillage.list');
});

Route::group(['prefix' => 'app/member'], function () {
    Route::get('/type/', 'MemberTypeApiController@index')->name('member.type.list');
    Route::get('/subtype/', 'SubMemberTypeApiController@index')->name('member.subtype.list');
    Route::get('/unit/', 'UnitVolunteerApiController@index')->name('member.unit.list');
});