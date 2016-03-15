<?php

namespace Lasallecrm\Listmanagement\SendEmails;

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
use Lasallecrm\Listmanagement\Helpers\Helpers;

// Laravel facades
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendEmailsFromList
 * @package Lasallecrm\Listmanagement\SendEmails
 */
class SendEmailsFromList
{
    /**
     * @var Lasallecrm\Listmanagement\Helpers
     */
    protected $helpers;

    /**
     * SendEmailsFromList constructor.
     *
     * @param Helpers $helpers
     */
    public function __construct(Helpers $helpers) {
        $this->helpers = $helpers;
    }

    /**
     * Send emails from a list.
     *
     * Expected that this method called from another package, via an event job
     *
     * @param  int     $listID    Database table "lists" ID
     * @param  array   $post      Post's title, body, etc
     * @return bool
     */
    public function sendEmails($listID, $post) {

        // if the list is not enabled, then return false
        if (!$this->helpers->isListEnabled($listID)) {
            return false;
        }

        $emailData = [];

        // subject
        $emailData['subject'] = $this->helpers->getListNameFromListID($listID) . ' - ' . $post['title'];

        // build the individual emails
        $emailIDs = $this->helpers->getEnabledEmailsFromList($listID);

        if (count($emailIDs) == 0) {
            // for some reason, maybe mismatched list_id #'s, finding no emails
            return false;
        }

        // for the blade files
        $post['link']  = '<a href="';
        $post['link'] .= $post['canonical_url'];
        $post['link'] .= '">';
        $post['link'] .= $post['title'];
        $post['link'] .= '</a>';
        $post['body']  = $post['content'];

        // iterate through each email address, prepping and sending each email individually!
        foreach ($emailIDs as $emailID) {

            // if emails must be type "primary" AND the email is *not* type primary, do not process
            if (!$this->helpers->isEmailAddressPrimaryType($emailID->email_id)) {
                continue;
            }

            // get the actual email address from the "email_id" field in the "list_email" db table
            $emailData['to_email'] = $this->helpers->getEmailAddressFromEmailID($emailID->email_id);

            // get the person's first name and surname
            $emailData['to_name'] = $this->helpers->getFirstnameSurnameFromEmailID($emailID->email_id);

            $post['to_email'] = $emailData['to_email'] ;
            $post['to_name']  = $emailData['to_name'];

            // send the email one-at-a-time
            // note the queue-ing
            Mail::queue('lasallecrmlistmanagement::emails.send_email_from_list', ['post' => $post], function($message) use ($emailData)
            {
                $message->from(Config::get('lasallecmscontact.from_email'), Config::get('lasallecmscontact.from_name'));
                $message->to($emailData['to_email'], $emailData['to_name']);
                $message->subject($emailData['subject']);
            });
        }

        return true;
    }
}