@extends(config('mustard.views.layout', 'mustard::layouts.master'))

@section('title')
    Bid Increments - Admin
@stop

@section('content')
    <div class="admin-listing-conditions">
        <div class="row">
            <div class="medium-3 large-2 columns">
                @include('mustard::admin.fragments.nav')
            </div>
            <div class="medium-9 large-10 columns">
                @include('tablelegs::filter')
                @if (!$table->isEmpty())
                    <table class="expand">
                        @include('tablelegs::header')
                        <tbody>
                            @foreach ($bid_increments as $bid_increment)
                                <tr>
                                    <td>{{ $bid_increment->bidIncrementId }}</td>
                                    <td>{{ $bid_increment->increment }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No bid increments.</p>
                @endif
                <div class="row">
                    <div class="medium-12 columns pagination-centered">
                        {!! $table->paginator() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
