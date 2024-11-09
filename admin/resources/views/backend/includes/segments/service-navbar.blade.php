<div class="main-card mb-2 card">
    <div class="btn-actions-pane-right">
        <div role="group" class="btn-group-sm btn-group">
            <a href="{{route('user.edit.service', $service_id)}}" class="btn-pill pl-3 btn btn-focus {{ ($segment == 'edit') ? 'active' : '' }}">Service Edit</a>
            <a href="{{route('user.order.service', $service_id)}}" class="btn btn-focus {{ ($segment == 'order') ? 'active' : '' }}">Order List</a>
            <a href="{{route('user.review.service', $service_id)}}" class="btn btn-focus {{ ($segment == 'review') ? 'active' : '' }}">Reviews</a>
            <a data-toggle="tab" href="#tab-eg3-2" class="btn-pill pr-3 btn btn-focus {{ ($segment == 'stats') ? 'active' : '' }}">Statistics</a>
        </div>
    </div>
</div>