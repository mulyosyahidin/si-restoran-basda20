@extends('layouts.master')
@section('title', 'Kelola User')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Pengaturan User</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item">Pengaturan User</div>
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
                            <h5 class="card-title">Kelola User</h5>

                            <span class="ml-auto">
                                <a href="#" class="btn btn-info btn-sm add-modal-btn"
                                    data-toggle="tooltip" title="Tambah User Baru"><i class="fa fa-plus"></i></a>
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table style="width: 100%" class="table table-striped table-hover" id="users-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="row">ID</th>
                                        <th scope="row">Nama</th>
                                        <th scope="row">Email</th>
                                        <th scope="row">Role</th>
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
          <h5 class="modal-title">Tambah User Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="add-user-form">
            <div class="modal-body">
                <div class="alert-container"></div>

                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" class="form-control name-input" id="name" name="name" required autofocus>
    
                    <div class="invalid-feedback name-feedback"></div>
                </div>
    
                <div class="row">
                    <div class="col-6 col-xs-12">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control email-input" required="required">
    
                            <div class="invalid-feedback email-feedback"></div>
                        </div>
                    </div>
                    <div class="col-6 col-xs-12">
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" class="form-control password-input" required="required" minlength="6">
    
                            <div class="invalid-feedback password-feedback"></div>
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select name="role" id="role" class="form-control">
                        @foreach ($roles as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
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
          <h5 class="modal-title">Ubah Data User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="edit-user-form">
            <div class="modal-body">
                <div class="alert-container"></div>

                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" class="form-control name-input" id="name" name="name" required autofocus>
    
                    <div class="invalid-feedback name-feedback"></div>
                </div>
    
                <div class="row">
                    <div class="col-6 col-xs-12">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control email-input" required="required">
    
                            <div class="invalid-feedback email-feedback"></div>
                        </div>
                    </div>
                    <div class="col-6 col-xs-12">
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" class="form-control password-input" minlength="6">
    
                            <div class="invalid-feedback password-feedback"></div>
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select name="role" id="role" class="form-control role-input">
                        @foreach ($roles as $item)
                            <option class="role-{{ $item->id }}" value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <div class="invalid-feedback role-feedback"></div>
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
          <h5 class="modal-title">Hapus User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="post" id="delete-user-form">
            <div class="modal-body">
                <div class="alert-container">Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</div>
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
        let bearerToken = localStorage.getItem('accessToken');

        $('.add-modal-btn').click(function (e) {
            $('#addModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        })

        let userTable = $('#users-table').DataTable({
            ajax: {
                url: '{{ route('api.users.index') }}',
                headers: {
                    "Authorization": "Bearer "+ bearerToken
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
                    data: "email"
                },
                {
                    data: "roles.0.name"
                },
                {
                    data: function (data, row, type) {
                        let userId = data.id;
                        let userName = data.name;

                        return `
                            <a href="#" class="btn btn-warning btn-sm btn-edit-user" data-id="${userId}"
                                data-toggle="tooltip" title="Edit ${userName}"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete-user" data-id="${userId}"
                                data-toggle="tooltip" title="Hapus ${userName}"><i class="fa fa-trash"></i></a>
                        `
                    }
                }
            ]
        });

        let addUserForm = document.querySelector('#add-user-form');
        addUserForm.addEventListener('submit', (e) => {
            e.preventDefault();

            let alertContainer = addUserForm.querySelector('.alert-container');
            let submitBtn = addUserForm.querySelector('[type=submit]');

            submitBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menambah...';
            submitBtn.setAttribute('disabled', 'disabled');

            let formData = new FormData(addUserForm);
            fetch('{{ route('api.users.store') }}', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken
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
                                addUserForm.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    addUserForm.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        userTable.ajax.reload();

                        alertContainer.classList.add('alert');
                        alertContainer.classList.add('alert-success');
                        alertContainer.innerHTML = res.message;

                        submitBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';

                        addUserForm.reset();

                        addUserForm.querySelector('.name-input')
                            .classList.remove('is-invalid');
                        addUserForm.querySelector('.email-input')
                            .classList.remove('is-invalid');
                        addUserForm.querySelector('.password-input')
                            .classList.remove('is-invalid')
                    }
                })
                .catch(errors => {
                    alertContainer.innerHTML = errors;
                })
        });

        $('#addModal').on('hidden.bs.modal', function (e) {
            let alertContainer = addUserForm.querySelector('.alert-container');
            let submitBtn = addUserForm.querySelector('[type=submit]');

            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Tambah';
            submitBtn.removeAttribute('disabled')

        });

        $('#editModal').on('hidden.bs.modal', function (e) {
            let alertContainer = editUserForm.querySelector('.alert-container');
            let submitBtn = editUserForm.querySelector('[type=submit]');

            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Simpan';
            submitBtn.removeAttribute('disabled')

        });

        let editUserForm = document.querySelector('#edit-user-form');
        let editUserId = 0;

        $(document).on('click', '.btn-edit-user', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            editUserId = id;

            fetch(`{{ route('api.users.show', false) }}/${id}`, {
                headers: {
                    'Authorization': 'Bearer '+ bearerToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    editUserForm.querySelector('.name-input')
                        .value = res.name;
                    editUserForm.querySelector('.email-input')
                        .value = res.email;
                    editUserForm.querySelector('.password-input')
                        .value = '';
                    editUserForm.querySelector(`.role-${res.roles[0].id}`)
                        .setAttribute('selected', 'selected');


                    $('#editModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                })
                .catch(errors => {
                    console.log(errors);
                })
        });

        editUserForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let alertContainer = editUserForm.querySelector('.alert-container');
            let submitBtn = editUserForm.querySelector('[type=submit]');

            submitBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menyimpan...';
            submitBtn.setAttribute('disabled', 'disabled');

            fetch('{{ route('api.users.update', false) }}/'+ editUserId, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: editUserForm.querySelector('.name-input').value,
                    email: editUserForm.querySelector('.email-input').value,
                    password: editUserForm.querySelector('.password-input').value,
                    role: editUserForm.querySelector('.role-input').value
                })
            })
                .then(res => res.json())
                .then(res => {
                    if (res.error) {
                        submitBtn.innerHTML = 'Simpan';
                        submitBtn.removeAttribute('disabled');

                        if (res.validations) {
                            for (field in res.validations) {
                                editUserForm.querySelector('.'+ field +'-input')
                                    .classList.add('is-invalid')
                                for(error in res.validations[field]) {
                                    editUserForm.querySelector('.'+ field +'-feedback')
                                        .innerHTML = res.validations[field][error]
                                }
                            }
                        }
                    }
                    else if (res.success) {
                        userTable.ajax.reload();

                        alertContainer.classList.add('alert');
                        alertContainer.classList.add('alert-success');
                        alertContainer.innerHTML = res.message;

                        submitBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil!';

                        editUserForm.querySelector('.name-input')
                            .classList.remove('is-invalid');
                        editUserForm.querySelector('.email-input')
                            .classList.remove('is-invalid');
                        editUserForm.querySelector('.password-input')
                            .classList.remove('is-invalid')
                    }
                })
                .catch(errors => {
                    alertContainer.innerHTML = errors;
                })
        });

        let deleteUserId = 0;
        $(document).on('click', '.btn-delete-user', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            deleteUserId = id;

            $('#deleteModal').modal('show');
        });

        let deleteUserForm = document.querySelector('#delete-user-form');
        deleteUserForm.addEventListener('submit', function (e) {
            e.preventDefault();

            let deleteBtn = deleteUserForm.querySelector('[type=submit]');
            let deleteContainer = deleteUserForm.querySelector('.alert-container');

            deleteBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Menghapus...';

            fetch(`{{ route('api.users.destroy', false) }}/${deleteUserId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer '+ bearerToken
                }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        userTable.ajax.reload();
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
            let alertContainer = deleteUserForm.querySelector('.alert-container');
            let submitBtn = deleteUserForm.querySelector('[type=submit]');

            alertContainer.innerHTML = 'Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.';
            alertContainer.classList.remove('alert');
            alertContainer.classList.remove('alert-success');

            submitBtn.innerHTML = 'Hapus';
            submitBtn.removeAttribute('disabled')
        });
    </script>
@endpush