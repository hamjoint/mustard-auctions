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

use Hamjoint\Mustard\Auctions\BidIncrement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MustardAuctionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->integer('bid_id', true)->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->decimal('amount', 8, 2)->unsigned();
            $table->integer('placed')->unsigned();

            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->unique(['item_id', 'user_id', 'amount']);
        });

        Schema::create('bid_increments', function (Blueprint $table) {
            $table->mediumInteger('bid_increment_id', true)->unsigned();
            $table->decimal('minimum', 8, 2)->unsigned();
            $table->decimal('increment', 8, 2)->unsigned();
        });

        $increments = [
            0    => 0.01,
            1    => 0.1,
            10   => 1,
            50   => 5,
            100  => 10,
            500  => 50,
            1000 => 100,
        ];

        foreach ($increments as $minimum => $increment) {
            $bid_increment = new BidIncrement();

            $bid_increment->minimum = $minimum;
            $bid_increment->increment = $increment;

            $bid_increment->save();
        }
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
