<?php

namespace Lasallecrm\Listmanagement\Http\Controllers;

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

// LaSalle Software
use Lasallecrm\Listmanagement\FrontendProcessing\SubscribeToList;



// Laravel classes
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

// Laravel facades
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


/**
 * Class FrontendListUnsubscribeController
 * @package Lasallecrm\Listmanagement\Http\Controllers
 */
class FrontendListSubscribeController extends BaseController {

    /**
     * @var Lasallecrm\Listmanagement\FrontendProcessing\SubscribeToList
     */
    protected $subscribeToList;


    /**
     * FrontendListUnsubscribeController constructor.
     *
     * @param Lasallecrm\Listmanagement\FrontendProcessing\SubscribeToList  $subscribeToList
     */
    public function __construct(SubscribeToList $subscribeToList) {
        $this->subscribeToList = $subscribeToList;
    }


    /**
     * Display the subscribe form for a given list ID
     *
     * @param   int       $listID    The "id" field of the "email_list" db table
     * @return  \Illuminate\Http\Response
     */
    public function subscribeform($listID) {

        if (!$this->subscribeToList->getListByID($listID)) {
            return view('lasallecrmlistmanagement::subscribe-unsubscribe-list.list_invalid', [
                'title'        => 'Invalid List',
            ]);
        }

        return view('lasallecrmlistmanagement::subscribe-unsubscribe-list.subscribe_form', [
            'title'            => 'List Sign-up',
            'email_field_only' => Config::get('lasallecrmlistmanagement.listmgmt_subscribe_form_email_field_only'),
            'list'             => $this->subscribeToList->getListnameByListId($listID),
            'listID'           => $listID,
        ]);
    }

    /**
     * Subscribe someone to a LaSalleCRM email list from the filled in subscribe form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postSubscribe(Request $request) {

        // Does the email ID exist in the "list_emails" table for this list ID already?
        // We need the email ID, but only have an email address. See if this email address exists in the "emails" db table
        if ($this->subscribeToList->getEmailIDByTitle($request->input('email'))) {

            // yes, the email address exists in the "emails" db table
            if ($this->subscribeToList->getList_emailByEmailIdAndListId(
                   $this->subscribeToList->getEmailIDByTitle($request->input('email')),
                   $request->input('listID')
                )
            ) {
                return view('lasallecrmlistmanagement::subscribe-unsubscribe-list.subscribe_email_already_in_list', [
                    'title'             => $this->subscribeToList->getListnameByListId($request->input('listID')),
                    'email'             => $request->input('email')
                ]);
            }

        }


        // Add email to the email list
        $emailID = $this->subscribeToList->getEmailIDByTitle($request->input('email'));

        if (!$emailID) {

            // The email address does NOT exist in the "emails" db table.
            // So, create the email address in the "emails" db table
            // (i) prep the data
            $data = $this->subscribeToList->prepareEmailDataForInsert($request);

            // (ii) create the new record
            $emailID = $this->subscribeToList->createNewEmailRecord($data);
        }

        // if the INSERT to the "emails" db table failed, then try the subscribe all over again
        if (!$emailID) {
            $message = "Something did not quite go right in the processing. Please try again.";
            Session::flash('message', $message);
            return redirect('list/subscribe/'.$request->input('listID'))
                ->withInput()
            ;
        }


        // INSERT INTO list_email
        // consolidate POST vars and processed data into one array
        $input = array_merge($request->all(),['emailID' => $emailID]);

        // prep the data for the INSERT
        $data = $this->subscribeToList->prepareList_emailDataForInsert($input);

        // INSERT record
        $list_emailID = $this->subscribeToList->createNewList_emailRecord($data);

        // if the INSERT into the "list_email" db table failed, then try the subscribe all over again
        if (!$list_emailID) {
            $message = "Something did not go quite right in the processing. Please try again.";
            Session::flash('message', $message);
            return redirect('list/subscribe/'.$request->input('listID'))
                ->withInput()
                ;
        }


        if ($request->has('surname')) {


            $peopleID = $this->subscribeToList->isFirstnameSurname($request->input('first_name'), $request->input('surname'));

            if (!$peopleID) {
                // prep the data for the INSERT
                $data = $this->subscribeToList->preparePeoplesDataForInsert($input);

                // INSERT record
                $peopleID = $this->subscribeToList->createNewPeoplesRecord($data);
            }


            if ($peopleID) {
                // INSERT INTO people_email
                $people_emailID = $this->subscribeToList->createNewPeople_emailRecord(['people_id' => $peopleID, 'email_id' => $emailID]);
            }


            if (!$people_emailID) {
                // whaddaya mean the INSERT INTO "people_email" failed?! try again...
                $people_emailID = $this->subscribeToList->createNewPeople_emailRecord(['people_id' => $peopleID, 'email_id' => $emailID]);
            }
        }

        return view('lasallecrmlistmanagement::subscribe-unsubscribe-list.subscribe_success', [
            'title'             => $this->subscribeToList->getListnameByListId($request->input('listID')),
            'email'             => $request->input('email')
        ]);




        // ===> NOW, PROCESS THE TOKEN UNSUBSCRIBE!!!!



        // **************************************************************************************************************

/*

use Lasallecrm\Listmanagement\Models\List_Unsubscribe_Token as Model;

use Lasallecrm\Lasallecrmapi\Repositories\EmailRepository;

use Lasallecrm\Listmanagement\Repositories\List_EmailRepository;

 */

        // Does the token exist in the list_unsubscribe_token database table?
        if (!$this->model->isTokenValid($token)) {
            return view('lasallecrmlistmanagement::unsubscribe.token_invalid', [
                'title'    => 'Invalid Unsubscribe Token',
            ]);
        }

        $emailID  = $this->model->getEmailIdByToken($token);
        $listID   = $this->model->getListIdByToken($token);

        // email address
        $email    = $this->emailRepository->getEmailByEmailId($emailID);

        // list's name
        $listName = $this->listRepository->getListnameByListId($listID);

        // Delete all list_unsubscribe_token records for this email_id and list_id
        $this->model->deleteAllTokensForListIDandEmailID($listID,$emailID);

        // un-enabled (ie, disable) the list_email database record for this email_id and list_id
        $this->list_EmailRepository->enabledFalse($emailID, $listID);

        return view('lasallecrmlistmanagement::unsubscribe.success', [
            'title'    => 'Unsubscribe Successful',
            'email'    => $email,
            'listName' => $listName
        ]);
    }
}