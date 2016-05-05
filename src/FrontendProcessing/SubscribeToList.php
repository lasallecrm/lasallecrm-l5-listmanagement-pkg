<?php

namespace Lasallecrm\Listmanagement\FrontendProcessing;

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
use Lasallecms\Lasallecmsapi\Repositories\UserRepository;
use Lasallecrm\Lasallecrmapi\Repositories\EmailRepository;
use Lasallecrm\Lasallecrmapi\Repositories\PeopleRepository;
use Lasallecrm\Lasallecrmapi\Repositories\People_emailRepository;
use Lasallecrm\Listmanagement\Repositories\ListRepository;
use Lasallecrm\Listmanagement\Repositories\List_EmailRepository;

// Third party classes
use Carbon\Carbon;


/**
 * Class SubscribeToList
 * @package Lasallecrm\Listmanagement\FrontendProcessing
 */
class SubscribeToList
{
    /**
     * @var Lasallecrm\Listmanagement\Repositories\ListRepository
     */
    protected $listRepository;

    /**
     * @var Lasallecrm\Lasallecrmapi\Repositories\EmailRepository
     */
    protected $emailRepository;

    /**
     * @var Lasallecrm\Listmanagement\Repositories\List_EmailRepository
     */
    protected $list_EmailRepository;

    /**
     * @var Lasallecms\Lasallecmsapi\Repositories\UserRepository
     */
    protected $userRepository;

    /**
     * @var Lasallecrm\Lasallecrmapi\Repositories\PeopleRepository
     */
    protected $peopleRepository;

    /**
     * @var Lasallecrm\Lasallecrmapi\Repositories\People_emailRepository
     */
    protected $people_emailRepository;


    /**
     * SubscribeToList constructor.
     *
     * @param Lasallecrm\Listmanagement\Repositories\ListRepository        $listRepository
     * @param Lasallecrm\Lasallecrmapi\Repositories\EmailRepository        $emailRepository
     * @param Lasallecrm\Listmanagement\Repositories\List_EmailRepository  $list_EmailRepository
     * @param Lasallecms\Lasallecmsapi\Repositories\UserRepository         $userRepository
     * @param Lasallecrm\Lasallecrmapi\Repositories\PeopleRepository       $peopleRepository
     * @param Lasallecrm\Lasallecrmapi\Repositories\People_emailRepository $people_emailRepository
     */
    public function __construct(
        ListRepository              $listRepository,
        EmailRepository             $emailRepository,
        List_EmailRepository        $list_EmailRepository,
        UserRepository              $userRepository,
        PeopleRepository            $peopleRepository,
        People_emailRepository      $people_emailRepository
    ) {
        $this->listRepository       = $listRepository;
        $this->emailRepository      = $emailRepository;
        $this->list_EmailRepository = $list_EmailRepository;
        $this->userRepository       = $userRepository;
        $this->peopleRepository     = $peopleRepository;
        $this->people_emailRepository = $people_emailRepository;
    }

    /**
     * Get the list record by list ID
     *
     * @param   int             $listID    The "id" field of the "email_list" db table
     * @return  eloquent object
     */
    public function getListByID($listID) {
        return $this->listRepository->getListByID($listID);
    }

    /**
     * Get the name of the list for a given list ID
     *
     * @param   int             $listID    The "id" field of the "email_list" db table
     * @return  string
     */
    public function getListnameByListId($listID) {
        return $this->listRepository->getListnameByListId($listID);
    }

    /**
     * Get the email ID from the "emails" db table, by email address
     *
     * @param  string  $email      The email address
     * @return int
     */
    public function getEmailIDByTitle($email) {
        return $this->emailRepository->getEmailIDByTitle($email);
    }

    /**
     * Does the email already exist in the "list_email" db table?
     *
     * @param   int  $emailID       The "emails" db table's ID field
     * @return  bool
     */
    public function getList_emailByEmailID($emailID) {
        return $this->list_EmailRepository->getList_emailByEmailID($emailID );
    }

    /**
     * Does the email AND list already exist in the "list_email" db table?
     *
     * @param   int  $emailID       The "emails" db table's ID field
     * @param   int  $listID        The "lists" db table's ID field
     * @return  bool
     */
    public function getList_emailByEmailIdAndListId($emailID, $listID) {
        return $this->list_EmailRepository->getList_emailByEmailIdAndListId($emailID, $listID);
    }

