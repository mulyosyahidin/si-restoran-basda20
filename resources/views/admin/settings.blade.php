@extends('layouts.master')
@section('title', 'Pengaturan Umum')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Pengaturan Umum</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item">Pengaturan Umum</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row">
                <div class="col-8">
                    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Identitas</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Nama Situs:</label>
                                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="name" name="site_name" value="{{ old('site_name', getSiteName()) }}" required>

                                    @error('site_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="site_logo">Logo:</label>
                                    <input type="file" name="site_logo" id="site_logo" class="form-control @error('site_logo') is-invalid @enderror">

                                    @error('site_logo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="site_desc">Deskripsi:</label>
                                    <textarea name="site_description" id="site_desc" cols="30" rows="10" class="form-control @error('site_description') is-invalid @enderror">{{ old('site_description', getSetting('siteDescription')) }}</textarea>
                                
                                    @error('site_description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" value="Simpan" class="btn btn-primary">
                            </div>
                        </div>

                        <input type="hidden" name="section" value="general">
                    </form>
                </div>

                <div class="col-4">
                    <form action="{{ route('admin.settings.update') }}" method="post">
                        @csrf
                        @method('put')

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Kontak</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" value="{{ old('email', getSetting('siteEmail')) }}" id="email" class="form-control @error('email') is-invalid @enderror">

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tel">HP:</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', getSetting('sitePhoneNumber')) }}" id="tel" class="form-control @error('phone_number') is-invalid @enderror">

                                    @error('phone_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat:</label>
                                    <textarea name="address" id="address" cols="30" rows="10" class="form-control @error('address') is-invalid @enderror">{{ old('address', getSetting('siteAddress')) }}</textarea>

                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" value="Simpan" class="btn btn-primary">
                            </div>
                        </div>

                        <input type="hidden" name="section" value="contact">
                    </form>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection