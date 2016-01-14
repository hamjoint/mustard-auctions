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

namespace Hamjoint\Mustard\Auctions\Tables;

use DB;
use Foundation\Pagination\FoundationFivePresenter;
use Tablelegs\Table;

class InventoryBidding extends Table
{
    /**
     * Column headers for the table. URL-friendly keys with human values.
     *
     * @var array
     */
    public $columnHeaders = [
        'Item ID'   => 'item_id',
        'Name'      => 'name',
        'Your bids' => 'bids',
        'Max bid'   => 'max_bid',
        'Details'   => null,
        'Time left' => 'end_date',
        'Options'   => null,
    ];

    /**
     * Array of filter names containing available options and their keys.
     *
     * @var array
     */
    public $filters = [
        'Status' => [
            'Winning',
            'Outbid',
        ],
    ];

    /**
     * Default key to sort by.
     *
     * @var string
     */
    public $defaultSortKey = 'end_date';

    /**
     * Default sort order.
     *
     * @var string
     */
    public $defaultSortOrder = 'desc';

    /**
     * Class name for the paginator presenter.
     *
     * @var string
     */
    public $presenter = FoundationFivePresenter::class;

    /**
     * Include winning items only.
     *
     * @return void
     */
    public function filterStatusWinning()
    {
        $this->db->winning();
    }

    /**
     * Include winning items only.
     *
     * @return void
     */
    public function filterStatusOutbid()
    {
        $this->db->outbid();
    }

    /**
     * Sort items by purchase date.
     *
     * @param string $sortOrder
     *
     * @return void
     */
    public function sortDate($sortOrder)
    {
        $this->db->orderBy('purchases.created', $sortOrder)
            ->orderBy('items.end_date', $sortOrder);
    }
}
