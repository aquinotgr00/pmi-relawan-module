<?php

// ============================================================= Back-end
Route::group(['prefix' => 'admin/settings', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::apiResource('/province', 'ProvinceApiController');
    Route::apiResource('/city', 'CityApiController');
    Route::apiResource('/subdistrict', 'SubdistrictApiController');
    Route::apiResource('/village', 'UrbanVillageApiController');
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


// ============================================================= Front-end

Route::group(['prefix' => 'app/area'], function () {
    Route::get('/provinces/', 'ProvinceApiController@index')->name('province.list');
    Route::get('/cities/', 'CityApiController@index')->name('city.list');
    Route::get('/subdistricts/', 'SubdistrictApiController@index')->name('subdistrict.list');
    Route::get('/urbanvillages/', 'UrbanVillageApiController@index')->name('urbanvillage.list');
});

Route::group(['prefix' => 'app/events', 'middleware'=>'auth:api'], function () {
    Route::apiResource('/report', 'EventReportApiController');
    Route::apiResource('/partisipants', 'EventPartisipantApiController');
    Route::apiResource('/activities', 'EventActivityApiController');
});

Route::group(['prefix' => 'app/member'], function () {
    Route::get('/type/', 'MemberTypeApiController@index')->name('member.type.list');
    Route::get('/subtype/', 'SubMemberTypeApiController@index')->name('member.subtype.list');
    Route::get('/unit/', 'UnitVolunteerApiController@index')->name('member.unit.list');
});