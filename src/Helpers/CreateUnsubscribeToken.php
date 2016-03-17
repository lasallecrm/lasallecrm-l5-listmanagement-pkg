<?php

namespace Lasallecrm\Listmanagement\Helpers;

/**
 *
 * To Do package for the LaSalle Customer Relationship Management package.
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
 * @package    To Do package for the LaSalle Customer Relationship Management package
 * @link       http://LaSalleCRM.com
 * @copyright  (c) 2015 - 2016, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

// LaSalle Software
use Lasallecrm\Listmanagement\Models\List_Unsubscribe_Token as Model;

// Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


/**
 * Class CreateUnsubscribeToken
 * @package Lasallecrm\Listmanagement\Helpers
 */
Class CreateUnsubscribeToken {

    /**
     * @var Lasallecrm\Listmanagement\Models\List_Unsubscribe_Token
     */
    protected $model;

    /**
     * CreateUnsubscribeToken constructor.
     * @param Model $model
     */
    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * Create a record
     * @param  int  $listID
     * @param  int  $emailID
     * @param  text  $token
     * @return void
     */
    public function createTokenRecord($listID, $emailID, $token) {

        // Maintaining only the recent unsubscribe token for a list?
        if (Config::get('lasallecrmlistmanagement.listmgmt_unsubscribe_token_recent_only')) {
            $this->model->deleteAllTokensForListIDandEmailID($listID, $emailID);
        }

        // INSERT or UPDATE the db table
        $this->model->createTokenRecord($emailID, $listID, $token);
    }


    /**
     * Create a unique token
     *
     * @return mixed
     */
    public function createUniqueToken() {

        $token = $this->model->createToken();

        if ($this->model->isTokenUnique($token)) {
            return $token;
        }

        $this->createToken();
    }

    public function unsubscribeURL($token) {

        return Config::get('app.url') . "/list/unsubscribe/" . $token;
    }
}