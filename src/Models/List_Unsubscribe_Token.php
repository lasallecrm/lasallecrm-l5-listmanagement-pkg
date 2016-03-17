<?php

namespace Lasallecrm\Listmanagement\Models;

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


/* ==================================================================================================================
    This is a weird database table, which means this is a weird model class!

    This database table does not have back-end form administration. You cannot CRUD this table in the back-end.

    This database table allows people to unsubscribe from LaSalleCRM email lists (Listlist). Someone can subscribe to
    multiple email lists, but one record represents a potential unsubscribe from one single list.

    The unsubscribe token is included (supposed to be included!) in the footer of each email sent out from a list. The
     person just clicks the unsubscribe link, and voila, they are unsubscribed. No logging into the site, no fuss, no
     muss.

    Usually, I create a repository for specific database methods. However, I'm putting all my database stuff in this
     model. Hey, it's a weird model class! Actually, it's a pretty specific table with a specific use with specific
     database operations. So, I'm kinda relieved that for once, I can (mostly) justify putting all the db stuff in
     the model class.

    The purpose of using a token for unsubscribe is to avoid using the list_id and email_id in the unsubscribe URL.
   ================================================================================================================== */


// LaSalle Software
use Lasallecms\Lasallecmsapi\Models\BaseModel;

// Laravel facades
use Illuminate\Support\Facades\DB;


/**
 * Class List_Unsubscribe_Token
 * @package Lasallecrm\Listmanagement\Models
 */
class List_Unsubscribe_Token extends BaseModel
{
    // LARAVEL MODEL CLASS PROPERTIES
    // See the properties at https://github.com/laravel/framework/blob/5.2/src/Illuminate/Database/Eloquent/Model.php
    // I'm trying to be extra sure that this database table cannot be CRUD'd through forms.

    /**
     * TThe table associated with the model.
     *
     * @var string
     */
    public $table = 'list_unsubscribe_token';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['*'];

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    // https://github.com/laravel/framework/blob/5.2/src/Illuminate/Database/Eloquent/Model::isFillable($key)
    protected static $unguarded = true;


    /**
     * Create a token using https://laravel.com/docs/5.1/helpers#method-str-random
     *
     * @return mixed
     */
    public function createToken() {
        return str_random(32);
    }

    /**
     * Is a given token unique in the list_unsubscribe_token database table?
     *
     * @param  text  $token
     * @return bool
     */
    public function isTokenUnique($token) {

        $record = List_Unsubscribe_Token::where('token', $token)->first();

        if (!$record) {
            return true;
        }

        return false;
    }

    /**
     * DELETE all records for a specific list and email
     *
     * @param  int   $emailID
     * @param  int   $listID
     * @return void
     */
    public function deleteAllTokensForListIDandEmailID($listID, $emailID) {
        List_Unsubscribe_Token::where('list_id', $listID)->where('email_id', $emailID)->delete();
    }

    /**
     * INSERT a token record.
     *
     * @param  int   $emailID
     * @param  int   $listID
     * @param  text  $token
     * @return void
     */
    public function createTokenRecord($emailID, $listID, $token) {

        $unsubscribe = new List_Unsubscribe_Token;

        $unsubscribe->email_id = $emailID;
        $unsubscribe->list_id  = $listID;
        $unsubscribe->token    = $token;

        $unsubscribe->save();
    }

    /**
     * Does a given token exist in the database?
     *
     * @param  text  $token
     * @return bool
     */
    public function isTokenValid($token) {
        $record = List_Unsubscribe_Token::where('token', $token)->first();

        if (!$record) {
            return false;
        }
        return true;
    }

    /**
     * Get email ID by token
     *
     * @param   text  $token
     * @return  mixed
     */
    public function getEmailIdByToken($token) {
        $unsubscribe = List_Unsubscribe_Token::where('token', $token)->first();

        return $unsubscribe->email_id;
    }

    /**
     * Get list ID by token
     *
     * @param  text  $token
     * @return mixed
     */
    public function getListIdByToken($token) {
        $unsubscribe = List_Unsubscribe_Token::where('token', $token)->first();

        return $unsubscribe->list_id;
    }
}