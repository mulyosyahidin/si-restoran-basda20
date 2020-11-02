@extends('layouts.master')
@section('title', $food->name)

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $food->name }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.foods.index') }}">Makanan</a></div>
              <div class="breadcrumb-item">{{ $food->name }}</div>
            </div>
          </div>

          <div class="section-body">
            @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">
                    <strong>{{ session()->get('success') }}</strong>
                </p>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <td>Nama</td>
                                        <td><span class="font-weight-bold">{{ $food->name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Stok</td>
                                        <td><span class="font-weight-bold">{{ $food->stock }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tersedia</td>
                                        <td>
                                            @if ($food->is_available == 1)
                                                <span class="badge badge-primary">Tersedia</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Harga</td>
                                        <td><span class="font-weight-bold">Rp {{ $food->price }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Kategori</td>
                                        <td>
                                            @forelse ($food->categories as $item)
                                                <span class="badge badge-info">{{ $item->name }}</span>
                                            @empty
                                                <span class="font-weight-bold">Tidak ada Kategori</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi</td>
                                        <td><span class="font-weight-bold">{{ $food->description }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#stockModal"><i class="fas fa-clipboard-check"></i></a>
                            <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Riwayat Order</h5>
                        </div>
                    </div>
                </div>
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
          <h5 class="modal-title">Hapus Makanan?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.foods.destroy', $food->id) }}" method="post">
            @csrf
            @method('DELETE')
            
            <div class="modal-body">
                <div class="alert-container">Anda yakin ingin menghapus data makanan ini? Tindakan ini tidak dapat dibatalkan.</div>
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