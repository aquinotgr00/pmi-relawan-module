<?php

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::prefix('settings')->group(function() {
        Route::apiResource('province', 'ProvinceApiController');
        Route::apiResource('city', 'CityApiController');
        Route::apiResource('subdistrict', 'SubdistrictApiController');
        Route::apiResource('village', 'VillageApiController');
        Route::apiResource('membership', 'MembershipApiController');
        Route::apiResource('unit', 'UnitVolunteerApiController');
    });

    Route::prefix('dashboard')->group(function() {
        Route::get('/amount/volunteer', 'MembershipApiController@getAmountVolunteers')->name('dashboard.amount.volunteer');
    });

    Route::prefix('events')->group(function() {
        Route::apiResource('/report', 'EventReportApiController');
        Route::get('/report-only-transed', 'EventReportApiController@onlyTrashed')->name('event-report.only-transed');
        Route::put('/participants/{participants}', 'EventParticipantApiController@update')->name('event-participant.update');
    });

    
    Route::get('/', 'VolunteerApiController@index')->name('list');
    Route::get('export-volunteers/print', 'VolunteerApiController@print')->name('print');
    Route::get('export-volunteers/print-profile/{volunteer}', 'VolunteerApiController@printProfile')->name('print-profile');
    Route::get('export-volunteers/print-html', 'VolunteerApiController@printHtml')->name('print-html');
    
    
    Route::apiResource('volunteers', 'VolunteerApiController');
});        


// ============================================================= Front-end

Route::prefix('app')->group(function () {

    Route::prefix('settings')->group(function() {
        Route::get('provinces', 'ProvinceApiController@index')->name('province.list');
        Route::get('cities', 'CityApiController@index')->name('city.list');
        Route::get('subdistricts', 'SubdistrictApiController@index')->name('subdistrict.list');
        Route::get('villages', 'VillageApiController@index')->name('village.list');
        Route::get('membership', 'MembershipApiController@index')->name('membership.list');
        Route::get('unit', 'UnitVolunteerApiController@index')->name('member.unit.list');
        Route::get('check-for-update', 'SettingsApiController@checkForUpdate')->name('settings.check-for-update');
    });

    Route::prefix('events')->middleware('auth:api')->group(function() {
        Route::apiResource('/report', 'EventReportApiController');
        Route::apiResource('/participants', 'EventParticipantApiController');
        Route::get('messages/{eventReport}', 'ChatApiController@showActivities');
        Route::post('message', 'ChatApiController@storeActivity');
    });
    
    Route::prefix('volunteer')->group(function() {
        Route::post('signup', 'VolunteerApiController@store');
        Route::get('profile', 'VolunteerApiController@show')->middleware('auth:api');
    });

});