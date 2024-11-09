@component('mail::message')
# Dear {{$author_name}},

We are pleased to inform you that you have received a new service inquery from a customer.

<p>Service Name : {{$service_name}}</p>
<p>Customer name : {{$user_name}}</p>
<p>Customer email : {{$user_email}}</p>
<p>Customer phone : {{$user_phone}}</p>
<p>Message : {{$user_message}}</p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent