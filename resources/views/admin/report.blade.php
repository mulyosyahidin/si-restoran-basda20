@extends('layouts.master')
@section('title', 'Laporan')

@section('content')
    <!-- Main Content -->
<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Data Laporan ({{ $filterTitle[$filter] }})</h1>

        <div class="ml-auto select-date-container d-none">
          <input type="text" id="select-date" class="form-control d-none" placeholder="Pilih tanggal">
          <input type="text" id="select-date2" class="form-control d-none" placeholder="Pilih tanggal">

          <input type="text" id="select-range-date" class="form-control d-none" placeholder="Pilih rentang tanggal">
          <input type="text" id="select-range-date2" class="form-control d-none" placeholder="Pilih rentang tanggal">
        </div>

        <div class="ml-auto">
          <select id="select-filter" class="form-control">
            <option value="0" disabled="disabled">Pilih filter</option>
            <option value="1"@if ($filter == '1') selected @endif>Semua</option>
            <option value="2"@if ($filter == '2') selected @endif>Hari Ini</option>
            <option value="3"@if ($filter == '3') selected @endif>Kemarin</option>
            <option value="4"@if ($filter == '4') selected @endif>Minggu Ini</option>
            <option value="5"@if ($filter == '5') selected @endif>Bulan Ini</option>
            <option value="6"@if ($filter == '6') selected @endif>Tahun Ini</option>
            <option value="7"@if ($filter == '7') selected @endif>Tanggal tertentu</option>
            <option value="8"@if ($filter == '8') selected @endif>Rentang tanggal</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="fas fa-table"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Order</h4>
              </div>
              <div class="card-body">
                {{ $order }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Pendapatan</h4>
              </div>
              <div class="card-body">
                Rp {{ number_format($income, 2, ',', '.') }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            @if (count($reports) > 0)
                <div class="table-responsive">
                  <table class="table table-striped table-hovered table-bordered">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Waiter</th>
                        <th scope="col">Pelanggan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Jumlah Item</th>
                        <th scope="col">Total Harga</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reports as $index => $item)
                          <tr>
                            <th scope="row">{{ (($reports->currentPage() - 1) * $reports->perPage()) + ($index + 1) }}</th>
                            <td><a target="_blank" href="{{ route('orders.show', $item->id) }}">#{{ $item->order_number }}</a></td>
                            <td>{{ $item->waiter->name }}</td>
                            <td>{{ $item->customer_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, DD MMM YYYY HH:mm') }}</td>
                            <td>{{ $item->total_item }}</td>
                            <td>Rp {{ number_format($item->total_price, 2, ',', '.') }}</td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  {{ $reports->appends(request()->except('page'))->links() }}
                </div>
            @else
                <div class="card-body">
                  <div class="alert alert-info">Tidak ada data untuk ditampilkan</div>
                </div>
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@section('custom_head')
  <link rel="stylesheet" href="{{ asset('assets/plugins/toastify-js/src/toastify.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/air-datepicker/dist/css/datepicker.min.css') }}">

  <style>
    #datepickers-container .-selected- {
      background: #3ABAF4;
      color: #fff !important;
    }
    #datepickers-container .-current- {
      color: #3ABAF4;
    }
    #datepickers-container .datepicker--time-sliders > *:not(:first-child) {
      margin-top: 9px;
      margin-bottom: 6px;
    }
    #datepickers-container .datepicker--time-sliders > *:first-child {
      margin-top: 6px;
    }
    #datepickers-container input[type=range]::-webkit-slider-thumb {
      box-shadow: 1px 1px 1px #000000, 0px 0px 1px #0d0d0d;
      border: 0px;
      height: 22px;
      width: 22px;
      border-radius: 5px;
      background: #3ABAF4ee;
      cursor: pointer;
      -webkit-appearance: none;
      margin-top: -11px;
    }
  </style>
@endsection