    /**
     * Prepare the data for creating a new record in the "emails" db table
     *
     * @param  object  $request     The request object
     * @return array
     */
    public function prepareEmailDataForInsert($request) {

        $data = [];

        $data['email_type_id'] = 1;  // Primary
        $data['title']         = $request->input('email');
        $data['description']   = "";
        $data['comments']      = "Created by front-end subscription to an email list";
        $data['created_at']    = Carbon::now();
        $data['created_by']    = $this->userRepository->getFirstAmongEqualsUserID();
        $data['updated_at']    = Carbon::now();
        $data['updated_by']    = $this->userRepository->getFirstAmongEqualsUserID();

        return $data;
    }

    /**
     * Create a new record in the "emails" db table
     *
     * @param   array   $data      The data to be saved, which is already validated, washed, & prepped.
     * @return  mixed              The new emails.id when save is successful, false when save fails
     */
    public function createNewEmailRecord($data) {
        return $this->emailRepository->createNewRecord($data);
    }

    /**
     * Prepare the data for creating a new record in the "list_email" db table
     *
     * @param   array   $input      POST and processed vars merged into one array
     * @return  array
     */
    public function prepareList_emailDataForInsert($input) {

        $data = [];

        $data['title']         = $input['listID']." ".$input['emailID'];
        $data['list_id']       = $input['listID'];
        $data['email_id']      = $input['emailID'];
        $data['comments']      = "";
        $data['enabled']       = 1;
        $data['created_at']    = Carbon::now();
        $data['created_by']    = $this->userRepository->getFirstAmongEqualsUserID();
        $data['updated_at']    = Carbon::now();
        $data['updated_by']    = $this->userRepository->getFirstAmongEqualsUserID();

        return $data;

    }

    /**
     * Create a new record in the "list_email" db table
     *
     * @param   array   $data      The data to be saved, which is already validated, washed, & prepped.
     * @return  mixed              The new emails.id when save is successful, false when save fails
     */
    public function createNewList_emailRecord($data) {
        return $this->list_EmailRepository->createNewRecord($data);
    }

    /**
     * Does a record exist with the given "first_name" and "surname"?
     *
     * @param   string  $firstName                 The first name.
     * @param   string  $surname                   The surname.
     * @return  mixed
     */
    public function isFirstnameSurname($firstName, $surname) {
        return $this->peopleRepository->isFirstnameSurname($firstName, $surname);
    }

    /**
     * Prepare the data for creating a new record in the "peoplesl" db table
     *
     * @param   array   $input      POST and processed vars merged into one array
     * @return  array
     */
    public function preparePeoplesDataForInsert($input) {

        $data = [];

        $data['user_id']       = null;  // Set users up as PEOPLES in the admin
        $data['title']         = $input['first_name']." ".$input['surname'];
        $data['salutation']    = "";
        $data['first_name']    = $input['first_name'];
        $data['middle_name']   = "";
        $data['surname']       = $input['surname'];
        $data['position']      = "";
        $data['description']   = "";
        $data['comments']      = "Created by front-end subscription to an email list";

        $data['birthday']      = null;
        $data['anniversary']   = null;

        $data['created_at']    = Carbon::now();
        $data['created_by']    = $this->userRepository->getFirstAmongEqualsUserID();
        $data['updated_at']    = Carbon::now();
        $data['updated_by']    = $this->userRepository->getFirstAmongEqualsUserID();

        $data['profile']       = null;
        $data['featured_image'] = null;

        return $data;
    }

    /**
     * Create a new record in the "peoples" db table
     *
     * @param   array   $data      The data to be saved, which is already validated, washed, & prepped.
     * @return  mixed              The new emails.id when save is successful, false when save fails
     */
    public function createNewPeoplesRecord($data) {
        return $this->peopleRepository->createNewRecord($data);
    }

    /**
     * Create a new record in the "people_email" db pivot table
     *
     * @param   array   $data      The data to be saved, which is already validated, washed, & prepped.
     * @return  mixed              The new emails.id when save is successful, false when save fails
     */
    public function createNewPeople_emailRecord($data) {
        return $this->people_emailRepository->createNewRecord($data);
    }
}