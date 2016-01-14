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
use Hamjoint\Mustard\Auctions\Bid;
use Hamjoint\Mustard\Auctions\BidIncrement;
use Hamjoint\Mustard\Http\Controllers\Controller;
use Hamjoint\Mustard\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Return the item bid view.
     *
     * @param int $itemId
     * @return \Illuminate\View\View
     */
    public function getBid($itemId)
    {
        $item = Item::findOrFail($itemId);

        if (!$item->auction) {
            return redirect($item->url)->withErrors([
                'This item is not an auction, so cannot be bid on.'
            ]);
        }

        $bids = $item->getBidHistory();

        $highest_bid = $bids->first() ?: new Bid;

        if ($bids->isEmpty()) {
            $minimum_bid = $item->startPrice;
        } elseif ($highest_bid->bidder == Auth::user()) {
            $minimum_bid = BidIncrement::getMinimumNextBid($highest_bid->amount);
        } else {
            $minimum_bid = BidIncrement::getMinimumNextBid($item->biddingPrice);
        }

        return view('mustard::item.bid', [
            'item' => $item,
            'bids' => $bids,
            'highest_bid' => $highest_bid,
            'minimum_bid' => $minimum_bid,
        ]);
    }

    /**
     * Place a bid.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBid(Request $request)
    {
        $item = Item::findOrFail($request->input('item_id'));

        if ($item->seller->userId == Auth::user()->userId) {
            return redirect()->back()->withErrors([
                'You cannot bid on your own items.'
            ]);
        }

        if (time() < $item->startDate) {
            return redirect()->back()->withErrors([
                'This item is not yet open for bidding.'
            ]);
        }

        if (time() > $item->endDate) {
            return redirect()->back()->withErrors([
                'This item has ended and cannot be bid on.'
            ]);
        }

        $minimum_bid = ($item->bids->count())
            ? BidIncrement::getMinimumNextBid($item->biddingPrice)
            : $item->startPrice;

        $highest_bid = $item->getBidHistory()->first();

        // If highest bidder, new bid must be higher than existing maximum
        if ($highest_bid && $highest_bid->bidder == Auth::user()) {
            if ($request->input('amount') < BidIncrement::getMinimumNextBid($highest_bid->amount)) {
                return redirect()->back()->withErrors([
                    'amount' => 'Your new maximum bid does not meet the minimum amount.'
                ]);
            }
        // Not highest bidder, so bid must be higher than minimum amount for current bidding price
        } elseif ($request->input('amount') < $minimum_bid) {
            return redirect()->back()->withErrors([
                'amount' => 'Your bid does not meet the minimum amount.'
            ]);
        }

        $this->validate(
            $request,
            [
                'amount' => 'required|monetary',
            ]
        );

        $item->placeBid($request->input('amount'), Auth::user());

        // If there's no highest bid, there's no bids, so bidding price can stay at start price
        if ($highest_bid) {
            // Have we beaten the highest bid?
            if ($request->input('amount') > $highest_bid->amount) {
                // If the reserve price is above the last highest bid but below the new maximum bid, set bidding to that
                if ($item->isReserved() && $request->input('amount') >= $item->reservePrice) {
                    $item->biddingPrice = $item->reservePrice;
                }

                // Check if we're increasing the maximum bid
                if ($highest_bid->bidder == Auth::user()) {
                    // Save in case the bidding price has been bumped to the reserve
                    $item->save();

                    $highest_bid->delete();

                    return redirect()->back()->withStatus('Your maximum bid has been increased.');
                }

                // Make sure we're not using the reserve price as the new bidding price
                if (!in_array('bidding_price', array_keys($item->getDirty()))) {
                    // Set bidding to the maximum bid or the next increment, whichever is lower
                    $item->biddingPrice = min([
                        $request->input('amount'),
                        // Calculate the next increment after the previous highest bid
                        $item::getMinimumBidAmount($highest_bid->amount),
                    ]);
                }

                $highest_bid->bidder->sendEmail(
                    "You've been outbid",
                    'emails.item.outbid',
                    [
                        'item_id' => $item->itemId,
                        'item_name' => $item->name,
                        'item_price' => $item->biddingPrice,
                    ]
                );
            // We're not the highest bid, so just increment
            } else {
                $item->biddingPrice = $request->input('amount');
            }
        } else {
            $item->biddingPrice = ($item->isReserved() && $request->input('amount') >= $item->reservePrice)
                ? $item->reservePrice
                : $item->startPrice;
        }

        $item->save();

        return redirect()->back()->withStatus('Your bid has been placed.');
    }

    /**
     * Watch an item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function endedAuctionsAdmin()
    {
        $items = Item::has('bids')
            ->where('auction', true)
            ->whereRaw('`reserve_price` <= `bidding_price`')
            ->where('user_ended', 0)
            ->where('winning_bid_id', 0)
            ->where('end_date', '<', time())
            ->get();

        foreach ($items as $item) {
            $item->end();

            $item->winningBid->bidder->sendEmail(
                'You won an item',
                'emails.item.won',
                [
                    'item_id' => $item->itemId,
                    'item_name' => $item->name,
                    'item_price' => $item->biddingPrice,
                    'bid_amount' => $item->winningBid->amount,
                    'bid_placed' => $item->winningBid->placed,
                ]
            );
        }
    }
}