@push('custom_js')
  <script src="{{ asset('assets/plugins/toastify-js/src/toastify.js') }}"></script>
  <script src="{{ asset('assets/plugins/air-datepicker/dist/js/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/air-datepicker/dist/js/i18n/datepicker.id.js') }}"></script>

  <script>
    let selectFilter = document.querySelector('#select-filter');

    selectFilter.addEventListener('change', function (e) {
      e.preventDefault();

      let filter = selectFilter.value;
      if (filter == 7) {
        $('.select-date-container').removeClass('d-none');
        $('#select-date').removeClass('d-none');
        $('#select-range-date, #select-range-date2').addClass('d-none');

        let selectDate = $('#select-date').datepicker({
          language: 'id',
          dateFormat: 'dd/mm/yyyy',
          onShow: function (dp, animationCompleted) {
            if ( ! animationCompleted) {
              if (dp.$datepicker.find('button').html() === undefined) {
                dp.$datepicker.append('<button type="button" class="btn btn-secondary btn-block" disabled="disabled"><i class="fa fa-check"></i> OK</button>')
                dp.$datepicker.find('button').click(function (event) {
                  dp.hide()
                })
              }
            }
          },
          onSelect: function (formattedDate, date, dp) {
            if (formattedDate.length > 0) {
              dp.$datepicker.find('button').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
            }
            else {
              dp.$datepicker.find('button').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
            }
          },
          onHide: function (dp, animationCompleted) {
            if (dp.selectedDates.length > 0) {
              let selDate = new Date(selectDate.selectedDates);

              let date = selDate.getDate();
              let month = selDate.getMonth()
              let year = selDate.getFullYear();

              if (date < 10) {
                date = `0${date}`;
              }
              month += 1;

              let buildDate = `${year}-${month}-${date}`;
              window.location = `{{ route('admin.report') }}?filter=7&date=${buildDate}`;
            }
          }
        }).data('datepicker');

        selectDate.show();
      }
      else if (filter == 8) {
        $('.select-date-container').removeClass('d-none');
        $('#select-range-date').removeClass('d-none');
        $('#select-date, #select-date2').addClass('d-none');

        let selectDate = $('#select-range-date').datepicker({
          language: 'id',
          range: true,
          multipleDatesSeparator: ' - ',
          dateFormat: 'dd/mm/yyyy',
          onShow: function (dp, animationCompleted) {
            if ( ! animationCompleted) {
              if (dp.$datepicker.find('button').html() === undefined) {
                dp.$datepicker.append('<button type="button" class="btn btn-secondary btn-block" disabled="disabled"><i class="fa fa-check"></i> OK</button>')
                dp.$datepicker.find('button').click(function (event) {
                  dp.hide()
                })
              }
            }
          },
          onSelect: function (formattedDate, date, dp) {
            if (formattedDate.length > 0) {
              dp.$datepicker.find('button').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
            }
            else {
              dp.$datepicker.find('button').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
            }
          },
          onHide: function (dp, animationCompleted) {
            if (dp.selectedDates.length > 0) {
              let fromDate = new Date(selectDate.selectedDates[0]);

              let date = fromDate.getDate();
              let month = fromDate.getMonth()
              let year = fromDate.getFullYear();

              if (date < 10) {
                date = `0${date}`;
              }
              month += 1;

              let toDate = new Date(selectDate.selectedDates[1]);

              let toDay = toDate.getDate();
              let toMonth = toDate.getMonth()
              let toYear = toDate.getFullYear();

              if (toDay < 10) {
                toDay = `0${toDay}`;
              }
              toMonth += 1;

              let buildDate = `[${year}-${month}-${date}][${toYear}-${toMonth}-${toDay}]`;
              window.location = `{{ route('admin.report') }}?filter=8&date=${buildDate}`;
            }
          }
        }).data('datepicker');

        selectDate.show();
      }
      else if (filter < 7 && filter > 0) {
        window.location = `{{ route('admin.report') }}?filter=${filter}`;
      }
      else {
        Toastify({
          text: 'Pilihan tidak valid!',
          duration: 3000,
          gravity: 'top',
          position: 'right'
        }).showToast();
        }
    });

    @if ($filter == 7)
      $('.select-date-container').removeClass('d-none');
      $('#select-date2').removeClass('d-none').val('{{ $getDate }}');

      let selectDate = $('#select-date2').datepicker({
        language: 'id',
        dateFormat: 'dd/mm/yyyy',
        onHide: function (dp, animationCompleted) {
          if (dp.selectedDates.length > 0) {
            let selDate = new Date(selectDate.selectedDates);

            let date = selDate.getDate();
            let month = selDate.getMonth()
            let year = selDate.getFullYear();

            if (date < 10) {
              date = `0${date}`;
            }
            month += 1;

            let buildDate = `${year}-${month}-${date}`;
            window.location = `{{ route('admin.report') }}?filter=7&date=${buildDate}`;
          }
        },
        onShow: function (dp, animationCompleted) {
          if ( ! animationCompleted) {
            if (dp.$datepicker.find('button').html() === undefined) {
              dp.$datepicker.append('<button type="button" class="btn btn-secondary btn-block" disabled="disabled"><i class="fa fa-check"></i> OK</button>')
              dp.$datepicker.find('button').click(function (event) {
                dp.hide()
              })
            }
          }
        },
        onSelect: function (formattedDate, date, dp) {
          if (formattedDate.length > 0) {
            dp.$datepicker.find('button').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
          }
          else {
            dp.$datepicker.find('button').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
          }
        }
      }).data('datepicker');
      
      selectDate.selectDate(new Date($('#select-date2').val()))
    @endif

    @if ($filter == 8)
      $('.select-date-container').removeClass('d-none');
      $('#select-range-date2').removeClass('d-none').val('{{ $getDate }}');

      let selectDate = $('#select-range-date2').datepicker({
        language: 'id',
        range: true,
        multipleDatesSeparator: ' - ',
        dateFormat: 'dd/mm/yyyy',
        onShow: function (dp, animationCompleted) {
          if ( ! animationCompleted) {
            if (dp.$datepicker.find('button').html() === undefined) {
              dp.$datepicker.append('<button type="button" class="btn btn-secondary btn-block" disabled="disabled"><i class="fa fa-check"></i> OK</button>')
              dp.$datepicker.find('button').click(function (event) {
                dp.hide()
              })
            }
          }
        },
        onSelect: function (formattedDate, date, dp) {
          if (formattedDate.length > 0) {
            dp.$datepicker.find('button').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
          }
          else {
            dp.$datepicker.find('button').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
          }
        },
        onHide: function (dp, animationCompleted) {
          if (dp.selectedDates.length > 0) {
            let fromDate = new Date(selectDate.selectedDates[0]);

            let date = fromDate.getDate();
            let month = fromDate.getMonth()
            let year = fromDate.getFullYear();

            if (date < 10) {
              date = `0${date}`;
            }
            month += 1;

            let toDate = new Date(selectDate.selectedDates[1]);

            let toDay = toDate.getDate();
            let toMonth = toDate.getMonth()
            let toYear = toDate.getFullYear();

            if (toDay < 10) {
              toDay = `0${toDay}`;
            }
            toMonth += 1;

            let buildDate = `[${year}-${month}-${date}][${toYear}-${toMonth}-${toDay}]`;
            window.location = `{{ route('admin.report') }}?filter=8&date=${buildDate}`;
          }
        }
      }).data('datepicker');
    @endif
  </script>
@endpush