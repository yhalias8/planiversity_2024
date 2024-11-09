@component('mail::message')
# Dear Agent,

We are pleased to inform you that you have received a new pricing inquery from a customer.

<p>Business Custom Pricing</p>
<p>Customer name : {{$user_name}}</p>
<p>Customer email : {{$user_email}}</p>
<p>Customer phone : {{$user_phone}}</p>
<p>Customer country : {{$user_country}}</p>
<p>Customer state : {{$user_state}}</p>
<p>Message : {{$user_message}}</p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent