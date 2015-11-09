@extends(config('mustard.views.layout', 'mustard::layouts.master'))

@section('title')
    Bidding: {{ $item->name }}
@stop

@section('content')
    <div class="item-buy">
        <form method="post" action="/item/bid" data-abide="true">
            {!! csrf_field() !!}
            <input type="hidden" name="item_id" value="{{ $item->itemId }}" />
            <div class="row">
                <div class="medium-12 columns">
                    <h1>Bidding: {{ $item->name }}</h1>
                </div>
            </div>
            <div class="row">
                @if ($item->isActive())
                    <div class="medium-6 columns">
                        <div class="row">
                            <div class="medium-12 columns">
                                @if ($highest_bid->bidder == Auth::user())
                                    @if ($item->isReserved())
                                        <div class="alert-box warning radius">
                                            You are currently the highest bidder, but the reserve price has not yet been reached.
                                        </div>
                                    @else
                                        <div class="alert-box success radius">
                                            You are currently the highest bidder.
                                        </div>
                                    @endif
                                @elseif ($item->isBidder(Auth::user()))
                                    <div class="alert-box alert radius">
                                        You have been outbid.
                                    </div>
                                @endif
                                <ul class="pricing-table">
                                    @if ($bids->count())
                                        <li class="price">Current bid: {{ mustard_price($item->biddingPrice) }}</li>
                                        @if ($highest_bid->bidder == Auth::user())
                                            <li class="bullet-item"><strong>Your maximum bid:</strong> {{ mustard_price($highest_bid->amount) }}</li>
                                        @endif
                                        @if ($item->hasReserve() && !$item->isReserved())
                                            <li class="bullet-item"><strong>Reserve price:</strong> {{ mustard_price($item->reservePrice) }}</li>
                                        @endif
                                    @else
                                        <li class="price">Starting price: {{ mustard_price($item->startPrice) }}</li>
                                    @endif
                                    <li class="bullet-item"><strong>Time left:</strong> {{ mustard_time($item->getTimeLeft()) }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="medium-12 columns">
                                @if ($highest_bid->bidder == Auth::user())
                                    <h2>Increase your maximum bid</h2>
                                @elseif ($item->isBidder(Auth::user()))
                                    <h2>Bid again</h2>
                                @else
                                    <h2>Your maximum bid</h2>
                                @endif
                                <label>Enter an amount of {{ mustard_price($minimum_bid) }} or more
                                    <div class="row collapse prefix-radius">
                                        <div class="small-1 columns">
                                            <span class="prefix">&pound;</span>
                                        </div>
                                        <div class="small-11 columns">
                                            <input type="text" name="amount" value="{{ mustard_number($minimum_bid, 2, 'C') }}" required pattern="monetary" />
                                            <small class="error">Please enter a bid amount.</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="medium-12 columns">
                                <button class="button expand radius">Bid</button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="medium-6 columns">
                    <h2>Bid history</h2>
                    @if ($bids->count())
                        <table class="expand">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Placed</th>
                                    <th>Bidder</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bids as $key => $bid)
                                <tr>
                                    @if ($key === 0)
                                        <td>{{ mustard_price($item->biddingPrice) }}</td>
                                    @else
                                        <td>{{ mustard_price($bid->amount) }}</td>
                                    @endif
                                        <td>{{ mustard_datetime($bid->placed) }}</td>
                                    @if ($bid->bidder == Auth::user() || $item->seller == Auth::user())
                                        <td><a href="{{ $bid->bidder->url }}">{{ $bid->bidder->username }}</a></td>
                                    @else
                                        <td>Redacted</td>
                                    @endif
                                </tr>
                                @endforeach
                                <tr>
                                    <td>{{ mustard_price($item->startPrice) }}</td>
                                    <td>{{ mustard_datetime($item->startDate) }}</td>
                                    <td>Starting price</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p>No bids placed yet.</p>
                    @endif
                </div>
            </div>
        </form>
        <div class="row">
            <div class="medium-12 columns">
                <a href="{{ $item->url }}"><i class="fa fa-arrow-circle-left"></i> Return to item</a>
            </div>
        </div>
    </div>
@stop
