<?php

Route::group(['prefix' => config('sidecar.routePrefix', 'sidecar'), 'namespace' => 'Revo\Sidecar\Controllers', 'middleware' => config('sidecar.routeMiddleware', ['web','auth'])], function () {
    Route::get('{resourceName}/widgets', 'ReportsController@widgets')->name('sidecar.report.widgets');
});