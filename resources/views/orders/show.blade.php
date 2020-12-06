@extends('layouts.master')
@section('title', 'Order #'. $order->order_number)

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Order #{{ $order->order_number }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('orders.index') }}">Order</a></div>
              <div class="breadcrumb-item">#{{ $order->order_number }}</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                       <div class="table-responsive">
                           <table class="table table-striped table-hover table-bordered">
                                <tr>
                                    <td>No. Order</td>
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('dddd, DD MMMM YYYY HH:mm') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama Pelanggan</td>
                                    <td>
                                        <strong>{{ $order->customer_name }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Item</td>
                                    <td>
                                        <strong>{{ $order->total_item }}</strong>
                                    </td>
                                </tr>
                                @role('admin')
                                <tr>
                                    <td>Total Harga</td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total_price, 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kasir</td>
                                    <td>{{ $order->waiter->name }}</td>
                                </tr>
                                @endrole
                                <tr>
                                    <td>Meja</td>
                                    <td>
                                        @if ($order->type == 1)
                                            @if (isset($order->table->name))
                                                {{ $order->table->name }}
                                            @endif
                                        @else
                                            <span class="badge badge-info">Bawa pulang</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        @if ($order->status == 1)
                                            <span class="badge badge-danger">Antrian dapur</span>
                                        @elseif ($order->status == 2)
                                            <span class="badge badge-info">Siap</span>
                                        @else
                                            <span class="badge badge-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Catatan</td>
                                    <td><strong>{{ $order->note }}</strong></td>
                                </tr>
                           </table>
                       </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Item Order</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->food->name }}</td>
                                            <td>{{ $item->item_qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </section>
    </div>
@endsection