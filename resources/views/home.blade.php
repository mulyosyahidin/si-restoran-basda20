@role('waiter')
    @include('orders.waiter')
@endrole

@role('kitchen')
    @include('orders.kitchen')
@endrole

@role('admin')
    @include('admin.admin')
@endrole

@role('cashier')
    @include('orders.cashier')
@endrole