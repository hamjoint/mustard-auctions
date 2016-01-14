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

namespace Hamjoint\Mustard\Auctions\Http\Controllers;

use Auth;
use DB;
use Hamjoint\Mustard\Auctions\Tables\InventoryBidding;
use Hamjoint\Mustard\Http\Controllers\Controller;
use Hamjoint\Mustard\Item;

class InventoryController extends Controller
{
    /**
     * Return the inventory bidding items view.
     *
     * @return \Illuminate\View\View
     */
    public function getBidding()
    {
        $items = Item::typeAuction()->active()->whereHas('bids', function ($query) {
            return $query->where('user_id', Auth::user()->userId);
        });

        $items = Item::typeAuction()->active()->leftJoin('bids')->where(
            'bids.user_id',
            Auth::user()->userId
        )->addSelect(
            DB::raw('max(`bids`.`amount`) as `max_bid`')
        )->addSelect(
            DB::raw('count(`bids`.`bid_id`) as `bid_count`')
        )->groupBy('items.item_id');

        $table = new InventoryBidding($items);

        return view('mustard::inventory.bidding', [
            'table' => $table,
            'items' => $table->paginate(),
        ]);
    }
}
