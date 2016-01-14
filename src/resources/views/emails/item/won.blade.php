You have won the following item for {{ p($item_price, false) }}!:

{{ $item_name }}

Your maximum bid of {{ p($bid_amount, false) }}, placed on {{ dt($bid_placed) }}, was the highest.

Please sign in to choose delivery and pay:

{{ url('checkout/' . $item_id) }}
