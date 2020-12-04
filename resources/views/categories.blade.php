@extends('layouts.master')
@section('title', 'Kelola Kategori')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Kelola Kategori</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item">Kelola Kategori</div>
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
                            <h5 class="card-title">Kelola Kategori</h5>

                            <span class="ml-auto">
                                <a href="#" class="btn btn-info btn-sm add-modal-btn"
                                    data-toggle="tooltip" title="Tambah Kategori Baru"><i class="fa fa-plus"></i></a>
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table style="width: 100%" class="table table-striped table-bordered table-hover" id="categories-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="row">ID</th>
                                        <th scope="row">Nama</th>
                                        <th scope="row">Deskripsi</th>
                                        <th scope="row">Foto</th>
                                        <th scope="row"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection

@section('custom_html')
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kategori Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="add-category-form">
            <div class="modal-body">
                <div class="alert-container"></div>

                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" class="form-control name-input" id="name" name="name" required autofocus>
    
                    <div class="invalid-feedback name-feedback"></div>
                </div>
    
                <div class="form-group">
                    <label for="picture">Foto:</label>
                    <input type="file" class="form-control picture-input" id="picture" name="picture">

                    <div class="invalid-feedback picture-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea name="description" id="description" cols="30" rows="10" class="form-control description-input"></textarea>
                
                    <div class="invalid-feedback description-feedback"></div>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="edit-category-form" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="alert-container"></div>

                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" class="form-control name-input" id="name" name="name" required autofocus>
    
                    <div class="invalid-feedback name-feedback"></div>
                </div>
    
                <div class="form-group">
                    <label for="picture">Foto:</label>
                    <input type="file" class="form-control picture-input" id="picture" name="picture">

                    <div class="invalid-feedback picture-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea name="description" id="description" cols="30" rows="10" class="form-control description-input"></textarea>
                
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
          <h5 class="modal-title">Hapus Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="delete-category-form">
            <div class="modal-body">
                <div class="alert-container">Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.</div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection

