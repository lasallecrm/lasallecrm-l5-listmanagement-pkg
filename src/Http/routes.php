<?php

/**
 *
 * List Management package for the LaSalle Customer Relationship Management package.
 *
 * Based on the Laravel 5 Framework.
 *
 * Copyright (C) 2015 - 2016  The South LaSalle Trading Corporation
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package    List Management package for the LaSalle Customer Relationship Management package
 * @link       http://LaSalleCRM.com
 * @copyright  (c) 2015 - 2016, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

Route::group(array('prefix' => 'admin'), function()
{
    // Regular tables
    Route::resource('listmgmtlists', 'AdminListMgmtListsController');
    Route::post('listmgmtlists/confirmDeletion/{id}', 'AdminListMgmtListsController@confirmDeletion');
    Route::post('listmgmtlists/confirmDeletionMultipleRows', 'AdminListMgmtListsController@confirmDeletionMultipleRows');
    Route::post('listmgmtlists/destroyMultipleRecords', 'AdminListMgmtListsController@destroyMultipleRecords');

    Route::resource('listmgmtlistemails', 'AdminListEmailsMgmtListsController');
    Route::post('listmgmtlistemails/confirmDeletion/{id}', 'AdminListEmailsMgmtListsController@confirmDeletion');
    Route::post('listmgmtlistemails/confirmDeletionMultipleRows', 'AdminListEmailsMgmtListsController@confirmDeletionMultipleRows');
    Route::post('listmgmtlistemails/destroyMultipleRecords', 'AdminListEmailsMgmtListsController@destroyMultipleRecords');
});

// Front-end routes
Route::get('list/unsubscribe/{token}', 'FrontendListUnsubscribeController@unsubscribe');
Route::get('list/subscribe/{listid}', 'FrontendListSubscribeController@subscribeform');
Route::post('list/subscribe/', 'FrontendListSubscribeController@postSubscribe');
