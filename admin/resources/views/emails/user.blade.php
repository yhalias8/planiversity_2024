@component('mail::message')
# Dear {{$username}},

Thank you for your recent order! We are excited to fulfill your purchase and wanted to confirm the details of your order.

<p>Date : {{$current_timestamp}}</p>
<p>Order Number : {{$order_number}}</p>
<p>Service Name : {{$service_name}}</p>
<p>Seller Name : {{$author_name}}</p>

If you have any questions or concerns regarding your order, please don't hesitate to contact us at plans@planiversity.com.

Thank you for choosing us for your purchase.

Thanks,<br>
{{ config('app.name') }}
@endcomponent