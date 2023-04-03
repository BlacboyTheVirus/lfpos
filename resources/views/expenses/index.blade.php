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
                    <h1 class="m-0">{{ __('Expenses List') }}</h1>
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
                                            &nbsp;
                                          </div>   
                                    </div>

                                    <div class="card-tools">
                
                                        <a class="btn btn-block btn-success" id="add-button" href="">
                                            <i class="fa fa-plus"></i> New Expense
                                        </a>
                                      </div>

                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class="card-body">
                            
                            <table id="expensesTable" class="table  table-hover">
                                <thead>
                                <tr> 
                                  <th class="exportable">Date</th>
                                  <th class="exportable">Category</th>
                                  <th class="exportable">Expense for</th>
                                  <th class="exportable">Amount</th>
                                  <th class="exportable" >Note</th>
                                  <th class="exportable" >Reference</th>
                                  <th class="exportable" >Created by</th>
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
                                        <th class=" exportable" ></th>
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
        #expensesTable td:nth-child(4){text-align: right;}
        #expensesTable td:nth-child(5){text-align: right;}
     
    </style>

    

@endsection


@section('modals')
       
        <!-- EDIT CUSTOMER MODAL -->  
        <div class="modal hide fade" tabindex="-1" id="modal-edit">
            <div class="modal-dialog modal-dialog-centered">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Expense <span class="text-info" id="edit_customer_code"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        
                        <form action = "{{ route('customers.update') }}" id="customers-edit-form" method="post"> 
                            @csrf
                        
                            <div class="form-group">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control form-control-border" id="customer_name" name="customer_name"  placeholder="Enter Name">
                            </div>

                            <div class="form-group">
                                <label for="customer_phone">Customer Phone</label>
                                <input type="text" class="form-control form-control-border" id="customer_phone" name="customer_phone"  placeholder="Enter Phone">
                            </div>

                            <div class="form-group">
                                <label for="customer_email">Customer Email</label>
                                <input type="text" class="form-control form-control-border" id="customer_email" placeholder="Enter Email">
                            </div>

                            <div class="form-group">
                                <label for="customer_amount_due">Previous Amount Due</label>
                                 <input type="text" class="form-control form-control-border" id="customer_amount_due" name="customer_amount_due" placeholder="Enter Previous Amount Due">
                            </div>

                            <input type="hidden" id="customer_id"  name="customer_id"  value="">
                            
                            
                    
                        </form>

                    </div>

                    <div class="modal-footer justify-content-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="editSave" >Save changes</button>
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

    <script src="{{ asset('plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    
    <script>
        $('document').ready(function(){

               //validate EDIT FORM
              var editvalidator = $('#customers-edit-form').validate({
                    rules: {
                        customer_name: {
                            required: true,
                        },
                        customer_phone: {
                            required: false,
                        },
                        customer_email: {
                            required: false,
                            email: true,
                        },
                    },
                    messages: {
                        customer_email: {
                            email: "Please enter a valid email address"
                        },
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });

             



            /////////////////////////////////////////////
            // Edit Button Clicked
            ////////////////////////////////////////////
            $( '#customerTable' ).on('click', ".edit-button", function(e){

                e.preventDefault(); 
                
                $('#customers-edit-form').trigger('reset');

                var customer_id = $(this).attr('id');
                $('#customers-edit-form #customer_id').val( customer_id );
                
                //ajax call
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                //populate edit form field
                var url = "{{ route('customers.edit', ':customer_id') }}";
                url = url.replace(':customer_id', customer_id);
                $.get(url, function (edata) {
                   $('#customers-edit-form #customer_name').val(edata.customer_name);
                   $('#customers-edit-form #customer_phone').val(edata.customer_phone);
                   $('#customers-edit-form #customer_email').val(edata.customer_email);
                   $('#customers-edit-form #customer_amount_due').val(edata.customer_amount_due);
                   $('#edit_customer_code').html(edata.customer_code);
                }); //end get

                
                $('#modal-edit').modal('show'); 

            });  // END EDIT BUTTON CLICKED


            ////////////////////////////////////////////
            // EDIT BUTTON CLICK TO SAVE
            ///////////////////////////////////////////
            $('#editSave').click(function(e){
                e.preventDefault();
                
                if(!editvalidator.form()){
                    return false;
                };
                
                //submit
                var formData = {
                    id: $('#customer_id').val(),
                    customer_name: $("#customers-edit-form #customer_name").val(),
                    customer_phone: $("#customers-edit-form #customer_phone").val(),
                    customer_email: $("#customers-edit-form #customer_email").val(),
                    customer_amount_due: $("#customers-edit-form #customer_amount_due").val(),
                };

                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('customers.update') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    if (data.status){
                        $('#modal-edit').modal('hide'); 
                        $('#customerTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                    } else{
                        toastr.error(data.message);
                    }
                });
                
            })
            

          
            /////////////////////////////////////////////
            //Fetch all Customer Records for Datatable
            ////////////////////////////////////////////
            function load_datatable(){
                table =   $('#expensesTable').DataTable({
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
                    ajax: {
                        url: "{{route('expenses.ajax')}}",
                        data: {}
                    },
                    columns: [
                        { data: 'expenses_date' },
                        { data: 'category_name' },
                        { data: 'expenses_for' },
                        { data: 'expenses_amount', 
                          sType: "numeric",
                          render: function ( data, type, row, meta ) {
                                         return ( parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2}) );
                          }
                        },
                        { data: 'expenses_note' },
                        { data: 'expenses_reference' },
                        { data: 'expenses_created_by' },
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
                                            formated = parseFloat(sum).toLocaleString( "en-US", {minimumFractionDigits:2});
                                            $(api.column(3).footer()).html('₦ '+ formated);

		                             }
                        
                   
                }); // end DataTable
            
                
            } // end load_datatable
            
            load_datatable();

            //Positive Decimal
             $("#expenses_amount").inputFilter(function(value) {
                 return /^\d*[.]?\d{0,2}$/.test(value); 
            });

        }); //end Document Ready

        var AdminLTEOptions = {
    /*https://adminlte.io/themes/AdminLTE/documentation/index.html*/
    sidebarExpandOnHover: true,
    navbarMenuHeight: "200px", //The height of the inner menu
    animationSpeed: 250,
  };
        

        function delete_expenses(id){
            if (confirm("Do you want to delete the Expense?") == true) {
                $.ajax({
                    url: "/expenses/delete/"+id,
                    type: "get", //send it through get method
                    success: function(response) {
                        if (response.status == 1){
                            $('#expensesTable').DataTable().ajax.reload();
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


    </script>
@endsection