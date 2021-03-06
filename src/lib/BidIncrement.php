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

namespace Hamjoint\Mustard\Auctions;

class BidIncrement extends \Hamjoint\Mustard\Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bid_increments';

    /**
     * The database key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'bid_increment_id';

    /**
     * Return the minimum next bid for an amount.
     *
     * @param float $amount
     *
     * @return float
     */
    public static function getMinimumNextBid($amount)
    {
        return $amount + self::where(
            'minimum',
            '<=',
            $amount
        )->orderBy(
            'increment',
            'desc'
        )->take(1)->value('increment');
    }
}
