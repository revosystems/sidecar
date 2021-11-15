<?php

Route::group(['prefix' => config('sidecar.routePrefix', 'sidecar'), 'namespace' => 'Revo\Sidecar\Controllers', 'middleware' => config('sidecar.routeMiddleware', ['web','auth'])], function () {
    Route::get('{report}'        , 'ReportsController@index')   ->name('sidecar.report.index');
    Route::get('{report}/widgets', 'ReportsController@widgets') ->name('sidecar.report.widgets');
    Route::get('{report}/graph'  , 'ReportsController@graph')   ->name('sidecar.report.graph');
});