@push('custom_js')
    <!-- JS Libraies -->
    <script src="{{ asset('assets/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $('.add-modal-btn').click(function (e) {
            $('#addModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        let categoryTable = $('#categories-table').DataTable({
            ajax: {
                url: '{{ route('api.categories.index') }}',
                headers: {
                    "Authorization": "Bearer "+ passportAccessToken
                }
            },
            columns: [
                {
                    data: "id"
                },
                {
                    data: "name"
                },
                {
                    data: "description"
                },
                {
                    data: function (data, row, type) {
                        let url = data.pictureUrl;

                        return `<img class="img-fluid" src="${url}" />`;
                    }
                },
                {
                    data: function (data, row, type) {
                        let categoryId = data.id;
                        let categoryName = data.name;

                        return `
                            <a href="#" class="btn btn-warning btn-sm btn-edit-category" data-id="${categoryId}"
                                data-toggle="tooltip" title="Edit ${categoryName}"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete-category" data-id="${categoryId}"
                                data-toggle="tooltip" title="Hapus ${categoryName}"><i class="fa fa-trash"></i></a>
                        `
                    }
                }
            ]
        });

        let addCategoryForm = document.querySelector('#add-category-form');
        addCategoryForm.addEventListener('submit', (e) => {
            e.preventDefault();

            let alertContainer = addCategoryForm.querySelector('.alert-container');
            let submitBtn = addCategoryForm.querySelector('[type=submit]');

            submitBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menambah...';
            submitBtn.setAttribute('disabled', 'disabled');

            let formData = new FormData(addCategoryForm);
            fetch('{{ route('api.categories.store') }}', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ passportAccessToken
                },
                body: formData
            })
                .then(res => res.json())
                .then(res => {
                    if (res.error) {
                        submitBtn.innerHTML = 'Tambah';
                        submitBtn.removeAttribute('disabled');

                        if (res.validations) {
                            for (field in res.validations) {
                                addCategoryForm.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    addCategoryForm.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        categoryTable.ajax.reload();

                        alertContainer.classList.add('alert');
                        alertContainer.classList.add('alert-success');
                        alertContainer.innerHTML = res.message;

                        submitBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';

                        addCategoryForm.reset();

                        addCategoryForm.querySelector('.name-input')
                            .classList.remove('is-invalid');
                        addCategoryForm.querySelector('.picture-input')
                            .classList.remove('is-invalid');
                        addCategoryForm.querySelector('.description-input')
                            .classList.remove('is-invalid')
                    }
                })
                .catch(errors => {
                    alertContainer.innerHTML = errors;
                })
        });

        $('#addModal').on('hidden.bs.modal', function (e) {
            let alertContainer = addCategoryForm.querySelector('.alert-container');
            let submitBtn = addCategoryForm.querySelector('[type=submit]');

            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Tambah';
            submitBtn.removeAttribute('disabled')

        });

        $('#editModal').on('hidden.bs.modal', function (e) {
            let alertContainer = editCategoryForm.querySelector('.alert-container');
            let submitBtn = editCategoryForm.querySelector('[type=submit]');

            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            editCategoryForm.reset();

            submitBtn.innerHTML = 'Simpan';
            submitBtn.removeAttribute('disabled')

        });

        let editCategoryForm = document.querySelector('#edit-category-form');
        let editKategoriId = 0;

        $(document).on('click', '.btn-edit-category', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            editKategoriId = id;

            fetch(`{{ route('api.categories.show', false) }}/${id}`, {
                headers: {
                    'Authorization': 'Bearer '+ passportAccessToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    editCategoryForm.querySelector('.name-input')
                        .value = res.name;
                    editCategoryForm.querySelector('.description-input')
                        .value = res.description;

                    $('#editModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                })
                .catch(errors => {
                    console.log(errors);
                })
        });

        editCategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let alertContainer = editCategoryForm.querySelector('.alert-container');
            let submitBtn = editCategoryForm.querySelector('[type=submit]');

            submitBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menyimpan...';
            submitBtn.setAttribute('disabled', 'disabled');

            let nameInput = editCategoryForm.querySelector('.name-input');
            let descriptionInput = editCategoryForm.querySelector('.description-input');
            let pictureInput = editCategoryForm.querySelector('.picture-input');

            const formData = new FormData();
            formData.append('name', nameInput.value);
            formData.append('description', descriptionInput.value);
            if (pictureInput && pictureInput.files[0]) {
                formData.append('picture', pictureInput.files[0]);
            }
            formData.append('_method', 'PUT');

            fetch('{{ route('api.categories.update', false) }}/'+ editKategoriId, {
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
                                editCategoryForm.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    editCategoryForm.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        categoryTable.ajax.reload();

                        alertContainer.classList.add('alert');
                        alertContainer.classList.add('alert-success');
                        alertContainer.innerHTML = res.message;

                        submitBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';

                        nameInput.classList.remove('is-invalid');
                        descriptionInput.classList.remove('is-invalid');
                        pictureInput.classList.remove('is-invalid')
                    }
                })
                .catch(errors => {
                    alertContainer.innerHTML = errors;
                })
        });

        let deleteKategoriId = 0;
        $(document).on('click', '.btn-delete-category', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            deleteKategoriId = id;

            $('#deleteModal').modal('show');
        });

        let deleteKategoriForm = document.querySelector('#delete-category-form');
        deleteKategoriForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let deleteBtn = deleteKategoriForm.querySelector('[type=submit]');
            let deleteContainer = deleteKategoriForm.querySelector('.alert-container');

            deleteBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menghapus...';

            fetch(`{{ route('api.categories.destroy', false) }}/${deleteKategoriId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer '+ passportAccessToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        categoryTable.ajax.reload();
                        deleteContainer.innerHTML = res.message;

                        deleteBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';

                        setTimeout(() => {
                            $('#deleteModal').modal('hide')
                        }, 2500);
                    }
                })
                .catch(errors => {
                    deleteContainer.innerHTML = errors;
                })
        });

        $('#deleteModal').on('hidden.bs.modal', function (e) {
            let alertContainer = deleteKategoriForm.querySelector('.alert-container');
            let submitBtn = deleteKategoriForm.querySelector('[type=submit]');

            alertContainer.innerHTML = 'Anda yakin ingin menghapus category ini? Tindakan ini tidak dapat dibatalkan.';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Hapus';
            submitBtn.removeAttribute('disabled')
        });
    </script>
@endpush