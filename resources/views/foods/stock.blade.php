@extends('layouts.master')
@section('title', 'Kelola Stok Makanan')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Kelola Stok</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.foods.index') }}">Makanan</a></div>
              <div class="breadcrumb-item">Kelola Stok</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Stok Makanan</h5>
                        </div>
                        <div class="table-responsive">
                            <form action="#" method="post" id="stock-form">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Makanan</th>
                                            <th scope="col">Tersedia</th>
                                            <th scope="col">Stok</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($foods as $item)
                                            <tr>
                                                <th scope="row">{{ $item->id }}</th>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    <label for="status-{{ $item->id }}">
                                                        <input type="checkbox" name="is_available[{{ $item->id }}]" id="status-{{ $item->id }}" value="1" @if ($item->is_available) checked @endif>
                                                        Tersedia
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text" name="stock[{{ $item->id }}]" id="stock-{{ $item->id }}" class="form-control stock-input" value="{{ $item->stock }}">
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" data-id="{{ $item->id }}" class="btn btn-primary save-btn"><i class="fa fa-check"></i> Simpan</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right">
                                                <button type="submit" class="btn btn-primary">Simpan Semua</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection

@push('custom_js')
    <script src="{{ asset('assets/plugins/toastify-js/src/toastify.js') }}"></script>
    <script>
        let bearerToken = localStorage.getItem('accessToken');
        let saveBtn = document.querySelectorAll('.save-btn');
        saveBtn.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();

                let id = btn.getAttribute('data-id');
                let isAvailable = document.querySelector(`#status-${id}`).checked;
                let stock = document.querySelector(`#stock-${id}`).value;

                fetch(`{{ route('api.foods.update', false) }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer '+ bearerToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        section: 'stock',
                        isAvailable: isAvailable,
                        stock: stock
                    })
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Toastify({
                                text: `${res.message} ${res.food.name}`,
                                duration: 3000,
                                gravity: 'bottom',
                                position: 'right'
                            }).showToast();
                        }
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            });
        });

        let stockForm = document.querySelector('#stock-form');
        let submitBtn = stockForm.querySelector('[type=submit]');
        stockForm.addEventListener('submit', (e) => {
            e.preventDefault();

            submitBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menyimpan...';
            submitBtn.setAttribute('disabled', 'disabled');

            let stockData = new FormData(stockForm);
            stockData.append('_method', 'PUT');

            fetch(`{{ route('api.foods.stock') }}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken
                },
                body: stockData
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        submitBtn.innerHTML = 'Simpan Semua';
                        submitBtn.removeAttribute('disabled');

                        Toastify({
                            text: res.message,
                            duration: 3000,
                            gravity: 'bottom',
                            position: 'right'
                        }).showToast();
                    }
                })
                .catch(errors => {
                    console.log(errors);
                });
        });
    </script>
@endpush
