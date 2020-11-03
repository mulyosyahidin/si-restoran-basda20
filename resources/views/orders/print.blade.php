<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Print Nota Order #{{ $order->order_number }}</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <style>
        body {
            padding-top: 30px
        }
    </style>
</head>

<body onload="window.print();">
    <div class="container">
        <div class="row">
            <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <address>
                            <strong>{{ getSIteName() }}</strong>
                            <br>
                            {{ getSetting('siteAddress') }}
                            <br>
                            {{ getSetting('siteEmail') }}
                            <br>
                            {{ getSetting('sitePhoneNumber') }}
                        </address>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                        <p>
                            Yth. {{ $order->customer_name }}
                        </p>
                        <p>
                            Tanggal order: <em>{{ $order->created_at }}</em>
                        </p>
                        <p>
                            No. Order: <em> #{{ $order->order_number }}</em>
                        </p>
                        @if ($order->status == 3)
                            <p><span class="text-success"><b>Sudah dibayar ({{ $order->updated_at }})</b></span></p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="text-center">
                        <h1>Order #{{ $order->order_number }}</h1>
                    </div>
                    </span>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                            <tr>
                                <td class="col-md-3">
                                    <em>{{ $item->food->name }}</em>
                                </td>
                                <td class="col-md-3 text-center">Rp {{ number_format($item->item_price, 2, ',', '.') }}</td>
                                <td class="col-md-3" style="text-align: center">{{ $item->item_qty }}</td>
                                <td class="col-md-3 text-center">Rp {{ number_format(($item->item_price * $item->item_qty), 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            
                            <tr>
                                <td>   </td>
                                <td>   </td>
                                <td class="text-right">
                                    <h4><strong>Total: </strong></h4>
                                </td>
                                <td class="text-center text-danger">
                                    <h4><strong>Rp {{ number_format($order->total_price, 2, ',', '.') }}</strong></h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>Nota ini berlaku sebagai bukti pembayaran yang sah. Dicetak pada {{ date('l, d M Y H:i') }}</p>
                </div>
            </div>
        </div>

</body>

</html>
