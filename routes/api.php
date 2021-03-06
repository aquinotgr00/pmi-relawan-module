<?php

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::prefix('settings')->group(function() {
        Route::apiResources([
            'province'=>'ProvinceApiController',
            'city'=>'CityApiController',
            'subdistrict'=>'SubdistrictApiController',
            'village'=>'VillageApiController',
            'membership'=>'MembershipApiController',
            'unit'=>'UnitVolunteerApiController'
        ]);
    });

    Route::prefix('dashboard')->group(function() {
        Route::get('/amount/volunteer', 'MembershipApiController@getAmountVolunteers')->name('dashboard.amount.volunteer');
    });

    Route::prefix('events')->group(function() {
        Route::apiResources([
            'report'=>'EventReportApiController',
            'participants'=>'EventParticipantApiController',
            'comment'=>'ChatApiController'
        ]);
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
        Route::get('memberships', 'MembershipApiController@index')->name('membership.list');
        Route::get('units', 'UnitVolunteerApiController@index')->name('member.unit.list');
        Route::get('check-for-update', 'SettingsApiController@checkForUpdate')->name('settings.check-for-update');
    });

    Route::prefix('events')->middleware('auth:api')->group(function() {
        Route::apiResource('report', 'EventReportApiController');
        Route::apiResource('participants', 'EventParticipantApiController');
        Route::apiResource('comment', 'ChatApiController')->only(['index','store']);
    });
    
    Route::prefix('volunteer')->group(function() {
        Route::post('signup', 'VolunteerApiController@store');
        Route::middleware('auth:api')->group(function () {
            Route::get('profile', 'VolunteerApiController@profile');
            Route::post('update/{volunteer}', 'VolunteerApiController@update');
        });
    });

});