@extends('layouts.master')
@section('title', getSiteName())

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Kasir Pembayaran</h1>
            <div class="section-header-breadcrumb">
              <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#payModalForm"><i class="fa fa-plus"></i></a>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row orders-list">
                @forelse ($readyOrders as $item)
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 order-{{ $item->id }}">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="card-title">
                                        <a href="{{ route('orders.show', $item->id) }}" target="_blank">Order #{{ $item->order_number }}</a>
                                    </h5>
                                </div>
                                <div class="col-7">
                                    <span class="badge badge-danger">
                                        {{  \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="col-5 text-right">
                                    <a href="#" class="btn btn-primary btn-sm btn-pay" data-order-number="{{ $item->order_number }}" data-id="{{ $item->id }}">Bayar <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-md table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Pelanggan</td>
                                        <td><strong>{{ $item->customer_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Meja</td>
                                        <td><strong>
                                            @if (isset($item->table->name))
                                                {{ $item->table->name }}
                                            @else
                                                Bawa Pulang
                                            @endif
                                        </strong></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Item</td>
                                        <td><strong>{{ $item->total_item }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Total Harga</td>
                                        <td><strong>Rp {{ number_format($item->total_price, 2, ',', '.') }}</strong></td>
                                    </tr>
                                </tbody>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 default-row">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-info">
                                    Tidak ada order yang siap untuk pembayaran
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
          </div>
        </section>
      </div>
@endsection

@section('custom_html')
<div class="modal fade" tabindex="-1" role="dialog" id="payModalForm">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Bayar Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="order-number">No. Order:</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">#</span></div>
                    <input type="text" class="form-control" id="order-number" name="order_number" required="required">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-secondary search-btn" data-toggle="tooltip" title="Cari Order"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="search-container"></div>
            <div class="table-container"></div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-do-payment" disabled>Bayar</button>
          </div>
      </div>
    </div>
</div>
@endsection

@push('custom_js')
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
<script src="{{ asset('assets/plugins/toastify-js/src/toastify.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/accounting.js/accounting.min.js') }}"></script>

<script>
    let passportAccessToken = localStorage.getItem('accessToken');

    function formatMoney(money) {
        return accounting.formatMoney(money, '', 2, '.', ',');
    }
    
    $(document).on('click', '.btn-pay', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let orderNumber = $(this).data('order-number');

        Swal.fire({
            title: 'Lakukan Pembayaran?',
            text: `Yakin akan melakukan pembayaran untuk order #${orderNumber}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Bayar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    html: '<i class="fa fa-spin fa-spinner"></i> Sedang memproses...',
                    showConfirmButton: false
                });

                fetch(`{{ route('api.orders.update', false) }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${passportAccessToken}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        section: 'do_payment'
                    })
                })
                    .then(res => res.json())
                    .then(res => {
                        Swal.fire(
                            'Berhasil!',
                            `Pembayaran order #${orderNumber} berhasil`,
                            'success'
                        );

                        let successInfo = `<div class="card-body">
                            <div class="text-center">
                                <i class="fa fa-check fa-2x"></i>
                                <br>
                                Order Berhasil Dibayar!
                            </div>
                        </div>`;

                        $(`.orders-list .order-${id} .card`).addClass('bg-success').empty().append(successInfo);

                        setTimeout(() => {
                            $(`.orders-list .order-${id}`).fadeOut('slow');
                        }, 3000);
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            }
        })
    });

    let readyBtn = document.querySelector('.btn-do-payment');
    let alertContainer = document.querySelector('.alert-container');

    readyBtn.addEventListener('click', (e) => {
        e.preventDefault();

        let orderId = __ready_order_id;

        readyBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Memproses...';
        readyBtn.setAttribute('disabled', 'disabled');
        
        fetch(`{{ route('api.orders.update', false) }}/${orderId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${passportAccessToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                section: 'mark_as_ready'
            })
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    readyBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';
                    alertContainer.innerHTML = 'Order ditandai sebagai disajikan';

                    $(`.orders-list .order-${orderId}`).remove();

                    setTimeout(() => {
                        $('#payModal').modal('hide');
                    }, 2500);

                    if (res.orderCount == 0) {
                        let blankOrder = `<div class="col-12 blank-order-container">
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-info">Tidak ada yang sedang menunggu antrian.</div>
                                </div>
                            </div>
                        </div>`;
                        document.querySelector('.orders-list')
                            .innerHTML = blankOrder;
                    }
                }
            })
            .catch(errors => {
                console.log(errors);
            });
    });

    $('#payModal').on('hidden.bs.modal', function (e) {
        readyBtn.removeAttribute('disabled');
        readyBtn.innerHTML = '<i class="fa fa-check"></i> Siap';
        alertContainer.innerHTML = 'Yakin ingin menandai order sudah siap disajikan?<br>Perubahan akan disampaikan ke waiter.';
    });

    let searchBtn = document.querySelector('.search-btn');
    let searchContainer = document.querySelector('.search-container');
    let tableContainer = document.querySelector('.table-container');

    searchBtn.addEventListener('click', function (e) {
        e.preventDefault();

        let orderNumber = document.querySelector('#order-number');

        if (orderNumber.value == '') {
            searchContainer.innerHTML = 'Masukkan nomor order yang akan dibayar';
            return false;
        }
        while (tableContainer.firstChild) {
            tableContainer.removeChild(tableContainer.firstChild)
        }

        searchContainer.innerHTML = `<i class="fa fa-spin fa-spinner"></i> Mencari order <b>#${orderNumber.value} ...`;

        fetch(`{{ route('api.orders.find') }}?order_number=${orderNumber.value}`, {
            headers: {
                'Authorization': `Bearer ${passportAccessToken}`
            }
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    let tableInfo = `<div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td>Pelanggan</td>
                                <td><b>${res.order.customer_name}</b></td>
                            </tr>
                            <tr>
                                <td>Total Item</td>
                                <td><b>${res.order.total_item}</b></td>
                            </tr>
                            <tr>
                                <td>Total Bayar</td>
                                <td><b>Rp ${formatMoney(res.order.total_price)}</b></td>
                            </tr>
                        </table>
                    </div>`;

                    searchContainer.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menampilkan data...'

                    setTimeout(() => {
                        while (searchContainer.firstChild) {
                            searchContainer.removeChild(searchContainer.firstChild)
                        }
                    }, 1000);

                    setTimeout(() => {
                        tableContainer.innerHTML = tableInfo;
                    }, 1200);
                }
                else {
                    searchContainer.innerHTML = `Tidak ada hasil untuk order <b>#${orderNumber.value}</b>`;
                    orderNumber.value = '';
                    orderNumber.focus();
                }
            })
            .catch(errors => {
                console.log(errors);
            })
    })
</script>
@endpush