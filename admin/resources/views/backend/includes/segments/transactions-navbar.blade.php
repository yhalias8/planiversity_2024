<div class="main-card mb-2 card">
    <div class="btn-actions-pane-right">
        <div role="group" class="btn-group-sm btn-group">
            <a href="{{route('user.billing.transactions')}}" class="btn-pill pl-3 btn btn-focus {{ ($segment == 'billing') ? 'active' : '' }}">Billing</a>
            <a href="{{route('user.service.transactions')}}" class="btn btn-focus {{ ($segment == 'service') ? 'active' : '' }}">Service</a>
        </div>
    </div>
</div>