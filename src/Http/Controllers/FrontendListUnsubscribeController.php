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
use Lasallecrm\Listmanagement\Models\List_Unsubscribe_Token as Model;
use Lasallecrm\Lasallecrmapi\Repositories\EmailRepository;
use Lasallecrm\Listmanagement\Repositories\ListRepository;
use Lasallecrm\Listmanagement\Repositories\List_EmailRepository;


// Laravel classes
use Illuminate\Routing\Controller as BaseController;


/**
 * Class FrontendListUnsubscribeController
 * @package Lasallecrm\Listmanagement\Http\Controllers
 */
class FrontendListUnsubscribeController extends BaseController {

    /**
     * @var Lasallecrm\Listmanagement\Models\List_Unsubscribe_Token
     */
    protected $model;

    /**
     * @var Lasallecrm\Lasallecrmapi\Repositories\EmailRepository
     */
    protected $emailepository;

    /**
     * @var Lasallecrm\Listmanagement\Repositories\ListRepository
     */
    protected $listRepository;

    /**
     * @var Lasallecrm\Listmanagement\Repositories\List_EmailRepository
     */
    protected $list_EmailRepository;


    /**
     * FrontendListUnsubscribeController constructor.
     * @param Model $model
     * @param Lasallecrm\Lasallecrmapi\Repositories\EmailRepository       $emailRepository
     * @param Lasallecrm\Listmanagement\Repositories\ListRepository       $listRepository
     * @param Lasallecrm\Listmanagement\Repositories\List_EmailRepository $listRepository
     */
    public function __construct(
        Model $model,
        EmailRepository $emailRepository,
        ListRepository $listRepository,
        List_EmailRepository $list_EmailRepository
    ) {
        $this->model                 = $model;
        $this->emailRepository       = $emailRepository;
        $this->listRepository        = $listRepository;
        $this->list_EmailRepository  = $list_EmailRepository;
    }

    /**
     * Unsubscribe from a LaSalleCRM email list
     *
     * @param $token
     */
    public function unsubscribe($token) {

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