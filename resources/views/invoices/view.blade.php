@extends('layouts.app')

@section('styles')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Invoice') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->







    <!-- Main content -->

    <div class="content">


        <div class="container-fluid">
            <div class="row">
                <div class="col-12">


                    <div class="invoice p-3 mb-3">

                        <div class="row">
                            <div class="col-12">

                                <div class="callout callout-success mb-4">
                                    <img src="{{ asset('images/logo2.png') }}">
                                    <h2 class="float-right  text-lg font-weight-bold">Invoice</h2>
                                </div>

                            </div>

                        </div>



                        <div class="row">
                            <div class="col-12 mb-3 mt-1">
                                <h4>
                                    <i class="fas fa-hashtag"></i> {{ $invoice->invoice_code }}
                                    <small class="float-right"><b>Date:</b> {{ $invoice->invoice_date }}</small>
                                </h4>
                            </div>

                        </div>


                        <div class="row invoice-info ">


                            <div class="col-sm-3 invoice-col">
                                From
                                <address>
                                    <strong><span class="text-md">Blacboy Kreative</span></strong><br>
                                    4/6 Cameroon Road by Gwari Road,<br>
                                    Kaduna - Nigeria<br>
                                    Phone : +234 803 5988 543<br>
                                    Email : info@blacboykreative.com
                                </address>
                            </div>



                            <div class="col-sm-3 invoice-col">
                                To
                                <address>
                                    <strong><span
                                            class="text-md">{{ $invoice->customer->customer_name }}</span></strong><br>

                                    Phone : {{ $invoice->customer->customer_phone }}<br>
                                    Email : {{ $invoice->customer->customer_email }}
                                </address>
                            </div>


                            <div class="col-sm-3 invoice-col">

                                <address>
                                    <br><b>Invoice Total:</b> ₦
                                    {{ number_format($invoice->invoice_grand_total, 2, '.', ',') }}<br>
                                    <b>Amount Paid:</b> ₦
                                    {{ number_format($invoice->invoice_amount_paid, 2, '.', ',') }}<br>
                                    <b>Amount Due:</b> ₦ {{ number_format($invoice->invoice_amount_due, 2, '.', ',') }}<br>
                                    <b>Payment Status:</b>
                                    <span
                                        class="badge text-sm  font-weight-normal 
                                            @if ($invoice->payment_status == 'Unpaid') badge-danger
                                            @elseif($invoice->payment_status == 'Partial') badge-warning
                                            @elseif ($invoice->payment_status == 'Paid')   badge-success @endif 
                                                                          ">
                                        {{ $invoice->payment_status }}</span><br>

                                </address>
                            </div>



                        </div>


                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr class="bg-gray-light">
                                            <th width="15%">Type</th>
                                            <th width="15%">Size (w x h) ft</th>
                                            <th width="15%">Unit Price</th>
                                            <th width="15%" class="text-center">Unit Amount</th>
                                            <th width="20%" class="text-center">Quantity</th>
                                            <th width="20%" class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($invoice->invoiceitems as $item)
                                            <tr>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ number_format($item->width, 2, '.', '') . ' X ' . number_format($item->height, 2, '.', '') }}
                                                </td>
                                                <td>{{ $item->unit_price }}</td>
                                                <td class="text-right">{{ number_format($item->unit_amount, 2, '.', ',') }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-right">{{ number_format($item->total_amount, 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>



                        <div class="row">
                            <div class=" order-2 order-sm-1 p-2 col-md-5">

                                <h3 class="card-title lead mb-2 font-weight-bold">Payment Details</h3>

                                        <div class="table table-responsive ">
                                            <table class="table text-nowrap table-condensed table-bordered table-hover">
                                                <thead>
                                                    <tr class="bg-gray-light">
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th class="text-center" width="30%">Note</th>
                                                        <th class="text-center">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_payment = 0; 
                                                    @endphp

                                                    @foreach ($invoice->payments as $key=>$payment)
                                                        @php
                                                            $total_payment = $total_payment + $payment->amount;
                                                        @endphp
                                                        
                                                        <tr>
                                                            <td> {{ ++$key }}</td>
                                                            <td>{{ $payment->payment_date }}</td>
                                                            <td>{{ $payment->payment_type }}</td>
                                                            <td>{{ $payment->payment_note }}</td>
                                                            <td class="text-right pr-3"> {{ number_format($payment->amount, 2, '.', ',') }} </td>
                                                        </tr>

                                                    @endforeach



                                                    <tfoot>
                                                        <tr class="bg-gray-light">
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th><b>Total</b></th>
                                                            <th class="text-right pr-3">{{ money_format($total_payment) }}</th>
                                                        </tr>
                                                    </tfoot>
                                                   
                                                </tbody>
                                            </table>
                                        </div>

                               

                            </div>

                            <div class=" order-1 order-sm-2 p-2  col-md-5 offset-2">
                                <div class="table-responsive">
                                    <table class="table text-right">
                                        <tbody>

                                            <tr style="border-top: 2px solid #cccccc">

                                                <th width="50%">Subtotal:</th>
                                                <td>&nbsp;</td>
                                                <td>{{ number_format($invoice->invoice_subtotal, 2, '.', ',') }}</td>

                                            </tr>
                                            <tr>
                                                <th>Less Discount</th>
                                                <td>&nbsp;</td>
                                                <td>{{ number_format($invoice->invoice_discount, 2, '.', ',') }}</td>

                                            </tr>
                                            <tr>
                                                <th>Roundoff </th>
                                                <td>&nbsp;</td>
                                                <td>{{ number_format($invoice->invoice_roundoff, 2, '.', ',') }}</td>

                                            </tr>
                                            <tr>
                                                <th>Total : </th>
                                                <td>&nbsp;</td>
                                                <td>₦ {{ number_format($invoice->invoice_grand_total, 2, '.', ',') }} </td>

                                            </tr>

                                        </tbody>
                                    </table>
                                </div>


                            </div>

                        </div>




                        <div class="row no-print">
                            <div class="col-12">
                                <a href="{{ route('invoices.edit', [$invoice->id]) }}" class="btn btn-success">
                                    <i class="fas fa-edit"></i>Edit Invoice
                                </a>

                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="fas fa-print"></i> Print Invoice
                                </button>

                                <button type="button" class="btn btn-success float-right">
                                    <i class="far fa-credit-card"></i> Print POS Receipt
                                </button>


                            </div>
                        </div>




                    </div>

                </div>
            </div>
        </div>


    </div> <!-- /. content -->
@endsection





@section('scripts')
    <script>
        $('document').ready(function() {



        }); //end Document Ready

        var AdminLTEOptions = {
            /*https://adminlte.io/themes/AdminLTE/documentation/index.html*/
            sidebarExpandOnHover: true,
            navbarMenuHeight: "200px", //The height of the inner menu
            animationSpeed: 250,
        };
    </script>
@endsection
