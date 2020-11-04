@extends('layouts.master')
@section('title', $title)

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $title }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item">{{ $title }}</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $title }}</h5>
                        </div>
                        @if (count($orders) > 0)
                        <div class="table-responsive">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="row">ID</th>
                                        <th scope="row">Tanggal</th>
                                        <th scope="row">Jenis / Meja</th>
                                        <th scope="row">Pelanggan</th>
                                        <th scope="row">Jumlah Item</th>
                                        @role('admin')
                                            <th scope="row">Total Harga</th>
                                        @endrole
                                        <th scope="row">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $item)
                                    <tr>
                                        <th scope="col"><a href="{{ route('orders.show', $item->id) }}">#{{ $item->order_number }}</a></th>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('l, d M Y H:i') }}</td>
                                        <td>
                                            @if ($item->type == 1)
                                                Makan ditempat @if ($item->table_id != null) / {{ $item->table->name }} @endif
                                            @else
                                                Bawa pulang
                                            @endif
                                        </td>
                                        <td>{{ $item->customer_name }}</td>
                                        <td>{{ $item->total_item }}</td>
                                        @role('admin')
                                            <td>Rp {{ number_format($item->total_price, 2, ',', '.') }}</td>
                                        @endrole
                                        <td>
                                            @if ($item->status == 1)
                                                <span class="badge badge-danger">Antrian dapur</span>
                                            @elseif ($item->status == 2)
                                                <span class="badge badge-info">Siap</span>
                                            @else
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $orders->links() }}
                        </div>
                        @else
                            <div class="card-body">
                                <div class="alert alert-info">Tidak ada data untuk ditampilkan</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
          </div>
        </section>
    </div>
@endsection