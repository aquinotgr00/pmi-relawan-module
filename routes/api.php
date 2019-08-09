<?php

// ============================================================= Back-end

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::prefix('settings')->group(function() {
        Route::apiResource('/province', 'ProvinceApiController');
        Route::apiResource('/city', 'CityApiController');
        Route::apiResource('/subdistrict', 'SubdistrictApiController');
        Route::apiResource('/village', 'VillageApiController');
    });

    Route::prefix('member')->group(function() {
        Route::apiResource('membership', 'MembershipApiController');
        Route::apiResource('unit', 'UnitVolunteerApiController');
    });

    Route::prefix('events')->group(function() {
        Route::apiResource('/report', 'EventReportApiController');
        Route::get('/report-only-transed', 'EventReportApiController@onlyTrashed')->name('event-report.only-transed');
        Route::put('/partisipants/{partisipants}', 'EventPartisipantApiController@update')->name('event-partisipant.update');
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

    Route::prefix('events')->group(function() {
        Route::apiResource('/report', 'EventReportApiController');
        Route::apiResource('/partisipants', 'EventPartisipantApiController');
        Route::apiResource('/activities', 'EventActivityApiController');
    });
    
    Route::prefix('member')->group(function() {
        Route::get('/membership', 'MembershipApiController@index')->name('membership.list');
        Route::get('/unit', 'UnitVolunteerApiController@index')->name('member.unit.list');
    });

    Route::post('volunteer/signup', 'VolunteerApiController@store');

});