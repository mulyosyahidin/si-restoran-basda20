@extends('layouts.master')
@section('title', 'Selamat Datang')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}">

    <style>
        .foods .item {
            background:  #F7F9FA;
            border: 1px solid #333;
            margin-bottom: 3px
        }
        .foods .item:hover {
            cursor: grab
        }
        .foods .item div {
            text-align: center;
            margin-bottom: 7px;
        }
        .foods .item.selected {
            background: #6777EF
        }
        .foods .item.selected div {
            color: white;
        }
      
       footer {
           position: fixed;
           width: 100%;
           left: 0;
           bottom: 0;
           z-index: 2;
           border: none !important;
           margin-bottom: -50px !important;
       }
       body {
           padding-bottom: 80px
       }
       footer .card {
           border-top: 1px solid #333
       }
    </style>
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Item</h5>
                        </div>
                        <div class="card-body">
                            <div class="row foods">
                                @foreach ($foods as $item)
                                    <div class="col-2">
                                        <div class="item item-{{ $item->id }}" data-id="{{ $item->id }}">
                                            <img src="{{ $item->media[0]->getFullUrl() }}" alt="" class="img-fluid img-w30">
                                            <div>{{ $item->name }}</div>
                                            @if ($item->stock < 10)
                                                <div class="text-center" style="position: absolute; top: 0; margin-top: 4px; margin-left: 4px">
                                                    @if ($item->stock < 10 && $item->stock > 0)
                                                        <span class="badge badge-info">Stok: {{ $item->stock }}</span>
                                                    @endif
                                                    @if ($item->stock == 0)
                                                        <span class="badge badge-danger">Habis</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="customer-name">Nama pelanggan:</label>
                                        <input type="text" id="customer-name" class="form-control customer_name-input">

                                        <div class="invalid-feedback customer_name-feedback">Nama pelanggan tidak boleh kosong</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="order-type">Order:</label>
                                        <select id="order-type" class="form-control">
                                            <option value="1">Makan ditempat</option>
                                            <option value="2">Bawa pulang</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="table-select">Meja:</label>
                                <select id="table-select" class="form-control">
                                    @foreach ($tables as $item)
                                        <option @if (in_array($item->id, $used_tables)) disabled @endif value="{{ $item->id }}" class="select-table table-{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="order-note">Catatan tambahan:</label>
                                <textarea name="note" id="order-note" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-striped table-bordered" id="items-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot id="order-summary">
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td colspan="2" class="text-right"><span class="total-item font-weight-bold">0</span></td>
                                        <td><strong>Rp <span class="total-price">0,00</span></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

           
        </section>
    </div>
@endsection

@section('footer')
<div class="card">
    <div class="card-footer text-right">
        <button type="button" class="btn btn-secondary float-left reset-btn">Reset</button>
        <button class="btn btn-primary" id="place-new-order">Buat Order <i class="fa fa-arrow-right"></i></button>
        <button class="btn btn-info" id="place-new-order2">Buat Order dan Bayar</button>
    </div>
</div>
@endsection

@section('custom_html')
<div class="modal fade" tabindex="-1" role="dialog" id="cartModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Item Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="add-cart-form">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>Harga satuan:</td>
                        <td>Rp <span class="price"></span></td>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td><span class="stock"></span></td>
                    </tr>
                    <tr>
                        <td>Jumlah order</td>
                        <td><input type="number" id="qty" class="form-control qty-input" value="1" name="qty"></td>
                    </tr>
                    <tr>
                        <td>Subtotal</td>
                        <td>Rp <span class="subtotal">0</span></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary mr-auto btn-close-cart-modal" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="orderModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><span class="order-title"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="text-center mb-3">
                <i class="fa fa-check fa-3x text-success"></i>
                <br>
                Order Berhasil!
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered" id="order-data">
                    <tr>
                        <td>ID</td>
                        <td><span class="order-number font-weight-bold"></span></td>
                    </tr>
                    <tr>
                        <td>Meja</td>
                        <td><span class="order-table font-weight-bold"></span></td>
                    </tr>
                    <tr>
                        <td>Total Harga</td>
                        <td><span class="total-price font-weight-bold"></span></td>
                    </tr>
                </table>
            </div>

            <div>
                Order berhasil dicatat dan telah disampaikan ke bagian dapur.
            </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary mr-auto btn-close-cart-modal" data-dismiss="modal">Tutup</button>
            <a href="#" class="btn btn-primary btn-print" target="_blank">Cetak Nota</a>
          </div>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="orderModal2">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><span class="order-title"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="pay-form">
            <div class="modal-body">
                <div class="message-container"></div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered" id="order-data2">
                        <tr>
                            <td>Total Harga</td>
                            <td><span class="total-price font-weight-bold"></span></td>
                        </tr>
                    </table>
                </div>
    
                <div class="form-group">
                    <label for="">Jumlah bayar:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control amount-input" name="amount" data-total-payment="" required>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="">Kembali:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control back-input" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary mr-auto btn-close-cart-modal" data-dismiss="modal">Tutup</button>
                
                <a href="#" class="btn btn-primary btn-print" target="_blank">Cetak Nota</a>
                <button type="submit" class="btn btn-primary pay-btn">Bayar dan Selesai</button>
              </div>
          </div>
        </form>
    </div>
