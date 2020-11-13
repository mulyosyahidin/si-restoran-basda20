@extends('layouts.master')
@section('title', 'Welcome, Admin!')

@section('content')
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Dashboard</h1>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-hamburger"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Produk</h4>
            </div>
            <div class="card-body">
              {{ $stats['foods'] }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-danger">
            <i class="fas fa-table"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Order</h4>
            </div>
            <div class="card-body">
            {{ $stats['orders'] }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Pendapatan</h4>
            </div>
            <div class="card-body">
              Rp {{ number_format($stats['income'], 2, ',', '.') }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-success">
            <i class="fas fa-user"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Pelanggan</h4>
            </div>
            <div class="card-body">
              0
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 col-md-12 col-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>Statistik Order</h4>
          </div>
          <div class="card-body">
            <canvas id="orderStats" height="182"></canvas>
            <div class="statistic-details mt-sm-4">
              <div class="statistic-details-item">
                <div class="detail-value">{{ $stats['today_order'] }}</div>
                <div class="detail-name">Order Hari Ini</div>
              </div>
              <div class="statistic-details-item">
                <div class="detail-value">Rp {{ number_format($stats['today_income'], 2, ',', '.') }}</div>
                <div class="detail-name">Pendapatan Hari Ini</div>
              </div>
              <div class="statistic-details-item">
                <div class="detail-value">{{ $stats['week_order'] }}</div>
                <div class="detail-name">Order Minggu Ini</div>
              </div>
              <div class="statistic-details-item">
                <div class="detail-value">Rp {{ number_format($stats['week_income'], 2, ',', '.') }}</div>
                <div class="detail-name">Pendapatan Minggu Ini</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-12 col-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>Paling Banyak di Order</h4>
          </div>
          <div class="card-body">
            @if (count($stats['most_products']) > 0)
              @foreach ($stats['most_products'] as $item)
              <ul class="list-unstyled list-unstyled-border">
                <li class="media">
                  <img class="mr-3 rounded-circle" width="50" src="{{ $item->food->media[0]->getFullUrl() }}" alt="avatar">
                  <div class="media-body">
                    <div class="float-right text-primary"><span class="badge badge-pill badge-info">{{ $item->order_count }} Order</span></div>
                    <div class="media-title">{{ $item->food->name }}</div>
                    <span>Total order Rp {{ number_format(($item->food->price * $item->order_count), 2, ',', '.') }}</span>
                  </div>
                </li>
              </ul>
              @endforeach
            @else
                <div class="alert alert-info">Tidak ada data untuk ditampilkan</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('custom_js')
  <script src="{{ asset('assets/plugins/chart.js/dist/Chart.min.js') }}"></script>
  <script>"use strict";

    var statistics_chart = document.getElementById("orderStats").getContext('2d');
    
    var orderStats = new Chart(statistics_chart, {
      type: 'line',
      data: {
        labels: [
          @for ($i = 6; $i >= 0; $i--)
            @php
              $timestamp = time();
              $tm = 86400 * $i;
              $tm = $timestamp - $tm;

              echo '"'. getDay(date('D', $tm)) .' ('. date('d', $tm) .'/'. date('m', $tm) .')", ';
            @endphp
          @endfor
        ],
        datasets: [{
          label: 'Order',
          data: [
            @foreach ($stats['order_stats'] as $key => $count)
              {{ $count }},
            @endforeach
          ],
          borderWidth: 5,
          borderColor: '#6777ef',
          backgroundColor: 'transparent',
          pointBackgroundColor: '#fff',
          pointBorderColor: '#6777ef',
          pointRadius: 4
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              stepSize: 150
            }
          }],
          xAxes: [{
            gridLines: {
              color: '#fbfbfb',
              lineWidth: 2
            }
          }]
        },
      }
    });
    </script>
@endpush