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

// Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


// YES, I SHOULD GET THESE METHODS INTO THE REPOSITORIES.,,


/**
 * Class Helpers
 * @package Lasallecrm\Listmanagement\Helpers
 */
class Helpers
{
    /**
     * Is the list enabled?
     *
     * @param  int         $listID    Database table "lists" ID
     * @return
     */
    public function isListEnabled($listID) {
        return DB::table('lists')
            ->select('enabled')
            ->where('id',  $listID)
            ->first()
            ->enabled
        ;
    }

    /**
     * Get the name of the list from the List's ID
     *
     * @param  int         $listID    Database table "lists" ID
     * @return mixed
     */
    public function getListNameFromListID($listID) {
        return DB::table('lists')
            ->select('title')
            ->where('id',  $listID)
            ->first()
            ->title
        ;
    }


    /**
     * Get all the enabled email addresses from a list
     *
     * @param  int         $listID    Database table "lists" ID
     * @return collection
     */
    public function getEnabledEmailsFromList($listID) {
        return DB::table('list_email')
            ->where('list_id',  $listID)
            ->where('enabled', true)
            ->get()
        ;
    }

    /**
     * Is the email type "primary" AND must we accept "primary" type only?
     *
     * Both conditions must be true to return true
     *
     * @param   int    $emailID       Emails db table's ID
     * @return  bool
     */
    public function isEmailAddressPrimaryType($emailID) {

        // Must we accept "primary" email type only?
        if (!Config::get('lasallecrmlistmanagement.listmgmt_emails_in_list_primary_type_only')) {
            return false;
        }

        // "primary" type is #1
        if ($this->getEmailType($emailID) != 1) {
            return false;
        }

        return true;
    }

    /**
     * Get the email type
     *
     * @param   int    $emailID       Emails db table's ID
     * @return  bool
     */
    public function getEmailType($emailID) {
        return DB::table('emails')
            ->select('email_type_id')
            ->where('id',  $emailID)
            ->first()
            ->email_type_id
        ;
    }

    /**
     * Get the email address from the "emails" db table's ID
     *
     * @param   int    $emailID       Emails db table's ID
     * @return  string
     */
    public function getEmailAddressFromEmailID($emailID) {
        return DB::table('emails')
            ->select('title')
            ->where('id',  $emailID)
            ->first()
            ->title
        ;
    }


    public function getFirstnameSurnameFromEmailID($emailID) {
        $people_id = DB::table('people_email')
            ->select('people_id')
            ->where('email_id',  $emailID)
            ->first()
        ;

        $people = DB::table('peoples')
            ->where('id',  $people_id->people_id)
            ->first()
        ;

        return $people->title;

    }
}