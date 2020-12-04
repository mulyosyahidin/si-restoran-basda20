@extends('layouts.master')
@section('title', getSiteName())

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Kelola Order</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item">Order</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row orders-list">
                @forelse ($orders as $item)
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
                                    <a href="#" class="btn btn-primary btn-sm btn-mark-ready" data-id="{{ $item->id }}">Siap <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-md table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->items as $food)
                                        <tr>
                                            <td>{{ $food->food->name }}</td>
                                            <td>{{ $food->item_qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <i class="fa fa-user text-info"></i> {{ $item->customer_name }}
                                        </td>
                                        <td>
                                            @if ($item->type == 1)
                                                @if ($item->table_id != null)
                                                    <i class="fa fa-chair text-info"></i> {{ $item->table->name }}
                                                @endif
                                            @else
                                                <i>Take away</i>
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if ($item->note)
                            <div class="card-footer">
                                {{ $item->note }}
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="col-12 default-row">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-info">
                                    Tidak ada order untuk diselesaikan
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
<div class="modal fade" tabindex="-1" role="dialog" id="markAsReadyModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Order Sudah Siap?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info alert-container">
                Yakin ingin menandai order sudah siap disajikan?
                <br>
                Perubahan akan disampaikan ke waiter.
            </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-do-ready"><i class="fa fa-check"></i> Siap</button>
          </div>
      </div>
    </div>
</div>
@endsection

@push('custom_js')
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
<script src="{{ asset('assets/plugins/toastify-js/src/toastify.js') }}"></script>

<script>
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        encrypted: true,
        cluster: 'ap1'
    });
      
    var channel = pusher.subscribe('restoran19');
    channel.bind('notifyKitchenNewOrder', function(data) {
        let defaultRow = document.querySelector('.default-row');
        let blankContainer = document.querySelector('.blank-order-container');

        if (defaultRow != null) {
            defaultRow.remove();
        }

        if (blankContainer != null) {
            blankContainer.remove();
        }

        let order = data.order;
        let table = data.table;

        var audio = new Audio('{{ asset('assets/uploads/beep2.mp3') }}');
        audio.play();

        Toastify({
            text: `Satu order baru dari ${order.customer_name}`,
            duration: 5000,
            gravity: 'top',
            position: 'left'
        }).showToast();

        let newItem = `
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 order-${order.id}">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title">
                                    <a href="{{ route('orders.show', false) }}/${order.id}" target="_blank">Order #${order.order_number}</a>
                                </h5>
                            </div>
                            <div class="col-7">
                                <span class="badge badge-danger">
                                    ${order.time}
                                </span>
                            </div>
                            <div class="col-5 text-right">
                                <a href="#" class="btn btn-primary btn-sm btn-mark-ready" data-id="${order.id}">Siap <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-md table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>`;
        data.items.forEach((item) => {
            newItem += `<tr>
                            <td>${item.name}</td>
                            <td>${item.qty}</td>
                        </tr>`;
        });

        newItem += `</tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <i class="fa fa-user text-info"></i> ${order.customer_name}
                            </td>
                            <td>`;
        if (order.type == 1) {
            if (order.table_id != null) {
                newItem += `<i class="fa fa-chair text-info"></i> ${table.name}`;
            }
        }
        else {
            newItem += '<i>Take away</i>';
        }
        
        newItem += `    </td>
                    </tr>
                </tfoot>
            </table>
        </div>`;
        
        if (order.note) {
            newItem += `<div class="card-footer">${order.note}</div>`;
        }

        newItem += `
            </div>
        </div>`;

        $('.row.orders-list').append(newItem)
    });

    let __ready_order_id = 0;

    $(document).on('click', '.btn-mark-ready', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        __ready_order_id = id;

        $('#markAsReadyModal').modal('show');
    });

    let readyBtn = document.querySelector('.btn-do-ready');
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
                        $('#markAsReadyModal').modal('hide');
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

    $('#markAsReadyModal').on('hidden.bs.modal', function (e) {
        readyBtn.removeAttribute('disabled');
        readyBtn.innerHTML = '<i class="fa fa-check"></i> Siap';
        alertContainer.innerHTML = 'Yakin ingin menandai order sudah siap disajikan?<br>Perubahan akan disampaikan ke waiter.';
    });
</script>
@endpush