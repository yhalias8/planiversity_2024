@component('mail::message')
# Dear {{$author_name}},

We are pleased to inform you that you have received a new order from a customer.

<p>Order Number : {{$order_number}}</p>
<p>Service Name : {{$service_name}}</p>
<p>Customer name : {{$user_name}}</p>
<p>Customer email : {{$user_email}}</p>
<p>Customer phone : {{$user_phone}}</p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent