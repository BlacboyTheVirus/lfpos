@extends('layouts.app')

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
 
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
  
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Invoices') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->

    
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-success card-outline">
                        

                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="icheck-info d-inline">
                                            <input type="checkbox" id="amount-due-check" >
                                            <label for="amount-due-check">View Account Receivable Invoices</label>
                                          </div>   
                                    </div>

                                    <div class="card-tools">
                
                                        <a class="btn btn-block btn-success"  href="{{ route('invoices.create') }}">
                                            <i class="fa fa-plus"></i> New Customer
                                        </a>
                                      </div>

                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class="card-body">
                            
                            <table id="invoiceTable" class="table  table-hover">
                                <thead>
                                <tr> 
                                  <th class="exportable">Invoice Date</th>
                                  <th class="exportable">Invoice Code</th>
                                  <th class="exportable">Customer Name</th>
                                  <th class="exportable" style="text-align: right">Invoice Total</th>
                                  <th class="exportable" style="text-align: right">Amount Paid</th>
                                  <th class="exportable" style="text-align: right">Amount Due</th>
                                  <th class="exportable">Payment Staus</th>
                                  <th class="nosort exportable">Created by </th>
                                  <th class="nosort"></th>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>

                                <tfoot>
                                    <tr style="background-color:#f4f6f9 !important">
                                        <th class="exportable"></th>
                                        <th class="exportable"></th>
                                        <th class="exportable" style="text-align: right">Total</th>
                                        <th class="exportable" style="text-align: right"></th>
                                        <th class="exportable" style="text-align: right"></th>
                                        <th class="exportable" style="text-align: right"></th>
                                        <th class="exportable"></th>
                                        <th class="nosort exportable"></th>
                                        <th class="nosort"></th> 
                                    </tr>
                                </tfoot>
                                
                              </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <style>
        #invoiceTable td:nth-child(4){text-align: right;}
        #invoiceTable td:nth-child(5){text-align: right;}
        #invoiceTable td:nth-child(6){text-align: right;}
    </style>

    

@endsection


@section('modals')
        
        <!-- VIEW PAYMENTS MODAL -->  
        <div class="modal hide fade" tabindex="-1" id="modal-payments">
            <div class="modal-dialog  modal-lg">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payments</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" id="modal-payments-detail">

                    </div>

                    <div class="modal-footer justify-content-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div> 

            </div>  
        </div>
        
@endsection


