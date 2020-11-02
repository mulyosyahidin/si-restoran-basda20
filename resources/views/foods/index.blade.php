@extends('layouts.master')
@section('title', 'Kelola Makanan')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelola Makanan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kelola Makanan</div>
                </div>
            </div>

            <div class="section-body">
                @if (session()->has('success'))
                <p class="section-lead font-weight-bold text-success">{{ session()->get('success') }}</p>
                @endif

                <div class="row">
                    @forelse ($foods as $item)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 table-{{ $item->id }}">
                        <article class="article article-style-b">
                            <div class="article-header">
                                <div class="article-image table-picture" data-background="@if (isset($item->media[0])) {{ $item->media[0]->getFullUrl() }} @endif">
                                </div>
                                <div class="article-badge">
                                    <div class="article-badge-item bg-info"><span class="table-seat_number">Stok: {{ $item->stock }}</span></div>
                                </div>
                            </div>
                            <div class="article-details">
                                <div class="article-title">
                                    <h2><a href="{{ route('admin.foods.show', $item->id) }}"><span class="table-name">{{ $item->name }}</span></a></h2>
                                </div>

                                <div class="dropdown float-right" style="margin-top: -32px">
                                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('admin.foods.edit', $item->id) }}" data-id="{{ $item->id }}" class="dropdown-item">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </div>
                                <p class="text-justify">{{ $item->description }}</p>
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