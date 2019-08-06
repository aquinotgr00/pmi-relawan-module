<?php

Route::group(['as' => 'volunteers.'], function () {
    Route::group(['prefix' => 'admin/volunteers', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
        Route::get('list', 'VolunteerApiController@list')->name('list');
        Route::post('update/{id}', 'VolunteerApiController@update')->name('update');
        Route::get('delete/{id}', 'VolunteerApiController@delete')->name('delete');
        Route::get('print', 'VolunteerApiController@print')->name('print');
    });
});