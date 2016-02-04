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

// Laravel classes
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListmanagementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ///////////////////////////////////////////////////////////////////////
        ////                      Main Tables                              ////
        ///////////////////////////////////////////////////////////////////////

        if (!Schema::hasTable('lists')) {
            Schema::create('lists', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->string('title')->unique();
                $table->string('description');
                $table->text('comments');
                $table->boolean('enabled')->default(true);

                $table->timestamp('created_at');
                $table->integer('created_by')->unsigned();
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamp('updated_at');
                $table->integer('updated_by')->unsigned();
                $table->foreign('updated_by')->references('id')->on('users');

                $table->timestamp('locked_at')->nullable();
                $table->integer('locked_by')->nullable()->unsigned();
                $table->foreign('locked_by')->references('id')->on('users');
            });
        }


        // Normally, this would be a pivot table. It would not have its own forms. Rather,
        // it would be populated by another table's form. However, in this case, associating
        // a list to an email is an independent operation of its own, not associated with anything
        // else.
        if (!Schema::hasTable('list_email')) {
            Schema::create('list_email', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                // "Composite Title" that is comprised of the "first_name" and "surname"
                // concatenated automatically during persist operations.
                $table->string('title');

                $table->integer('list_id')->unsigned();
                $table->foreign('list_id')->references('id')->on('lists');

                $table->integer('email_id')->unsigned();
                $table->foreign('email_id')->references('id')->on('emails');

                $table->text('comments');

                $table->boolean('enabled')->default(true);

                $table->timestamp('created_at');
                $table->integer('created_by')->unsigned();
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamp('updated_at');
                $table->integer('updated_by')->unsigned();
                $table->foreign('updated_by')->references('id')->on('users');

                $table->timestamp('locked_at')->nullable();
                $table->integer('locked_by')->nullable()->unsigned();
                $table->foreign('locked_by')->references('id')->on('users');
            });
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Disable foreign key constraints or these DROPs will not work
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        ///////////////////////////////////////////////////////////////////////
        ////                    Main Tables                                ////
        ///////////////////////////////////////////////////////////////////////

        Schema::table('lists', function($table){
            $table->dropIndex('lists_title_unique');
            $table->dropForeign('lists_created_by_foreign');
            $table->dropForeign('lists_updated_by_foreign');
            $table->dropForeign('lists_locked_by_foreign');
        });
        Schema::dropIfExists('lists');

        Schema::table('list_email', function($table){
            $table->dropForeign('list_email_list_id_foreign');
            $table->dropForeign('list_email_email_id_foreign');
            $table->dropForeign('list_email_created_by_foreign');
            $table->dropForeign('list_email_updated_by_foreign');
            $table->dropForeign('list_email_locked_by_foreign');
        });
        Schema::dropIfExists('list_email');

        // Enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}