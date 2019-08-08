<?php

Route::group(['as' => 'volunteers.'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
        Route::get('/', 'VolunteerApiController@index')->name('list');
        Route::apiResource('volunteers', 'VolunteerApiController');
        Route::get('export-volunteers/print', 'VolunteerApiController@print')->name('print');
        Route::get('export-volunteers/print-profile/{volunteer}', 'VolunteerApiController@printProfile')->name('print-profile');
    });
});