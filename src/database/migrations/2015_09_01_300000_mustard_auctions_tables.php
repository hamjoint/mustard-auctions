<?php

/*

This file is part of Mustard.

Mustard is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Mustard is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Mustard.  If not, see <http://www.gnu.org/licenses/>.

*/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MustardAuctionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function(Blueprint $table)
        {
            $table->integer('bid_id', true)->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->decimal('amount', 8, 2)->unsigned();
            $table->integer('placed')->unsigned();

            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->unique(['item_id', 'user_id', 'amount']);
        });

        Schema::create('bid_increments', function(Blueprint $table)
        {
            $table->mediumInteger('bid_increment_id', true)->unsigned();
            $table->decimal('increment', 8, 2)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bid_increments');
        Schema::drop('bids');
    }
}
