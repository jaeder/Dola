<?php

use DFZ\Dola\Models\DataType;

/*
|--------------------------------------------------------------------------
| Dola Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Dola.
|
*/

Route::group(['as' => 'dola.'], function () {
    event('dola.routing', app('router'));

    $namespacePrefix = '\\'.config('dola.controllers.namespace').'\\';

    Route::get('login', ['uses' => $namespacePrefix.'DolaAuthController@login',     'as' => 'login']);
    Route::post('login', ['uses' => $namespacePrefix.'DolaAuthController@postLogin', 'as' => 'postlogin']);

    Route::group(['middleware' => 'admin.user'], function () use ($namespacePrefix) {
        event('dola.admin.routing', app('router'));

        // Main Admin and Logout Route
        Route::get('/', ['uses' => $namespacePrefix.'DolaController@index',   'as' => 'dashboard']);
        Route::post('logout', ['uses' => $namespacePrefix.'DolaController@logout',  'as' => 'logout']);
        Route::post('upload', ['uses' => $namespacePrefix.'DolaController@upload',  'as' => 'upload']);

        Route::get('profile', ['uses' => $namespacePrefix.'DolaController@profile', 'as' => 'profile']);

        try {
            foreach (DataType::all() as $dataType) {
                $breadController = $dataType->controller
                                 ? $dataType->controller
                                 : $namespacePrefix.'DolaBreadController';

                Route::resource($dataType->slug, $breadController);
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // Role Routes
        Route::resource('roles', $namespacePrefix.'DolaRoleController');

        // Menu Routes
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix.'DolaMenuController@builder',    'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix.'DolaMenuController@order_item', 'as' => 'order']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix.'DolaMenuController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix.'DolaMenuController@add_item',    'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix.'DolaMenuController@update_item', 'as' => 'update']);
            });
        });

        // Settings
        Route::group([
            'as'     => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'DolaSettingsController@index',        'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'DolaSettingsController@store',        'as' => 'store']);
            Route::put('/', ['uses' => $namespacePrefix.'DolaSettingsController@update',       'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'DolaSettingsController@delete',       'as' => 'delete']);
            Route::get('{id}/move_up', ['uses' => $namespacePrefix.'DolaSettingsController@move_up',      'as' => 'move_up']);
            Route::get('{id}/move_down', ['uses' => $namespacePrefix.'DolaSettingsController@move_down',    'as' => 'move_down']);
            Route::get('{id}/delete_value', ['uses' => $namespacePrefix.'DolaSettingsController@delete_value', 'as' => 'delete_value']);
        });

        // Admin Media
        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'DolaMediaController@index',              'as' => 'index']);
            Route::post('files', ['uses' => $namespacePrefix.'DolaMediaController@files',              'as' => 'files']);
            Route::post('new_folder', ['uses' => $namespacePrefix.'DolaMediaController@new_folder',         'as' => 'new_folder']);
            Route::post('delete_file_folder', ['uses' => $namespacePrefix.'DolaMediaController@delete_file_folder', 'as' => 'delete_file_folder']);
            Route::post('directories', ['uses' => $namespacePrefix.'DolaMediaController@get_all_dirs',       'as' => 'get_all_dirs']);
            Route::post('move_file', ['uses' => $namespacePrefix.'DolaMediaController@move_file',          'as' => 'move_file']);
            Route::post('rename_file', ['uses' => $namespacePrefix.'DolaMediaController@rename_file',        'as' => 'rename_file']);
            Route::post('upload', ['uses' => $namespacePrefix.'DolaMediaController@upload',             'as' => 'upload']);
            Route::post('remove', ['uses' => $namespacePrefix.'DolaMediaController@remove',             'as' => 'remove']);
        });

        // Database Routes
        Route::group([
            'as'     => 'database.bread.',
            'prefix' => 'database',
        ], function () use ($namespacePrefix) {
            Route::get('{table}/bread/create', ['uses' => $namespacePrefix.'DolaDatabaseController@addBread',     'as' => 'create']);
            Route::post('bread', ['uses' => $namespacePrefix.'DolaDatabaseController@storeBread',   'as' => 'store']);
            Route::get('{table}/bread/edit', ['uses' => $namespacePrefix.'DolaDatabaseController@addEditBread', 'as' => 'edit']);
            Route::put('bread/{id}', ['uses' => $namespacePrefix.'DolaDatabaseController@updateBread',  'as' => 'update']);
            Route::delete('bread/{id}', ['uses' => $namespacePrefix.'DolaDatabaseController@deleteBread',  'as' => 'delete']);
        });

        Route::resource('database', $namespacePrefix.'DolaDatabaseController');
    });
});
