@extends('layouts.master')
@section('title', auth()->user()->name)

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profil Saya</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Profil Saya</div>
                </div>
            </div>

            <div class="section-body">
                @if (session()->has('success'))
                    <p class="section-lead text-success">
                        <strong>{{ session()->get('success') }}</strong>
                    </p>
                @endif

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card profile-widget">
                            <div class="profile-widget-header">
                                <img alt="image" src="{{ getProfilePicture() }}"
                                    class="rounded-circle profile-widget-picture">
                            </div>
                            <div class="profile-widget-description">
                                <div class="profile-widget-name">{{ auth()->user()->name }} <div
                                        class="text-muted d-inline font-weight-normal">
                                        <div class="slash"></div> {{ auth()->user()->roles[0]->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-7">
                        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Nama:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>

                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="email">Email:</label>
                                                <input type="email" name="email" id="email"
                                                    value="{{ old('email', auth()->user()->email) }}"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    required="required">

                                                @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="password">Password:</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror">

                                                @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="picture">Foto profil:</label>
                                        <input type="file" name="picture" id="picture" class="form-control @error('picture') is-invalid @enderror">

                                        @error('picture')
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
        let pictureTag = document.querySelector('.profile-widget-picture');

        pictureField.addEventListener('change', (e) => {
            e.preventDefault();

            if (pictureField.files && pictureField.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    pictureTag.setAttribute('src', e.target.result);
                }

                reader.readAsDataURL(pictureField.files[0]);
            }
        });
    </script>
@endpush
