@extends(config('mustard.views.layout', 'mustard::layouts.master'))

@section('title')
    Bidding - Inventory
@stop

@section('content')
<div class="row">
    <div class="medium-3 large-2 columns">
        @include('mustard::inventory.nav')
    </div>
    <div class="medium-9 large-10 columns">
        @include('tablelegs::filter')
        @if (!$table->isEmpty())
            <table class="expand">
                @include('tablelegs::header')
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->itemId }}</td>
                            <td><a href="{{ $item->url }}">{{ $item->name }}</a></td>
                            <td>{{ mustard_number($item->bidCount) }}</td>
                            <td>{{ mustard_price($item->maxBid) }}</td>
                            <td style="white-space:nowrap;">
                                <strong>Bids:</strong> {{ mustard_number($item->bids->count()) }}<br />
                                <strong>Current price:</strong> {{ mustard_price($item->biddingPrice) }}
                            </td>
                            <td>{{ mustard_time($item->getTimeLeft(), 2, true) }}</td>
                            <td>
                                <button href="#" data-dropdown="item-{{ $item->itemId }}-options" aria-controls="item-{{ $item->itemId }}-options" aria-expanded="false" class="button tiny radius dropdown"><i class="fa fa-cog"></i> Options</button>
                                <ul id="item-{{ $item->itemId }}-options" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">
                                    <li><a href="/item/bid/{{ $item->itemId }}">Bid again</a></li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>You aren't currently bidding on any items. <a href="/buy">Bid on something now</a>!</p>
        @endif
        <div class="row">
            <div class="medium-12 columns text-center">
                {{ $table->paginator() }}
            </div>
        </div>
    </div>
</div>
@stop
