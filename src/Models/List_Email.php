<?php
namespace Lasallecrm\Listmanagement\Models;

    /**
     *
     * List Management package for the LaSalle Customer Relationship Management package.
     *
     * Based on the Laravel 5 Framework.
     *
     * Copyright (C) 2015  The South LaSalle Trading Corporation
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
     * @copyright  (c) 2015, The South LaSalle Trading Corporation
     * @license    http://www.gnu.org/licenses/gpl-3.0.html
     * @author     The South LaSalle Trading Corporation
     * @email      info@southlasalle.com
     *
     */

// LaSalle Software
use Lasallecms\Lasallecmsapi\Models\BaseModel;

class List_Email extends BaseModel
{
    ///////////////////////////////////////////////////////////////////
    ///////////     MANDATORY USER DEFINED PROPERTIES      ////////////
    ///////////              MODIFY THESE!                /////////////
    ///////////////////////////////////////////////////////////////////


    // LARAVEL MODEL CLASS PROPERTIES

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'list_email';


    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'title', 'list_id', 'email_id', 'description', 'enabled',
    ];


    // PACKAGE PROPERTIES

    /*
     * Name of this package
     *
     * @var string
     */
    public $package_title = "LaSalleCRM - List Management";


    // MODEL PROPERTIES

    /*
     * Model class namespace.
     *
     * Do *NOT* specify the model's class.
     *
     * @var string
     */
    public $model_namespace = "Lasallecrm\Listmanagement\Models";

    /*
     * Model's class.
     *
     * Convention is capitalized and singular -- which is assumed.
     *
     * @var string
     */
    public $model_class = "List_Email";


    // RESOURCE ROUTE PROPERTIES

    /*
     * The base URL of the resource routes.
     *
     * Frequently is the same as the table name.
     *
     * By convention, plural.
     *
     * Lowercase.
     *
     * @var string
     */
    public $resource_route_name   = "listmgmtlistemails";


    // FORM PROCESSORS PROPERTIES.
    // THESE ARE THE ADMIN CRUD COMMAND HANDLERS.
    // THE ONLY REASON YOU HAVE TO CREATE THESE COMMAND HANDLERS AT ALL IS THAT
    // THE EVENTS DIFFER. EVERYTHING THAT HAPPENS UP TO THE "PERSIST" IS PRETTY STANDARD.

    /*
     * Namespace of the Form Processors
     *
     * MUST *NOT* have a slash at the end of the string
     *
     * @var string
     */
    public $namespace_formprocessor = 'Lasallecrm\Listmanagement\Jobs\List_Emails';

    /*
     * Class name of the CREATE Form Processor command
     *
     * @var string
     */
    public $classname_formprocessor_create = 'CreateList_EmailFormProcessing';

    /*
     * Namespace and class name of the UPDATE Form Processor command
     *
     * @var string
     */
    public $classname_formprocessor_update = 'UpdateList_EmailFormProcessing';

    /*
     * Namespace and class name of the DELETE (DESTROY) Form Processor command
     *
     * @var string
     */
    public $classname_formprocessor_delete = 'DeleteList_EmailFormProcessing';


    // SANITATION RULES PROPERTIES

    /**
     * Sanitation rules for Create (INSERT)
     *
     * @var array
     */
    public $sanitationRulesForCreate = [
        'comments'    => 'trim',
    ];

    /**
     * Sanitation rules for UPDATE
     *
     * @var array
     */
    public $sanitationRulesForUpdate = [
        'comments'    => 'trim',
    ];


    // VALIDATION RULES PROPERTIES

    /**
     * Validation rules for  Create (INSERT)
     *
     * @var array
     */
    public $validationRulesForCreate = [
        'list_id'     => 'integer',
        'email_id'    => 'integer',
    ];

    /**
     * Validation rules for UPDATE
     *
     * @var array
     */
    public $validationRulesForUpdate = [
        'list_id'     => 'integer',
        'email_id'    => 'integer',
    ];


    // USER GROUPS & ROLES PROPERTIES

    /*
     * User groups that are allowed to execute each controller action
     *
     * @var array
     */
    public $allowed_user_groups = [
        ['index'   => ['Super Administrator']],
        ['create'  => ['Super Administrator']],
        ['store'   => ['Super Administrator']],
        ['edit'    => ['Super Administrator']],
        ['update'  => ['Super Administrator']],
        ['destroy' => ['Super Administrator']],
    ];


    // FIELD LIST PROPERTIES

    /*
     * Field list
     *
     * ID and TITLE must go first.
     *
     * Forms will list fields in the order fields are listed in this array.
     *
     * @var array
     */
    public $field_list = [
        [
            'name'                  => 'id',
            'type'                  => 'int',
            'info'                  => false,
            'index_skip'            => false,
            'index_align'           => 'center',
        ],
        [
            /*  "Composite Title" field. Database tables that are related to each other need a "Title" field.
                 There is no natural field that can serve as the "Title" field. The "Composite Title" field
                 concatenates other fields during create and updates automatically.

                 * not for lookup tables
                 * include  'index_skip' => true,  so existing code will exclude from index listing
                 * MySQL field type "text" so not run out of space concatenating multiple varchar(255) fields
             */
            'name'                  => 'composite_title',
            'type'                  => 'composite_title',
            'fields_to_concatenate' => ['list_id', 'email_id'],
            'index_skip'            => true,
        ],
        [
            'name'                  => 'list_id',
            'alternate_form_name'   => 'List',
            'type'                  => 'related_table',
            'related_table_name'    => 'lists',
            'related_namespace'     => 'Lasallecrm\Listmanagement\Models',
            'related_model_class'   => 'Listlist',
            'related_fk_constraint' => false,
            'related_pivot_table'   => false,
            'nullable'              => false,
            'info'                  => false,
            'index_skip'            => false,
            'index_align'           => 'center',
        ],
        [
            'name'                  => 'email_id',
            'alternate_form_name'   => 'Email Address',
            'type'                  => 'related_table',
            'related_table_name'    => 'emails',
            'related_namespace'     => 'Lasallecrm\Lasallecrmapi\Models',
            'related_model_class'   => 'Email',
            'related_fk_constraint' => false,
            'related_pivot_table'   => false,
            'nullable'              => false,
            'info'                  => 'false',
            'index_skip'            => false,
            'index_align'           => 'center',
        ],
        [
            'name'                  => 'comments',
            'type'                  => 'text-with-editor',
            'info'                  => 'Optional.',
            'index_skip'            => true,
            'persist_wash'          => 'content',
        ],
        [
            'name'                  => 'enabled',
            'type'                  => 'boolean',
            'info'                  => false,
            'index_skip'            => true,
            'index_align'           => 'center',
            'persist_wash'          => 'enabled',
        ],
    ];


    // MISC PROPERTIES

    /*
     * Suppress the delete button when just one record to list, in the listings (index) page
     *
     * true  = suppress the delete button when just one record to list
     * false = display the delete button when just one record to list
     *
     * @var bool
     */
    public $suppress_delete_button_when_one_record = false;

    /*
     * DO NOT DELETE THESE CORE RECORDS.
     *
     * Specify the TITLE of these records
     *
     * Assumed that this database table has a "title" field
     *
     * @var array
     */
    public $do_not_delete_these_core_records = [];


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with List
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function listlist()
    {
        return $this->hasOne('Lasallecrm\Listmanagement\Models\Listlist');
    }

    /*
     * One to one relationship with List_Email
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function list_email()
    {
        return $this->hasOne('Lasallecrm\Listmanagement\Models\List_Email');
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        OTHER METHODS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    // none
}