@section('scripts')

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.5/api/sum().js"></script>

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    
    <script>
        $('document').ready(function(){

           

            //    //validate EDIT FORM
            //   var editvalidator = $('#customers-edit-form').validate({
            //         rules: {
            //             customer_name: {
            //                 required: true,
            //             },
            //             customer_phone: {
            //                 required: false,
            //             },
            //             customer_email: {
            //                 required: false,
            //                 email: true,
            //             },
            //         },
            //         messages: {
            //             customer_email: {
            //                 email: "Please enter a valid email address"
            //             },
            //         },
            //         errorElement: 'span',
            //         errorPlacement: function (error, element) {
            //             error.addClass('invalid-feedback');
            //             element.closest('.form-group').append(error);
            //         },
            //         highlight: function (element, errorClass, validClass) {
            //             $(element).addClass('is-invalid');
            //         },
            //         unhighlight: function (element, errorClass, validClass) {
            //             $(element).removeClass('is-invalid');
            //         }
            //     });

            //       //validate CREATE FORM
            //   var createvalidator = $('#customers-create-form').validate({
            //         rules: {
            //             customer_name: {
            //                 required: true,
            //             },
            //             customer_phone: {
            //                 required: false,
            //             },
            //             customer_email: {
            //                 required: false,
            //                 email: true,
            //             },
            //         },
            //         messages: {
            //             customer_email: {
            //                 email: "Please enter a valid email address"
            //             },
            //         },
            //         errorElement: 'span',
            //         errorPlacement: function (error, element) {
            //             error.addClass('invalid-feedback');
            //             element.closest('.form-group').append(error);
            //         },
            //         highlight: function (element, errorClass, validClass) {
            //             $(element).addClass('is-invalid');
            //         },
            //         unhighlight: function (element, errorClass, validClass) {
            //             $(element).removeClass('is-invalid');
            //         }
            //     });


        
          
            /////////////////////////////////////////////
            //Fetch all Invoice Records for Datatable
            ////////////////////////////////////////////
            function load_datatable(show_account_receivable ='unchecked'){
                table =   $('#invoiceTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, 
                    lengthChange: false,
                    autoWidth: false,
                    info: true,
                    order: [[1, 'desc']],
                    ajax: {
                        url: "{{route('invoices.ajax')}}",
                        data: {
                            'show_account_receivable': show_account_receivable
                        }
                    },
                    columns: [
                        { data: 'invoice_date' },
                        { data: 'invoice_code' },
                        { data: 'customer_name' },
                        { data: 'invoice_grand_total', 
                        "render": function ( data, type, row, meta ) {
                                         return ( parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2}) );
                                  }
                        },
                        { data: 'invoice_amount_paid', 
                        "render": function ( data, type, row, meta ) {
                                         return ( parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2}) );
                                  }
                        },
                        { data: 'invoice_amount_due', 
                        "render": function ( data, type, row, meta ) {
                                         return ( parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2}) );
                                  }
                        },
                        { data: 'payment_status', 
                        "render": function ( data, type, row, meta ) {
                                        if (data == "Unpaid"){
                                            return ( "<span class='badge badge-danger'>"+data+"</span>" );
                                        } else if (data == "Partial"){
                                            return ( "<span class='badge badge-warning'>"+data+"</span>" );
                                        } else {
                                            return ( "<span class='badge badge-success'>"+data+"</span>" );
                                        }
                                        
                                  }
                        },
                        { data: 'created_by' },
                        { data: 'action'},
                    ],
                    language: {
                        processing: '<div style="padding:0.75rem;position: relative;z-index:99999;overflow: visible; background:#fff">Loading...</div>'
                    },
                    aoColumnDefs: [
                        
                        {bSortable: false,'aTargets': ['nosort']},
                        {searchable: false, "aTargets": ['nosort'] }
                    ],
                    buttons: [
                                {extend: "copy", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                                {extend: "csv", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                                {extend: "excel", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                                {extend: "pdfHtml5", footer:true, exportOptions: {columns: [ '.exportable' ]} },
                                {extend: 'print', footer:true, exportOptions: {columns: [ '.exportable' ]} }, 
                                "colvis"
                                ],
                    sDom: '<"row" <"#top.col-md-6"> <"col-md-6"f> > rt <"row" <"col-md-6"i> <"col-md-6"p> ><"clear">',
                    "initComplete": function(settings, json) {
                                    $(this).DataTable().buttons().container()
                                    .appendTo( ('#top'));
                                    },
                    
                                    drawCallback: function () {
                                        var api = this.api();
                                        var sum = 0;
                                        var formated = 0;
                                        //to show first th
                                        $(api.column(2).footer()).html('Total');

                                            sum = api.column(3, {page:'current'}).data().sum();
                                            //to format this sum
                                            formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:2});
                                            $(api.column(3).footer()).html('₦ '+ formated);

                                            sum = api.column(4, {page:'current'}).data().sum();
                                            //to format this sum
                                            formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:2});
                                            $(api.column(4).footer()).html('₦ '+ formated);

                                            sum = api.column(5, {page:'current'}).data().sum();
                                            //to format this sum
                                            formated = parseFloat(sum).toLocaleString(undefined, {minimumFractionDigits:2});
                                            $(api.column(5).footer()).html('₦ '+ formated);
                                        
		                             }
                        
                   
                }); // end DataTable
            
                $("#amount-due-check").change(function() {
                    $('#invoiceTable').DataTable().destroy();
                    if(this.checked){
                        load_datatable('checked');
                         
                    } else {
                        load_datatable();//default unchecked
                    }
                    
                });
            } // end load_datatable
             
            load_datatable();

            //Positive Decimal
            $("#customer_amount_due").inputFilter(function(value) {
                 return /^\d*[.]?\d{0,2}$/.test(value); 
            });

        }); //end Document Ready



        ////////////////////////////////////////
        /// VIEW PAYMENTS
        ////////////////////////////////////
                            
        function view_payments(id){
                //ajax call
                 $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                $.ajax({
                    url: "{{ route('invoices.paymentdetails') }}",
                    type: "get", //send it through get method
                    data: { 
                        id: id, 
                    },
                    success: function(response) {
                        $('#modal-payments-detail').html(response);
                        $('#modal-payments').modal('show');
                    },
                    error: function(xhr) {
                        //Do Something to handle error
                    }
                });
                
        }    
        
        
         ////////////////////////////////////////
        /// DELETE PAYMENT
        ////////////////////////////////////
                            
        function delete_payment(id){
            
            if (confirm("Do you want to delete the Payment?") == true) {
                $.ajax({
                    url: "/payments/delete/"+id,
                    type: "get", //send it through get method
                    data: { 
                        'id': id, 
                    },
                    success: function(response) {
                        if (response.status == 1){
                            $('#payment_'+id).remove(); 

                            var grand_total_row = parseFloat($('#grand_total').text().replace(/,/g, ''));
                            var total_payment_row = 0;

                            $('.payment_row').each(function(){
                                total_payment_row = total_payment_row + parseFloat($(this).text().replace(/,/g, ''));
                            });

                            console.log (grand_total_row);
                            console.log (total_payment_row);
                            console.log (grand_total_row - total_payment_row);

                            $('#total_payment').html(total_payment_row.toLocaleString(undefined, {minimumFractionDigits:2}));
                            $('#amount_due').html((grand_total_row - total_payment_row).toLocaleString(undefined, {minimumFractionDigits:2}) );

                            failed_sound.currentTime = 0;
                            failed_sound.play();

                            $('#invoiceTable').DataTable().ajax.reload();                            
                            toastr.success(response.message);
                            
                        } else {
                            toastr.error(response.message);
                        }
                    
                    },
                    error: function(xhr) {
                        toastr.error('Ooopsy! Something unintended just happened. ')
                    }
                }); // end ajax
            }
                
        }    





        var AdminLTEOptions = {
    /*https://adminlte.io/themes/AdminLTE/documentation/index.html*/
    sidebarExpandOnHover: true,
    navbarMenuHeight: "200px", //The height of the inner menu
    animationSpeed: 250,
  };



           

        
    </script>
@endsection