</div>
@endsection

@push('custom_js')
    <script src="{{ asset('assets/plugins/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/plugins/accounting.js/accounting.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    <script>
        function formatMoney(money) {
            return accounting.formatMoney(money, '', 2, '.', ',');
        }
        function loadOrderOverview(data) {
            let itemQty = 0;
            let itemSubtotal = 0;

            for (let i = 0; i < data.length; i++) {
                let id = data[i][0];
                let qty = data[i][1];
                let price = data[i][2];

                itemQty += +qty;
                itemSubtotal += +price * qty;
            }

            $('#order-summary .total-item').text(itemQty);
            $('#order-summary .total-price').text(formatMoney(itemSubtotal));
        }

        function resetOrder() {
            __temp_order_data = [];
            $('#place-new-order').html('<i class="fa fa-arrow-right"></i> Order');

            $('#customer-name').val('');
            $('#table-select').prop('selectedIndex', 0);
            $('#order-type').prop('selectedIndex', 0);
            $('#order-note').val('');
            $('#items-table tbody').empty();

            $('#order-summary .total-item').text('0');
            $('#order-summary .total-price').text('Rp 0,00');

            $('.foods .item').removeClass('selected');

            $('.pay-btn').html('Bayar dan Selesai');
            $('#pay-form .message-container').removeClass('alert alert-success').empty();
            $('#pay-form .back-input, #pay-form .amount-input').val('');
            $('#place-new-order2').html('Bayar dan Selesai');
        }

        let bearerToken = localStorage.getItem('accessToken');
        document.querySelector('body')
            .classList.add('sidebar-mini');

        $(document).ready(function () {
            $('#table-select').select2();
        });

        let addCartForm = $('#add-cart-form');
        let addCartBtn = $('[type=submit]', addCartForm);

        let __temp_order_data = [];

        let __temp_item_id = 0;
        let __temp_item_price = 0;
        let __temp_order_max = 0;

        var audio = new Audio('{{ asset('assets/uploads/beep-08b.mp3') }}');

        $('.foods .item').click(function () {
            let isSelected = $(this).hasClass('selected');
            let id = $(this).data('id');

            __temp_item_id = id;

            if (!isSelected) {
                fetch(`{{ route('api.foods.show', false) }}/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer '+ bearerToken
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        $('.stock', addCartForm).html(res.stock);
                        $('.price', addCartForm).html(formatMoney(res.price));
                        $('.qty-input', addCartForm).val(1);
                        $('.subtotal', addCartForm).html(formatMoney(res.price));

                        __temp_item_price = res.price;
                        __temp_order_max = res.stock;

                        $('#qty', addCartForm).attr('max', res.stock);
                        $('#cartModal').modal('show');
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            }
            else {
                $(`#items-table #item-${id}`).remove();
                $(`.foods .item-${id}`).removeClass('selected');

                let removePosition = 0;
                __temp_order_data.forEach((item, index) => {
                    if (id == item[0]) {
                        removePosition = index;
                        return false;
                    }
                });

                __temp_order_data.splice(removePosition, 1);

                loadOrderOverview(__temp_order_data);

                Toastify({
                    text: 'Item dihapus dari keranjang',
                    duration: 3000,
                    gravity: 'top',
                    position: 'left'
                }).showToast();
            }
        });

        $('.qty-input').keyup(function (e) {
            e.preventDefault();

            let qty = $(this).val();
            let subtotal = qty * __temp_item_price;

            if (qty > __temp_order_max) {
                Toastify({
                    text: 'Order tidak bisa melebihi stok',
                    duration: 3000,
                    gravity: 'top',
                    position: 'center'
                }).showToast();

                addCartBtn.attr('disabled', 'disabled');
            }
            else if (qty <= 0) {
                Toastify({
                    text: 'Jumlah order minimal 1',
                    duration: 3000,
                    gravity: 'top',
                    position: 'center'
                }).showToast();

                addCartBtn.attr('disabled', 'disabled');
            }
            else {
                addCartBtn.removeAttr('disabled');
            }

            $('.subtotal', addCartForm).html(formatMoney(subtotal));
        })

        addCartForm.submit(function (e) {
            e.preventDefault();
            
            let id = __temp_item_id;
            let qty = +$('.qty-input', $(this)).val();
            
            fetch(`{{ route('api.foods.show', false) }}/${id}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    let subtotal = qty * res.price;
                    __temp_order_data.push([id, qty, subtotal]);

                    $(`.foods .item-${id}`).addClass('selected');
                    
                    audio.play();

                    let newItem = $(`<tr id="item-${id}" />`);
                    newItem.append(`<td>${res.name}</td>`);
                    newItem.append(`<td>Rp ${formatMoney(res.price)}</td>`);
                    newItem.append(`<td>
                        <a class="btn btn-secondary btn-sm mr-2 decrease-qty" data-id="${id}" data-stock="${res.stock}" data-price="${res.price}">-</a>
                        <span class="order-item-qty qty-${id}">${qty}</span>
                        <a data-id="${id}" data-price="${res.price}" data-stock="${res.stock}" class="increase-qty ml-2 btn btn-secondary btn-sm">+</a></td>
                    `);
                    newItem.append(`<td>Rp <span class="subtotal-${id}">${formatMoney(subtotal)}</span></td>`);
                    newItem.append(`<td><a href="#" class="btn btn-sm btn-danger remove-cart" data-id="${id}"><i class="fa fa-times"></i></a></td>`);

                    $('#items-table > tbody').append(newItem);

                    $('#cartModal').modal('hide');

                    loadOrderOverview(__temp_order_data);
                })
                .catch(errors => {
                    console.log(errors);
                })
        });

        $(document).on('click', '.remove-cart', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            $(`#items-table #item-${id}`).remove();
            $(`.foods .item-${id}`).removeClass('selected');

            let removePosition = 0;
            __temp_order_data.forEach((item, index) => {
                if (id == item[0]) {
                    removePosition = index;
                    return false;
                }
            });

            __temp_order_data.splice(removePosition, 1);

            loadOrderOverview(__temp_order_data);

            Toastify({
                text: 'Item dihapus dari keranjang',
                duration: 3000,
                gravity: 'bottom',
                position: 'right'
            }).showToast();
        });

        $(document).on('click', '.increase-qty', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            let qtyContainer = $(`.order-item-qty.qty-${id}`);
            let stock = $(this).data('stock');
            let price = $(this).data('price');

            let currentQty = qtyContainer.text();
            let addQty = +currentQty + 1;
            let increased = addQty;

            if (addQty > stock) {
                Toastify({
                    text: `Jumlah order tidak bisa melebihi stok (Stok: ${stock})`,
                    duration: 3000,
                    gravity: 'bottom',
                    position: 'right'
                }).showToast();

                qtyContainer.text(stock);
                increased = stock;
            }
            else {
                qtyContainer.text(addQty);
            }

            let newSubTotal = price * increased;
            $(`.subtotal-${id}`).text(formatMoney(newSubTotal))

            let updateIndex = 0;
            __temp_order_data.forEach((item, index) => {
                if (id == item[0]) {
                    updateIndex = index;
                    return false;
                }
            });

            __temp_order_data[updateIndex][1] = increased;
            loadOrderOverview(__temp_order_data);
        });
        
        $(document).on('click', '.decrease-qty', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            let qtyContainer = $(`.order-item-qty.qty-${id}`);
            let price = $(this).data('price');

            let currentQty = qtyContainer.text();
            let addQty = +currentQty - 1;
            let decreased = addQty;

            if (addQty < 1) {
                Toastify({
                    text: 'Jumlah order minimal 1',
                    duration: 3000,
                    gravity: 'bottom',
                    position: 'right'
                }).showToast();

                qtyContainer.text(1);
                decreased = 1;
            }
            else {
                qtyContainer.text(addQty);
            }

            let newSubTotal = price * decreased;
            $(`.subtotal-${id}`).text(formatMoney(newSubTotal))

            let updateIndex = 0;
            __temp_order_data.forEach((item, index) => {
                if (id == item[0]) {
                    updateIndex = index;
                    return false;
                }
            });

            __temp_order_data[updateIndex][1] = decreased;
            loadOrderOverview(__temp_order_data);
        });

        $('#order-type').change(function (e) {
            e.preventDefault();

            let type = $(this).val();
            if (type == 2) {
                $('#table-select').attr('disabled', 'disabled');
            }
            else {
                $('#table-select').removeAttr('disabled');
            }
        });

        $('#place-new-order').click(function (e) {
            e.preventDefault();

            let customerName = $('#customer-name');
            let table = $('#table-select');
            let type = $('#order-type');
            let note = $('#order-note');

            if (customerName.val() == '') {
                customerName.addClass('is-invalid');
                return false;
            }
            customerName.removeClass('is-invalid');

            if (__temp_order_data.length == 0) {
                Toastify({
                    text: 'Silahkan memilih item yang akan diorder',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right'
                }).showToast();

                return false;
            }

            $(this).html('<i class="fa fa-spin fa-spinner"></i> Membuat order...');

            fetch(`{{ route('api.orders.store') }}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    customer_name: customerName.val(),
                    order_type: type.val(),
                    table_id: table.val(),
                    items: __temp_order_data,
                    note: note.val()
                })
            })
                .then(res => res.json())
                .then(res => {
                    if (res.error) {
                        $(this).html('<i class="fa fa-arrow-right"></i> Buat Order');
                        if (res.validations) {
                            for (field in res.validations) {
                                document.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    document.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        $(this).html('<i class="fa fa-check"></i> Berhasil!');

                        let orderModal = $('#orderModal');
                        
                        $('.order-title', orderModal).text(`Order #${res.order.order_number}`);
                        let orderData = $('#order-data');

                        $('.order-number', orderData).text(`#${res.order.order_number}`);
                        $('.order-table', orderData).text(res.table.name);
                        $('.total-price', orderData).text(`Rp ${formatMoney(res.order.total_price)}`);

                        $('.btn-print').attr('href', `{{ route('orders.print', false) }}/${res.order.id}`);

                        let usedTables = res.used_tables;
                        $('#table-select option').removeAttr('disabled');

                        usedTables.forEach((table) => {
                            $(`#table-select .table-${table.table_id}`).attr('disabled', 'disabled');
                        })

                        orderModal.modal('show');
                    }
                })
                .catch(errors => {
                    console.log(errors);
                })
        });

        let __temp_order_id = 0;

        $('#place-new-order2').click(function (e) {
            e.preventDefault();

            let customerName = $('#customer-name');
            let table = $('#table-select');
            let type = $('#order-type');
            let note = $('#order-note');

            if (customerName.val() == '') {
                customerName.addClass('is-invalid');
                return false;
            }
            customerName.removeClass('is-invalid');

            if (__temp_order_data.length == 0) {
                Toastify({
                    text: 'Silahkan memilih item yang akan diorder',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right'
                }).showToast();

                return false;
            }

            $(this).html('<i class="fa fa-spin fa-spinner"></i> Membuat order...');

            fetch(`{{ route('api.orders.store') }}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    customer_name: customerName.val(),
                    order_type: type.val(),
                    table_id: table.val(),
                    items: __temp_order_data,
                    note: note.val()
                })
            })
                .then(res => res.json())
                .then(res => {
                    if (res.error) {
                        $(this).html('<i class="fa fa-arrow-right"></i> Buat Order');
                        if (res.validations) {
                            for (field in res.validations) {
                                document.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    document.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        $(this).html('<i class="fa fa-check"></i> Berhasil!');

                        __temp_order_id = res.order.id;

                        let orderModal = $('#orderModal2');
                        
                        $('.order-title', orderModal).text(`Order #${res.order.order_number}`);
                        let orderData = $('#order-data2');

                        $('.order-number', orderData).text(`#${res.order.order_number}`);
                        if (res.table != null) {
                            $('.order-table', orderData).text(res.table.name);
                        }
                        $('.total-price', orderData).text(`Rp ${formatMoney(res.order.total_price)}`);

                        $('.btn-print').attr('href', `{{ route('orders.print', false) }}/${res.order.id}`);
                        $('.amount-input').data('total-payment', res.order.total_price);

                        orderModal.modal('show');
                    }
                })
                .catch(errors => {
                    console.log(errors);
                })
        });

        $('#orderModal, #orderModal2').on('hidden.bs.modal', function (e) {
            resetOrder();
        });
        $('.reset-btn').click(function (e) {
            resetOrder();

            Toastify({
                text: 'Order berhasil direset',
                duration: 3000,
                gravity: 'bottom',
                position: 'left'
            }).showToast();
        });

        $('.amount-input').keyup(function (e) {
            e.preventDefault();

            let total = $(this).data('total-payment');
            let amount = $(this).val();

            let back = amount - total;
            if (back > 0) {
                $('.back-input').val(formatMoney(back))
            }
            else {
                $('.back-input').val('0,00');
            }
        });

        $('#pay-form').submit(function (e) {
            e.preventDefault();

            let payBtn = $('.pay-btn', $(this));
            let container = $('.message-container', $(this));

            payBtn.html('<i class="fa fa-spin fa-spinner"></i> Memproses pembayaran...');
            payBtn.attr('disabled', 'disabled');

            let amount = $('.amount-input', $(this)).val();
            let orderId = __temp_order_id;

            fetch(`{{ route('api.orders.payment', false) }}/${orderId}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        payBtn.html('<i class="fa fa-check"></i> Berhasil!');

                        container.addClass('alert alert-success').text(res.message)
                    }
                })
                .catch(errors => {
                    container.html(errors);
                })
        })
    </script>
@endpush