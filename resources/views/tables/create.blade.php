@extends('layouts.master')
@section('title', 'Tambah Meja')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tambah Meja</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.tables.index') }}">Meja</a></div>
              <div class="breadcrumb-item">Tambah Meja</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row">
                <div class="col-12 default-col">
                    <form action="{{ route('admin.tables.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Identitas</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama Meja: <span class="font-weight-bold text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="seat_number">Jumlah kursi:</label>
                                    <input type="number" class="form-control @error('seat_number') is-invalid @enderror" id="seat_number" name="seat_number" value="{{ old('seat_number') }}">

                                    @error('seat_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="picture">Foto:</label>
                                    <input type="file" name="picture" id="picture" class="form-control @error('picture') is-invalid @enderror">

                                    @error('picture')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Deskripsi:</label>
                                    <textarea name="description" id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>
                            </div>
                            <div class="card-footer text-rights">
                                <span class="mr-auto">
                                    Kolom bertanda <span class="font-weight-bold text-danger">*</span> wajib diisi
                                </span>
                                <input type="submit" value="Tambah" class="btn btn-primary float-right">
                            </div>
                        </div>

                        <input type="hidden" name="section" value="general">
                    </form>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection

@push('custom_js')
    <script>
        let pictureField = document.querySelector('#picture');
        let n = 0;

        pictureField.addEventListener('change', function (e) {
            e.preventDefault();

            let prevEl = document.querySelector(`.count-${n-1}`);
            if (prevEl != null) {
                prevEl.remove();
            }

            let row = document.querySelector('.row');
            let defaultCol = row.querySelector('.default-col');

            defaultCol.classList.remove('col-12');
            defaultCol.classList.add('col-8');

            let newCol = document.createElement('div');
            newCol.classList.add('col-4');
            newCol.classList.add(`count-${n}`);

            let newColCard = document.createElement('div');
            newColCard.classList.add('card');

            let newColCardBody = document.createElement('div');
            newColCardBody.classList.add('card-body');

            let newImageTag = document.createElement('img');
            newImageTag.setAttribute('id', 'picture-preview');
            newImageTag.classList.add('img-fluid');

            newColCardBody.appendChild(newImageTag);
            newColCard.appendChild(newColCardBody);
            newCol.appendChild(newColCard);
            row.appendChild(newCol);

            let pictureTag = newImageTag;

            if (pictureField.files && pictureField.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    pictureTag.setAttribute('src', e.target.result);
                }

                reader.readAsDataURL(pictureField.files[0]);
            }

            n++;
        })
    </script>
@endpush