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



return [

    /*
    |--------------------------------------------------------------------------
    | Do you want lists comprised of 'primary' emails only
    |--------------------------------------------------------------------------
    |
    | Do you want your email lists comprised of "primary" email types only?
    |
    | That is, lookup_email_types #1
    |
    | true or false
    |
    */
    'listmgmt_emails_in_list_primary_type_only' => true,

    /*
    |--------------------------------------------------------------------------
    | Unsubscribe token exists for the recent token only
    |--------------------------------------------------------------------------
    |
    | Do you want the most recent unsubscribe token in the "list_unsubscribe_token" database table?
    |
    | Or, do you want all unsubscribe tokens retained in the db table?
    |
    | Each email sent from a LaSalleCRM email list has (or is supposed to have) a footer with an unsubscribe-to-this-list
    | link. Each token is different. Each token is valid for the unsubscribe action as long as the corresponding
    | database record exists for it.
    |
    | If you prefer, you can choose to have just the most recently created token as the valid unsubscribe token.
    |
    | true  = the most recently created token is the only valid token for unsubscribing
    | false = all previouisly created tokens are valid for unsubscribing (by virtue of existing in the db table)
    |
    */
    'listmgmt_unsubscribe_token_recent_only' => false,

    /*
    |--------------------------------------------------------------------------
    | Subscribe to list form has first name and surname fields
    |--------------------------------------------------------------------------
    |
    | Do you want the most subscribe-to-list form to include first_name and surname fields?
    |
    | It is preferable to include these fields because it is better to create a "peoples" database table record, and
    | then associate the "peoples" id with the email address.
    |
    | However, many list sign-up forms ask for just the email only.
    |
    | true or false
    |
    */
    'listmgmt_subscribe_form_email_field_only' => false,

];

