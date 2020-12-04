@extends('layouts.master')
@section('title', 'Kelola Meja')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelola Meja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kelola Meja</div>
                </div>
            </div>

            <div class="section-body">
                @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">{{ session()->get('success') }}</p>
                @endif

                <div class="row">
                    @forelse ($tables as $item)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 table-{{ $item->id }}">
                        <article class="article article-style-b">
                            <div class="article-header">
                                <div class="article-image table-picture" data-background="@if (isset($item->media[0])){{ $item->media[0]->getFullUrl() }}@else{{ asset('assets/uploads/images/meja.webp') }}@endif">
                                </div>
                                <div class="article-badge">
                                    <div class="article-badge-item bg-info"><i class="fas fa-chair"></i> <span class="table-seat_number">{{ $item->seat_number }}</span> Kursi</div>
                                </div>
                            </div>
                            <div class="article-details">
                                <div class="article-title">
                                    <h2><a href="{{ route('admin.tables.show', $item->id) }}"><span class="table-name">{{ $item->name }}</span></a></h2>
                                </div>

                                <div class="dropdown float-right" style="margin-top: -32px">
                                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="#" data-id="{{ $item->id }}" class="dropdown-item edit-table-link">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="#" data-id="{{ $item->id }}" class="dropdown-item delete-table-link">
                                            <i class="fa fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                                <p class="text-justify"><span class="table-description">{{ $item->description }}</span></p>
                            </div>
                        </article>
                    </div>
                    @empty
                        
                    @endforelse
                </div>

            </div>
        </section>
    </div>
@endsection

@section('custom_html')
<div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Meja?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="delete-table-form">
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
                    <input type="text" class="form-control name-input" id="name" name="name" required autofocus>
    
                    <div class="invalid-feedback name-feedback"></div>
                </div>
    
                <div class="form-group">
                    <label for="seat_number">Jumlah kursi:</label>
                    <input type="number" class="form-control seat_number-input" id="seat_number" name="seat_number">

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
                    <textarea name="description" id="description" class="form-control description-input"></textarea>
                
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
@endsection

@push('custom_js')
    <script>
        let deleteLink = document.querySelectorAll('.delete-table-link');
        let deleteTableId = 0;

        deleteLink.forEach((deleteLink) => {
            deleteLink.addEventListener('click', (e) => {
                e.preventDefault();

                let id = deleteLink.getAttribute('data-id');
                deleteTableId = id;
                $('#deleteModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            })
        });

        let deleteTableForm = document.querySelector('#delete-table-form');
        let deleteTableBtn = deleteTableForm.querySelector('[type=submit]');
        deleteTableForm.addEventListener('submit', (e) => {
            e.preventDefault();

            deleteTableBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menghapus...';
            deleteTableBtn.setAttribute('disabled', 'disabled');

            fetch('{{ route('api.tables.destroy', false) }}/'+ deleteTableId, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer '+ passportAccessToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        deleteTableBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';
                        deleteTableBtn.removeAttribute('disabled');

                        let tableCol = document.querySelector(`.table-${deleteTableId}`);
                        tableCol.remove();

                        setTimeout(() => {
                            $('#deleteModal').modal('hide');
                        }, 3000);
                    }
                })
                .catch(errors => {
                    console.log(errors);
                })
        });

        $('#deleteModal').on('hidden.bs.modal', function (e) {
            let alertContainer = deleteTableForm.querySelector('.alert-container');
            let submitBtn = deleteTableForm.querySelector('[type=submit]');

            alertContainer.innerHTML = 'Anda yakin ingin menghapus meja ini? Tindakan ini tidak dapat dibatalkan.';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Hapus';
            submitBtn.removeAttribute('disabled')
        });

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
            
            fetch('{{ route('api.tables.update', false) }}/'+ editTableId, {
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
                        let tableCol = document.querySelector(`.table-${editTableId}`);
                        tableCol.querySelector('.table-name')
                            .innerHTML = nameInput;
                        tableCol.querySelector('.table-seat_number')
                            .innerHTML = seatNumberInput;
                        tableCol.querySelector('.table-description')
                            .innerHTML = descriptionInput;
                        
                        if (pictureInput.files && pictureInput.files[0]) {
                            var reader = new FileReader();

                            reader.onload = function (e) {
                                tableCol.querySelector('.table-picture').setAttribute('data-background', e.target.result);
                                tableCol.querySelector('.table-picture').style.background = `url(${e.target.result})`;
                            }

                            reader.readAsDataURL(pictureInput.files[0]);
                        }

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
                            $('#editModal').modal('hide');
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