@extends('layouts.app')

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css') }}">

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
                    <h1 class="m-0">{{ __('Payments') }}</h1>
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
                                            <label></label>
                                          </div>   
                                    </div>

                                    <div class="card-tools">
                
                                        &nbsp;
                                      </div>

                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class="card-body">
                            
                            <table id="paymentTable" class="table  table-hover">
                                <thead>
                                    <tr> 
                                    <th class="exportable">Payment Date</th>
                                    <th class="exportable" width="15%">Customer Name</th>
                                    <th class="exportable">Invoice Code</th>
                                    <th class="exportable"  style="text-align: right" width="15%">Amount</th>
                                    <th class="exportable"  style="text-align: center" >Payment Type</th>
                                    <th class="exportable">Note</th>
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
                                        <th class="exportable" ></th>
                                        <th class="exportable" ></th>
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
        #paymentTable td:nth-child(3){text-align: right;}
        #paymentTable td:nth-child(4){text-align: right;}
        #paymentTable td:nth-child(5){text-align: center;}
       
    </style>

    

@endsection


@section('modals')
        
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

    <script src="{{ asset('plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    
    <script>
        $('document').ready(function(){

           
            /////////////////////////////////////////////
            //Fetch all Invoice Records for Datatable
            ////////////////////////////////////////////
            function load_datatable(){
                table =   $('#paymentTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, 
                    fixedHeader: {
                    header: true,
                        headerOffset: $('.main-header').height()+15
                    },
                    lengthChange: true,
                    autoWidth: false,
                    info: true,
                    order: [[0, 'desc']],
                    ajax: {
                        url: "{{route('payments.ajax')}}",
                     
                    },
                    columns: [
                        { data: 'payment_date' },
                        { data: 'customer_name' },
                        { data: 'invoice_code' },
                        { data: 'amount', 
                        "render": function ( data, type, row, meta ) {
                                         return ( parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2}) );
                                  }
                        },
                        { data: 'payment_type' },
                        { data: 'payment_note' },
                        { data: 'payment_created_by' },
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
                    dom: '<"row" <"col-md-3"l> <"#top.col-md-6">  <"col-md-3"f> > rt <"row"  <"col-md-6"i> <"col-md-6"p> ><"clear">',
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
                                        
		                             }
                        
                   
                }); // end DataTable
            
                
            } // end load_datatable
             
            load_datatable();

           

        }); //end Document Ready



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
                            
                            $('#paymentTable').DataTable().ajax.reload();                            
                            
                            toastr.success(response.message);

                            failed_sound.currentTime = 0;
                            failed_sound.play();

                           
                            
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