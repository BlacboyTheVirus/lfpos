@extends('layouts.app')

@section('styles')
 

  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

  <!-- Select 2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
 
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/bootstrap-datepicker.css') }}">
  
@endsection



@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> {{  ($expenses->id)? "Edit Expense" : "New Expense" }} </h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-8">
                    
                    <div class="card card-success card-outline">
                        
                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        {{  ($expenses->id)? "Edit Expense Detail" : "Enter Expense Detail" }}
                                            
                                    </div>
                                </div>
                            </div>
                        </div> <!-- END CARD HEADER  -->
                        
                        <div class="card-body">
                            <form action="{{route('expenses.store')}}" method="post" id="expenses_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">

                                        <div class="form-group">
                                            <label for="expenses_detail">Expense Date <label class="text-danger">*</label></label>
                                                <div class="input-group date" id="expenses_date" data-target-input="nearest">
                                                    <input type="text" class="form-control form-control-border " name="expenses_date" id="expenses_date" placeholder="Expense Date" value="" readonly required style="background: #fff !important">
                                                    <div class="input-group-append" data-target="#expenses_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                                    </div>
                                                </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Category <label class="text-danger">*</label></label>
                                            <select class="form-control form-control-border" id="category_id" name="category_id" >
                                                <option value = ''>--Select Category--</option>
                                                @foreach ($categories as $category )
                                                <option value='{{$category->id}}' @if ($expenses->category_id == $category->id) selected='selected' @endif >{{$category->category_name}}</option> 
                                                @endforeach
                                                

                                            </select>
                                        </div>


                                        <div class="form-group ">
                                             <label for="expenses_for">Expense For <label class="text-danger">*</label></label>
                                            <input class="form-control form-control-border" type="text" name="expenses_for" id="expenses_for" value="{{$expenses->expenses_for}}">
                                        </div>

                                        <div class="form-group ">
                                            <label for="expenses_amount">Amount <label class="text-danger">*</label></label>
                                           <input class="form-control form-control-border" type="text" name="expenses_amount" id="expenses_amount" value="{{$expenses->expenses_amount}}">
                                        </div>



                                    </div> <!-- col 6 -->


                                    <div class="col-md-5 offset-md-2">
                                        
                                        <div class="form-group">
                                            <label for="expenses_note">Expense Note</label>
                                            <textarea class="form-control form-control-border" rows="2" placeholder="Enter ..." id="expenses_note" name="expenses_note">{{$expenses->expenses_note}}</textarea>
                                        </div>

                                        <div class="form-group ">
                                            <label for="expenses_reference">Expense Reference</label>
                                            <input class="form-control form-control-border" type="text" name="expenses_reference" id="expenses_reference" value="{{$expenses->expenses_reference}}">
                                        </div>
                                        
                                        @if($expenses->id) 
                                            <input type="hidden" id = "expenses_id" name="expenses_id" value = "{{$expenses->id}}">
                                        @endif

                                    </div> <!-- col 6 -->

                                    <div class="col-sm-12">
                                        <div class="p-4 text-center">
                                            
                                            <div type="button" class="btn btn-primary" id="expenses_save"> Save </div>
                                            <a class="btn btn-default"  href="{{ url()->previous() }}">Cancel</a>
                                            
                                        </div>
                                    </div>

                                </div>

                                


                            </form>
                        </div> <!-- END CARD BODY --> 
                    
                    </div> <!-- end card -->
                
                </div> <!-- right 9 -->


            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.content -->
@endsection





{{-- /////////////////////////////////////////////////////////////////////////////////////   --}}


@section('scripts')

     
    <!-- date-range-picker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    
    <script>
        $('document').ready(function(){

            //ajax call
             $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
             });
           
                       
            /////////////////////////////////////////////
            // VALIDATE EXPENSES FORM
            ////////////////////////////////////////////
            var createvalidator = $('#expenses_form').validate({
                    rules: {
                        expenses_date: {
                            required: true,
                        },
                        category_id: {
                            required: true,
                        },
                        expenses_for: {
                            required:true,
                        },
                        expenses_amount: {
                            required:true,
                        },

                    },
                    messages: {
                        category_id: {
                            required: "Please select a category."
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


//////////////////////////////////////////////////////////////////
///////////  SAVE EXPENSE AJAX
/////////////////////////////////////////////////////////////////

            $('#expenses_save').on('click',function(e){
                e.preventDefault();
                // $('#expenses_form').submit();
                // exit;

                if(!createvalidator.form()){
                    return false;
                };

                var ajaxurl = "";
                if ($('#expenses_id').length > 0){
                    ajaxurl = "{{route('expenses.update')}}";
                } else {
                    ajaxurl = "{{route('expenses.store')}}";
                }

                

                    $.ajax({
                        type:"POST",
                        url: ajaxurl,
                        data:$("#expenses_form").serialize(), //only input
                        success: function(response){
                            if (response.status != 0){
                                toastr.success(response.message);
                                setTimeout(() => {
                                    window.location.href = "{{route('expenses.index')}}";
                                }, 1000);
                            }  else {
                                toastr.error(response.message);
                            }

                        }
                    });

              

            });

         
            

            //Date picker
            $('#expenses_date').datepicker({
                format: "dd-mm-yyyy",
                toggleActive: false,
                autoclose: true,
                todayHighlight: true               
            });

            $('#expenses_date').datepicker("setDate", new Date());

            $('#category_id').select2();


            //Positive Decimal
               $("#expenses_amount, .numonly").inputFilter(function(value) {
                 return /^\d*[.]?\d{0,2}$/.test(value); 
            });



        }); //end Document Ready

        var AdminLTEOptions = {
    /*https://adminlte.io/themes/AdminLTE/documentation/index.html*/
    sidebarExpandOnHover: true,
    navbarMenuHeight: "200px", //The height of the inner menu
    animationSpeed: 250,

  }; // END DOCUMENT READY


    
        
    </script>
@endsection






