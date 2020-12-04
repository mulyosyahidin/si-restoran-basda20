@extends('layouts.master')
@section('title', $table->name)

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $table->name }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('admin.tables.index') }}">Meja</a></div>
                    <div class="breadcrumb-item">{{ $table->name }}</div>
                </div>
            </div>

            <div class="section-body">
                @if (session()->has('success'))
                <p class="section-lead">{{ session()->get('success') }}</p>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Data Meja</h5>

                                @if (isset($table->media[0]))
                                <span class="ml-auto">
                                    <a href="#" class="btn btn-info btn-sm btn-image"
                                        data-toggle="modal" data-target="#pictureModal"><i class="fa fa-image"></i></a>
                                </span>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <td>Nama</td>
                                        <td><strong>{{ $table->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah kursi</td>
                                        <td><strong>{{ $table->seat_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi</td>
                                        <td><strong>{{ $table->description }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer text-right">
                                <a href="#" data-toggle="modal" data-target="#editModal" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                <a href="#" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Riwayat Order</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    Tidak ada data untuk ditampilkan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('custom_html')
<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Meja</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="edit-table-form" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="alert-container"></div>

                <div class="form-group">
                    <label for="name">Nama Meja:</label>
                    <input type="text" class="form-control name-input" id="name" value="{{ $table->name }}" name="name" required autofocus>
    
                    <div class="invalid-feedback name-feedback"></div>
                </div>
    
                <div class="form-group">
                    <label for="seat_number">Jumlah kursi:</label>
                    <input type="number" class="form-control seat_number-input" id="seat_number" value="{{ $table->seat_number }}" name="seat_number">

                    <div class="invalid-feedback set_number-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="picture">Foto:</label>
                    <input type="file" name="picture" id="picture" class="form-control picture-input">

                    <div class="invalid-feedback picture-feedback"></div>
                    <div class="text-muted">Pilih foto baru untuk mengganti yang lama</div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea name="description" id="description" class="form-control description-input">{{ $table->description }}</textarea>
                
                    <div class="invalid-feedback description-feedback"></div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-info">Simpan</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Meja?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.tables.destroy', $table->id) }}" method="post" id="delete-table-form">
            @csrf
            @method('DELETE')

            <div class="modal-body">
                <div class="alert-container">
                    Anda yakin ingin menghapus meja ini? Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
      </div>
    </div>
</div>

@if (isset($table->media[0]))
<div class="modal fade" tabindex="-1" role="dialog" id="pictureModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $table->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-0">
            <img src="{{ $table->media[0]->getFullUrl() }}" alt="{{ $table->name }}" class="img-fluid">
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tututp</button>
        </div>
      </div>
    </div>
</div>
@endif
@endsection

@push('custom_js')
    <script>
        let editTableLink = document.querySelectorAll('.edit-table-link');
        let editTableId = 0;
        let editTableForm = document.querySelector('#edit-table-form');

        editTableLink.forEach((editLink) => {
            editLink.addEventListener('click', (e) => {
                e.preventDefault();

                let id = editLink.getAttribute('data-id');
                editTableId = id;

                fetch(`{{ route('api.tables.show', false) }}/${id}`, {
                    headers: {
                        'Authorization': 'Bearer '+ passportAccessToken
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        editTableForm.querySelector('.name-input')
                            .value = res.name;
                        editTableForm.querySelector('.seat_number-input')
                            .value = res.seat_number;
                        editTableForm.querySelector('.description-input')
                            .value = res.description;

                        $('#editModal').modal('show');
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            })
        });

        editTableForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let alertContainer = editTableForm.querySelector('.alert-container');
            let submitBtn = editTableForm.querySelector('[type=submit]');

            submitBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menyimpan...';
            submitBtn.setAttribute('disabled', 'disabled');

            let nameInput = editTableForm.querySelector('.name-input').value;
            let seatNumberInput = editTableForm.querySelector('.seat_number-input').value;
            let descriptionInput = editTableForm.querySelector('.description-input').value;
            let pictureInput = editTableForm.querySelector('.picture-input');

            const formData = new FormData();
            formData.append('name', nameInput);
            formData.append('seat_number', seatNumberInput);
            formData.append('description', descriptionInput);
            if (pictureInput && pictureInput.files[0]) {
                formData.append('picture', pictureInput.files[0]);
            }
            formData.append('_method', 'PUT')
            
            fetch('{{ route('api.tables.update', $table->id) }}', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ passportAccessToken
                },
                body: formData
            })
                .then(res => res.json())
                .then(res => {
                    if (res.error) {
                        submitBtn.innerHTML = 'Simpan';
                        submitBtn.removeAttribute('disabled');

                        if (res.validations) {
                            for (field in res.validations) {
                                editTableForm.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    editTableForm.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        alertContainer.classList.add('alert');
                        alertContainer.classList.add('alert-success');
                        alertContainer.innerHTML = res.message;

                        submitBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';

                        editTableForm.querySelector('.name-input')
                            .classList.remove('is-invalid');
                        editTableForm.querySelector('.seat_number-input')
                            .classList.remove('is-invalid');
                        editTableForm.querySelector('.description-input')
                            .classList.remove('is-invalid');

                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    }
                })
                .catch(errors => {
                    alertContainer.innerHTML = errors;
                })
        });

        $('#editModal').on('hidden.bs.modal', function (e) {
            editTableForm.reset();

            let alertContainer = editTableForm.querySelector('.alert-container');
            let submitBtn = editTableForm.querySelector('[type=submit]');

            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Simpan';
            submitBtn.removeAttribute('disabled')

        });
    </script>
@endpush