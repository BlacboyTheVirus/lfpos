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
                    <h1 class="m-0">{{ __('Expenses Category') }}</h1>
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
                                            <i class="fa fa-plus"></i> Add Category
                                        </a>
                                      </div>

                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class="card-body">
                            
                            <table id="categoryTable" class="table  table-hover ">
                                <thead>
                                <tr> 
                                  <th class="exportable">#</th>
                                  <th class="exportable">Category Name</th>
                                  <th class="exportable">Description</th>
                                  <th class="exportable">Status</th>
                                  <th class="nosort"></th>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>

                                {{-- <tfoot>
                                    <tr style="background-color:#f4f6f9 !important">
                                        <th class="exportable"></th>
                                        <th class="exportable"></th>
                                        <th class="exportable"></th>
                                        <th class="nosort"></th> 
                                    </tr>
                                </tfoot> --}}
                                
                              </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

   
    

@endsection


@section('modals')
        <!-- ADD CATEGORY MODAL -->
        <div class="modal hide fade" tabindex="-1" id="modal-create">
            <div class="modal-dialog modal-dialog-centered">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        
                        <form action = "{{ route('expenses.category_store') }}" id="category-create-form" method="post"> 
                            @csrf
                            
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control form-control-border" id="category_name" name="category_name"  placeholder="Enter Category Name">
                            </div>

                            <div class="form-group">
                                <label for="category_description">Category Description</label>
                                <input type="text" class="form-control form-control-border" id="category_description" name="category_description"  placeholder="Enter Description">
                            </div>

                        </form>

                    </div>

                    <div class="modal-footer justify-content-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="createSave" >Save changes</button>
                    </div>
                </div> 

            </div>  
        </div>

         <!-- EDIT CATEGORY MODAL -->  
        <div class="modal hide fade" tabindex="-1" id="modal-edit">
            <div class="modal-dialog modal-dialog-centered">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category <span class="text-info" id="edit_customer_code"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        
                        <form action = "{{ route('expenses.category_update') }}" id="category-edit-form" method="post"> 
                            @csrf
                        
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control form-control-border" id="category_name" name="category_name"  placeholder="Enter Category Name">
                            </div>

                            <div class="form-group">
                                <label for="category_description">Category Description</label>
                                <input type="text" class="form-control form-control-border" id="category_description" name="category_description"  placeholder="Enter Category Description">
                            </div>

                            <input type="hidden" id="category_id"  name="category_id"  value="">
                            
                            
                    
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

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    
    <script>
        $('document').ready(function(){

               //validate EDIT FORM
              var editvalidator = $('#category-edit-form').validate({
                    rules: {
                        category_name: {
                            required: true,
                        },
                        customer_description: {
                            required: false,
                        },
                    },
                    messages: {
                        category_name: {
                            email: "Please enter category name"
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

                  //validate CREATE FORM
              var createvalidator = $('#category-create-form').validate({
                    rules: {
                        category_name: {
                            required: true,
                        },
                        category_description: {
                            required: false,
                        },
                    },
                    messages: {
                        customer_email: {
                            email: "Please enter category name"
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
            // Add button clicked
            ////////////////////////////////////////////
            $( 'body' ).on('click', "#add-button", function(e){
                e.preventDefault();
                
                //ajax call
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                $('#modal-create').modal('show');

            });

            ////////////////////////////////////////////
            // CREATE BUTTON CLICK TO SAVE
            ///////////////////////////////////////////
            $('#createSave').click(function(e){
                e.preventDefault();
                
                if(!createvalidator.form()){
                    return false;
                };
                
                //submit
                var formData = {
                    category_name: $("#category-create-form #category_name").val(),
                    category_description: $("#category-create-form #category_description").val(),
                };

                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('expenses.category_store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    if (data.status){
                        $('#modal-create').modal('hide'); 
                        $('#categoryTable').DataTable().ajax.reload();
                        $('#category-create-form').trigger('reset');
                        success_sound.time = 0;
                        success_sound.play();
                        toastr.success(data.message);
                        $('#categoryTable').DataTable().ajax.reload();
                    } else{
                        failed_sound.time = 0;
                        failed_sound.play();
                        toastr.error(data.message);
                    }
                });
                
            })
            
            



            /////////////////////////////////////////////
            // Edit Button Clicked
            ////////////////////////////////////////////
            $( '#categoryTable' ).on('click', ".edit-button", function(e){

                e.preventDefault(); 
                
                $('#category-edit-form').trigger('reset');

                var category_id = $(this).attr('id');
                $('#category-edit-form #category_id').val( category_id );
                
                //ajax call
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                //populate edit form field
                var url = "{{ route('expenses.category_edit', ':category_id') }}";
                url = url.replace(':category_id', category_id);
                $.get(url, function (edata) {
                   $('#category-edit-form #category_name').val(edata.category_name);
                   $('#category-edit-form #category_description').val(edata.category_description);
                   
                   $('#modal-edit').modal('show'); 
                }); //end get

                

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
                    id: $('#category_id').val(),
                    category_name: $("#category-edit-form #category_name").val(),
                    category_description: $("#category-edit-form #category_description").val(),
                };

                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('expenses.category_update') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    if (data.status){
                        $('#modal-edit').modal('hide'); 
                        $('#categoryTable').DataTable().ajax.reload();
                        toastr.success(data.message);
                        success_sound.time=0;
                        success_sound.play();
                    } else{
                        toastr.error(data.message);
                        failed_sound.time=0;
                        failed_sound.play();
                    }
                });
                
            })
            

          
            /////////////////////////////////////////////
            //Fetch all Category  Records for Datatable
            ////////////////////////////////////////////
            function load_datatable(){
                table =   $('#categoryTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, 
                    lengthChange: false,
                    autoWidth: false,
                    info: true,
                    ajax: {
                        url: "{{route('expenses.category_ajax')}}",
                       },
                    columns: [
                        { data: 'id' },
                        { data: 'category_name' },
                        { data: 'category_description' },
                        { data: 'category_status', 
                          render: function ( data, type, row, meta ) {
                            if (data == "active") return ('<span class="badge text-xs font-weight-normal badge-success">'+data+'</span>' ) ;
                            }
                        },
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
                    
                }); // end DataTable
            
            } // end load_datatable
            
            load_datatable();

        }); //end Document Ready

        var AdminLTEOptions = {
    /*https://adminlte.io/themes/AdminLTE/documentation/index.html*/
    sidebarExpandOnHover: true,
    navbarMenuHeight: "200px", //The height of the inner menu
    animationSpeed: 250,
  };



function delete_category(id){

    if (confirm("Do you want to delete the Expense Category?") == true) {
                $.ajax({
                    url: "/expenses/category_delete/"+id,
                    type: "get", //send it through get method
                    success: function(response) {
                        if (response.status == 1){
                            $('#categoryTable').DataTable().ajax.reload();
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