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

use Hamjoint\Mustard\Auctions\BidIncrement;
use Hamjoint\Mustard\Auctions\Tables\AdminBidIncrements;
use Hamjoint\Mustard\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Return the admin bid increments view.
     *
     * @return \Illuminate\View\View
     */
    public function getBidIncrements()
    {
        $table = new AdminBidIncrements(BidIncrement::query());

        return view('mustard::admin.bid-increments', [
            'table'          => $table,
            'bid_increments' => $table->paginate(),
        ]);
    }
}
