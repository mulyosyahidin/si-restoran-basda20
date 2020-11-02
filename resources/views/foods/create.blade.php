@extends('layouts.master')
@section('title', 'Tambah Makanan')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tambah Makanan</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.foods.index') }}">Makanan</a></div>
              <div class="breadcrumb-item">Tambah Makanan</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <form action="{{ route('admin.foods.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Makanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama:</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" id="name" required="required">
                                
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="price">Harga:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required="required">
                                    </div>

                                    @error('price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="desc">Deskripsi:</label>
                                    <textarea name="description" id="desc" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="stock">Stok:</label>
                                    <input type="number" name="stock" id="stock" value="{{ old('stock') }}" class="form-control @error('stock') is-invalid @enderror" required="required" min="1">
                                
                                    @error('stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" value="Tambah" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Foto</h5>

                                <span class="ml-auto">
                                    <a href="#" class="btn btn-info btn-sm add-picture-btn"><i class="fa fa-plus"></i></a>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="file" name="pictures[0]" class="form-control @error('pictures.*') is-invalid @enderror">
                                
                                    @if ($errors->get('pictures.*'))
                                        @foreach ($errors->get('pictures.*') as $item)
                                            @foreach ($item as $msg)
                                                <div class="invalid-feedback">
                                                    {{ $msg }}
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>

                                <div class="add-picture-container"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Kategori</h5>
                            </div>
                            <div class="card-body">
                                @forelse ($categories as $item)
                                    <div class="form-group">
                                        <label for="category-{{ $item->id }}">
                                            <input type="checkbox" name="categories[]" value="{{ $item->id }}" id="category-{{ $item->id }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                @empty
                                    <div class="alert alert-info">
                                        Tidak ada data kategori
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </form>
          </div>
        </section>
      </div>
@endsection

@push('custom_js')
    <script>
        let addPictureBtn = document.querySelector('.add-picture-btn');
        let addPictureContainer = document.querySelector('.add-picture-container');
        let n = 1;

        addPictureBtn.addEventListener('click', (e) => {
            e.preventDefault();

            let newFormGroup = document.createElement('div');
            newFormGroup.classList.add('form-group');

            let newInputField = document.createElement('input');
            newInputField.setAttribute('type', 'file');
            newInputField.setAttribute('name', `pictures[${n}]`);
            newInputField.classList.add('form-control');

            newFormGroup.appendChild(newInputField);
            addPictureContainer.appendChild(newFormGroup);

            n++;
        });
    </script>
@endpush