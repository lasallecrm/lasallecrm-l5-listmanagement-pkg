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
// LaSalle Softweare
use Lasallecms\Usermanagement\Helpers\NotifySuperAdministrators\EmailSuperAdministrators;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * Class SuperAdminNotificationSentToList
 * @package Lasallecrm\Listmanagement\SendEmails
 */
class SuperAdminNotificationSentToList {

    /**
     * @var Lasallecms\Usermanagement\Helpers\NotifySuperAdministrators\EmailSuperAdministrators
     */
    protected $emailSuperAdministrators;

    /**
     * SuperAdminNotificationSentToList constructor.
     * @param EmailSuperAdministrators $emailSuperAdministrators
     */
    public function __construct(EmailSuperAdministrators $emailSuperAdministrators) {
        $this->emailSuperAdministrators = $emailSuperAdministrators;
    }

    /**
     * Send the super administrators an email letting them know that a post was sent to a LaSalleCRM list.
     * Called by a LaSalleCMSAPI listener.
     *
     * @param  array   $event     Post's title, body, etc
     * @return void
     */
    public function emailSuperAdministratorNotifications($event) {

        if (!config('lasallecmsapi.auth_frontend_registration_successful_send_admins_email')) {
           // return;
        }

        $event->data['id']['listTitle'] = $this->findListById($event->data['id']['listID']);


        $subject = config('lasallecmsfrontend.site_name') . ': Blog Post Sent to Email List';

        $emailBladeFile = 'lasallecrmlistmanagement::emails.notify_super_admin_post_sent_to_list';

        $this->emailSuperAdministrators->sendEmailToSuperAdmins($subject, $event, $emailBladeFile);
    }



    /**
     * Find the list's title by list ID
     *
     * @param  int   $listID
     * @return text
     */
    public function findListById($listID) {
        return DB::table('lists')
            ->where('id', $listID)
            ->value('title')
        ;
    }
}