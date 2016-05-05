<?php

namespace Lasallecrm\Listmanagement\Repositories;

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
use Lasallecrm\Lasallecrmapi\Repositories\BaseRepository;
use Lasallecrm\Listmanagement\Models\List_Email;

/**
 * Class List_EmailRepository
 * @package Lasallecrm\Listmanagement\Repositories
 */
class List_EmailRepository extends BaseRepository
{
    /**
     * Instance of model
     *
     * @var Lasallecrm\Listmanagement\Models\List_Email
     */
    protected $model;

    /**
     * Inject the model
     *
     * @param  Lasallecrm\Lasallecrmapi\Models\List_Email
     */
    public function __construct(List_Email $model) {
        $this->model = $model;
    }

    /**
     * UPDATE a list_email record so that "enabled" is false
     *
     * @param  int   $emailID
     * @param  int   $listID
     * @return void
     */
    public function enabledFalse($emailID, $listID) {

        $this->model->where('list_id', $listID)
            ->where('email_id', $emailID)
            ->update(['enabled' => false])
        ;
    }

    public function getList_emailByEmailID($emailID) {
        return $this->model
            ->where('email_id', $emailID)
            ->first()
        ;
    }

    /**
     * Does the email AND list already exist in the "list_email" db table?
     *
     * @param   int  $emailID       The "emails" db table's ID field
     * @param   int  $listID        The "lists" db table's ID field
     * @return  bool
     */
    public function getList_emailByEmailIdAndListId($emailID, $listID) {
        return $this->model
            ->where('email_id', $emailID)
            ->where('list_id', $listID)
            ->first()
        ;
    }

    /**
     * INSERT INTO 'list_email'
     *
     * @param   array   $data      The data to be saved, which is already validated, washed, & prepped.
     * @return  mixed              The new list_email.id when save is successful, false when save fails
     */
    public function createNewRecord($data) {
        $list_email = new $this->model;

        $list_email->title      = $data['list_id']." ".$data['email_id'];
        $list_email->list_id    = $data['list_id'];
        $list_email->email_id   = $data['email_id'];
        $list_email->comments   = $data['comments'];
        $list_email->enabled    = $data['enabled'];
        $list_email->created_at = $data['created_at'];
        $list_email->created_by = $data['created_by'];
        $list_email->updated_at = $data['updated_at'];
        $list_email->updated_by = $data['updated_by'];
        $list_email->locked_at  = null;
        $list_email->locked_by  = null;

        if ($list_email->save()) {

            // Return the new ID
            return $list_email->id;
        }

        return false;
    }
}