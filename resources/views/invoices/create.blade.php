@extends('layouts.app')

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

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
                    <h1 class="m-0">{{ __('New Invoice') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->

    <div class="content">
        <form action="{{ route('invoices.store') }}" method="POST" novalidate id="invoice-form">
        @csrf

        <div class="container-fluid">
            <div class="row">

                <div class="col-md-3 pr-1">
                    <div class="card card-success card-outline">
                        
                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-12 text-bold ">
                                      <span style="font-weight: 400; font-size:0.8rem">Invoice Code: </span> 
                                      <span id="invoice-code-label">IN-0000</span>
                                      <input type="hidden" id="invoice-code" name="invoice_code" >
                                      <input type="hidden" id="count-id" name="count_id" >

                                    </div>

                                    <div class="card-tools">
                                       
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            

                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <div class="row">
                                        <div class="col-sm-10 pr-0">
                                            <select class="form-control form-control-border" id="customer-id" name="customer_id" >
                                                <option value='1' selected="selected">CU-0001 | Walk-In Customer</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <a class="btn btn-block btn-success btn-sm p-1" id="add-button" href="">
                                                <i class="fa fa-user-plus"></i> 
                                            </a>    
                                        </div>
                                    </div>

                                    <label style="margin-bottom: 0.1rem; font-weight: 500">
                                        Previous Due: <label class="customer_previous_due " style="font-size: 1.0rem;">₦ 0</label>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>Date:</label>
                                      <div class="input-group date" id="invoice-date" data-target-input="nearest">
                                        <input type="text" class="form-control " name="invoice_date" id="invoice-date" placeholder="Invoice Date" value="" readonly required style="background: #fff !important">
                                          <div class="input-group-append" data-target="#invoice-date" data-toggle="datetimepicker">
                                              <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                          </div>
                                      </div>
                                </div>


                                <div class="form-group ">
                                    <label for="payment-note">Invoice Note</label>
                                    <textarea class="form-control" rows="2" placeholder="Enter ..." id="invoice-note" name="invoice_note"></textarea>
                                </div>

                                
                              
                        </div>


                    </div>
                </div> <!-- left -->
                
                <div class="col-md-9">
                    <div class="card card-success card-outline">
                        
                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        Job Details
                                    </div>
                                </div>
                            </div>

                            

                        </div>
                        
                        <div class="card-body">

                            <div id="products-list">
                              
                            </div>

                            <div class="card-body table-responsive p-0 mb-3">

                                <table class="table table-hover text-nowrap ">
                                  
                                    <thead>
                                        <tr>
                                        <th width="15%">Type</th>
                                        <th width="15%">Size</th>
                                        <th width="15%">Unit Price</th>
                                        <th width="15%">Amount</th>
                                        <th width="15%">Quantity</th>
                                        <th width="15%">Total Amount</th>
                                        <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody id="invoice-items" >
                                        
                                    </tbody>

                                   
                                </table>
                              </div>



                              <div class="row" id="summary">
                               
                                    <div class="order-1 order-sm-2 p-2 col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                               <div class="form-group">
                                                   
                                                  <table class="col-md-9">
                                                     <tbody>
                                                        <tr>
                                                            <th class="text-right" >Subtotal</th>
                                                            <th class="text-right" >
                                                            <h5><b id="subtotal_txt">0.00</b></h5>
                                                            <input type="hidden" name="subtotal" id="subtotal" value=0.00> 
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-right">Discount</th>
                                                            <th class="text-right">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control form-control-border text-right text-red text-bold p-0 numonly" id="discount" placeholder="" value="0.00" onchange="calculatetotal()" name="discount" style="font-size: 1.2rem">
                                                            </div>
                                                            </th>
                                                        </tr>
                                                        <tr style="">
                                                            <th class="text-right">Round Off</th>
                                                            <th class="text-right">
                                                            <h5><b id="roundoff_txt">0.00</b></h5>
                                                            <input type="hidden" name="roundoff" id="roundoff" value=0.00>
                                                            </th>
                                                        </tr>
                                                        <tr style=" border-bottom: 2px solid #dee2e6;">
                                                            <th class="text-right">Grand Total</th>
                                                            <th class="text-right" >
                                                            <h5><b id="grandtotal_txt">0.00</b></h5>
                                                            <input type="hidden" name="grandtotal" id="grandtotal" value=0.00>
                                                            </th>
                                                        </tr>

                                                        <tr style=" border-bottom: 2px solid #dee2e6;">
                                                            <th colspan="2" class="text-right">
                                                                <span id="inwords" style="font-size: 0.8rem; font-weight:500"></span>
                                                            </th>
                                                        </tr>
                                                        
                                                     </tbody>
                                                </table>
                                               </div>
                                            </div>
                                         </div>
                                    </div>


                                    <div class="order-2 order-sm-1 p-2 col-md-6">
                                        <div class="row">
                                            <h5 class="mb-2 text-md">Payment Details</h5>
                                        </div>

                                        <div class="row bg-gray-light" style="padding: 4px">
                                           
                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    <label for="customer_name">Payment Amount</label>
                                                    <input type="text" class="form-control form-control-border text-sm numonly" id="amount_paid" name="amount_paid" placeholder="Enter Amount Paid" value="0.00">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    <label for="customer_name">Payment Type</label>
                                                    <select class="form-control form-control-border text-sm" id="payment-type" name="payment_type" placeholder="Select Payment Type">
                                                        <option value="cash">Cash</option>
                                                        <option value="pos">POS</option>
                                                        <option value="bank">Bank</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label for="payment-note">Note</label>
                                                    <textarea class="form-control form-control-border text-sm" rows="2" placeholder="Enter ..." id="payment-note" name="payment_note"></textarea>
                                                </div>
                                            </div>

                                            <input type="hidden" id="amount_due" name="amount_due" >

                                         </div>
                                    </div>
                                   
                            </div>
                               
                            </div>



                            <div class="col-sm-12">
                                <div class="p-4 text-center">
                                    
                                    <div type="button" class="btn btn-primary" id="invoice_save"> Save </div>
                                    <a class="btn btn-default"  href="{{ url()->previous() }}">Cancel</a>
                                    
                                </div>
                            </div>


                        </div> <!-- END CARD BODY --> 


                    </div>
                </div> <!-- right -->


            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
        </form>
    </div>
    <!-- /.content -->

@endsection



{{-- /////////////////////////////////////////////////////////////////////////////////////   --}}

@section('modals')
        <!-- ADD CUSTOMER MODAL -->
        <div class="modal hide fade" tabindex="-1" id="modal-create">
            <div class="modal-dialog modal-dialog-centered">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Customer <span id="new_customer_code" class="text-info"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        
                        <form action = "{{ route('customers.store') }}" id="customers-create-form" method="post"> 
                            @csrf
                            
                            <input type="hidden" id="count_id"  name="count_id"  value="">
                            <input type="hidden" id="customer_code"  name="customer_code"  value="">

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
                                <input type="email" class="form-control form-control-border" id="customer_email" placeholder="Enter Email">
                            </div>

                            <div class="form-group">
                                <label for="customer_amount_due">Amount Due</label>
                                <input type="customer_amount_due" class="form-control form-control-border" id="customer_amount_due" placeholder="Enter Amount (eg 0.00)" value="0.00">
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


        
@endsection


@section('scripts')

     
    <!-- date-range-picker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    
    <script>
        $('document').ready(function(){

                get_amount_due();

                // Get current Invoice Code and COunt Id
                $.get('{{route('invoices.newcode')}}', function (idata) {
                    $('#invoice-code-label').html(idata.invoice_code);
                    $('#invoice-code').val(idata.invoice_code);

                    $('#count-id').val(idata.count_id);

                });

              //ajax call
             $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
             });
           
            var count=0;
            
            $('#amount_paid').on('change', function(e){
                calculatetotal();
            });



//////////////////////////////////////////////////////////////////
///////////  SAVE INVOICE AJAX
/////////////////////////////////////////////////////////////////

            $('#invoice_save').on('click',function(e){
                e.preventDefault();
                //$('#invoice-form').submit();
                
                

                // check if any item has been added to invoice
                var count = $('#invoice-items tr').length;
                console.log('COUNT: ' + count);
                if(count < 1){
                    toastr.info("Please, add items to the invoice before submitting.");
                    return false;
                }

                if ($('#amount_paid').val() == "" ) $('#amount_paid').val(0.00); 

                //check that Walk-In Customer makes full payment
                if ( $('#customer-id').val() == 1 && $('#amount_paid').val() < grandtotal ){
                        toastr.error("Walk-In Customer must make full payment.");
                        return false;
                } else {

                    $.ajax({
                        type:"POST",
                        url: "{{ route('invoices.store') }}",
                        data:$("#invoice-form").serialize(), //only input
                        success: function(response){
                            if (response.status != 0){
                                toastr.success(response.message);
                                setTimeout(() => {
                                    window.location.href = '/invoices/view/' + response.status;
                                }, 1000);
                            }  else {
                                toastr.error(response.message);
                            }

                        }
                    });

                }

            });

            
            /////////////////////////////////////////////////////////////
            // Get Products list and create buttons
            ////////////////////////////////////////////////////////////

            $.get('{{route('products.getproducts')}}', function (pdata) {

                $(jQuery.parseJSON(JSON.stringify(pdata))).each(function(){
                    prod = `<a class="btn btn-app add-product" data-id="${this.product_id}" data-price="${this.product_price}" data-name="${this.product_name}">
                            <i class="fas fa-barcode"></i> ${this.product_name}
                        </a>`;

                    $('#products-list ').append(prod);
                    
                });
               
            }); //end get


            /////////////////////////////////////////////////////////////
            //  ADD JOB TO QUEUE ON CLICK
            /////////////////////////////////////////////////////////////
            $('#products-list').on('click', '.add-product', function () {

                success_sound.currentTime = 0;
                success_sound.play();
                
                default_w = 1; 
                default_h = 1;
                
                count++;

                var product_id = $(this).attr('data-id');
                var product_name = $(this).attr('data-name');
                var product_price = $(this).attr('data-price');

                if (product_name =='SAV'){
                    default_w = 5;
                }
                    
                var product_item = `
                <tr id="item-${count}" data-count="${count}">
                    <td>
                        <label id="item-type">${product_name}</label> <input type="hidden" id="item-id-${count}" value="${product_id}" name="item_id[]" > <input type="hidden" id="item-name-${count}" value="${product_name}" name="item_name[]" >
                    </td>
                    <td> 
                        <div class="input-group input-group-sm ">
                            <input type="text" name="width[]" class="form-control form-control-border numonly wh" id="width-${count}" onChange="calculateitem(${count})" value = ${default_w}  >
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="">X</span>
                            </div>
                             <input type="text" name="height[]" class="form-control form-control-border numonly wh"  id="height-${count}" onChange="calculateitem(${count})" value=${default_h}  >
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" name="unit_price[]" class="form-control form-control-border unitprice" id="unit-price-${count}"  placeholder="Price/sqft" value="${product_price}" onChange="calculateitem(${count})"  class="numonly" data-default="${product_price}" >
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" name="amount[]" class="form-control form-control-border text-right " id="amount-${count}"  placeholder="" value="${product_price}"  onChange="calculateitem(${count})" readonly  >
                        </div>
                    
                    </td>
                    <td>
                        <div class="input-group input-group-sm"  >
                            <input type="number" name="quantity[] min="1" class="form-control form-control-border text-center numonly wh" id="quantity-${count}"  value="1"  required style="background: #fff !important" onChange="calculateitem(${count})"  ">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" name="total_amount[]" class="form-control form-control-border text-right itemtotal"  id="total-amount-${count}"  placeholder="Enter Name" value="${product_price}" readonly  >
                        </div>
                    </td>
                    <td><a class="btn btn-danger btn-sm" id="remove-item" onclick="removeitem(${count})"><i class="fa fa-minus"></i></a></td>
                </tr>`;

                $('#invoice-items').append(product_item);
                
                
                calculateitem(count);
                
            });


            /////////////////////////////////////////////
            // GET AMOUNT DUE ON CUSTOMER CHANGE
            /////////////////////////////////////////////
            $('#customer-id').on('change', function() {
                get_amount_due();
            });


            /////////////////////////////////////////////
            // NEW  CUSTOMER button clicked
            ////////////////////////////////////////////
            $( 'body' ).on('click', "#add-button", function(e){
                e.preventDefault();
                
                 $.get('{{route('customers.create')}}', function (cdata) {
                   $('#customers-create-form #count_id').val(cdata.count_id);
                   $('#customers-create-form #customer_code').val(cdata.customer_code);
                   $(' #new_customer_code').html(cdata.customer_code);
                   
                }); //end get

                $('#modal-create').modal('show');

            });

            ////////////////////////////////////////////
            // CREATE CUSTOMER BUTTON CLICK TO SAVE
            ///////////////////////////////////////////
            $('#createSave').click(function(e){
                e.preventDefault();
                
                if(!createvalidator.form()){
                    return false;
                };
                
                //submit
                var formData = {
                    count_id: $('#count_id').val(),
                    customer_code: $("#customers-create-form #customer_code").val(),
                    customer_name: $("#customers-create-form #customer_name").val(),
                    customer_phone: $("#customers-create-form #customer_phone").val(),
                    customer_email: $("#customers-create-form #customer_email").val(),
                    customer_amount_due: $("#customers-create-form #customer_amount_due").val(),
                };

                $.ajax({
                    type: "POST",
                    url: '{{ route('customers.store') }}',
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    if (data.status){
                        $('#modal-create').modal('hide'); 
                        $('#customers-create-form').trigger('reset');

                        var option = new Option(data.text, data.id, true, true);
                        $('#customer-id').append(option).trigger('change');

                        // manually trigger the `select2:select` event
                        $('#customer-id').trigger({
                            type: 'select2:select',
                            params: {
                                data: data.id
                            }
                        });

                        toastr.success(data.message);
                    } else{
                        toastr.error(data.message);
                    }
                });
                
            });

            /////////////////////////////////////////////
            // VALIDATE NEW CUSTOMER FORM
            ////////////////////////////////////////////
            var createvalidator = $('#customers-create-form').validate({
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
                            email: "Please enter a valid email address."
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

            
            //Date picker
            $('#invoice-date').datepicker({
                format: "dd-mm-yyyy",
                toggleActive: false,
                autoclose: true,
                todayHighlight: true               
            });

            $('#invoice-date').datepicker("setDate", new Date());


         
            $('#customer-id').select2({
                // minimumInputLength: 1,
                ajax: { 
                headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                url: "{{route('customers.getcustomers')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
                }
            });


            //Positive Decimal
               $("#customer_amount_due, .numonly").inputFilter(function(value) {
                 return /^\d*[.]?\d{0,2}$/.test(value); 
            });

            $('div').on('change', '.wh', function(e){
                var item_count = $(this).closest('tr').attr('data-count');
                if ( $(this).val() == 0 || $(this).val() == "" ) {
                    $(this).val(1);
                    calculateitem(item_count);
                    return false;
                } 
            });

            $('div').on('change', '.unitprice', function(e){
                var item_count = $(this).closest('tr').attr('data-count');
                if ( $(this).val() == 0 || $(this).val() == "" ) {
                    $(this).val($(this).attr('data-default'));
                    calculateitem(item_count);
                    return false;
                } 
            });


            $('div').on('input keydown keyup mousedown mouseup select contextmenu drop', '.numonly', function(e){
                  if (isNaN($(this).val())){
                        $(this).val(1);
                        return false;
                 }
            });

            $('#discount').on('change  select contextmenu drop', function(e){
                  if (isNaN($(this).val())){
                        $(this).val(0.00);
                        calculatetotal();
                        return false;
                 }
            });


        }); //end Document Ready

        var AdminLTEOptions = {
    /*https://adminlte.io/themes/AdminLTE/documentation/index.html*/
    sidebarExpandOnHover: true,
    navbarMenuHeight: "200px", //The height of the inner menu
    animationSpeed: 250,

  }; // END DOCUMENT READY


    ////////////////////////////////////////////////////
    // Remove job item from queue
    ///////////////////////////////////////////////////
    function removeitem(id){
        $('#item-'+id).remove();
        calculatetotal();
        failed_sound.currentTime = 0;
        failed_sound.play();
    }


    function calculateitem(id){
         var area = parseFloat($("#width-"+id).val() * $("#height-"+id).val());
         var amount = area * parseFloat($("#unit-price-"+id).val());
         var totalamount = amount * parseInt($("#quantity-"+id).val());

          //Enforce Minimum Subtotal if only at least 1 product added
         if (totalamount < {{ config('global.minimum_itemtotal') }} ){
            totalamount = {{ config('global.minimum_itemtotal') }};
         }

         amount = (Math.round(amount * 100) / 100).toFixed(2);
         totalamount = (Math.round(totalamount * 100) / 100).toFixed(2);

         $('#amount-'+id).val(amount);
         $('#total-amount-'+id).val(totalamount);

         calculatetotal();
    }
    
    function calculatetotal(){
            var discount = parseFloat($('#discount').val());
            $('#discount').val( discount.toLocaleString("en-US", {minimumFractionDigits:2}) )
        
            var subtotal = 0;
            var roundoff = 0;
            var amount_due = 0;

            // calculate subtotal
            $("table").find("input.itemtotal").each(function(){
            	subtotal += parseFloat($(this).val());
            });

            subtotal = (Math.round(subtotal * 100) / 100).toFixed(2);
            rounded_subtotal = Math.round(subtotal / 50) * 50;
            roundoff = rounded_subtotal - subtotal;
            roundoff = (Math.round(roundoff * 100) / 100).toFixed(2);
            grandtotal = parseFloat(subtotal) - parseFloat(discount) + parseFloat(roundoff);
            amount_due = grandtotal - $('#amount_paid').val();
            console.log(amount_due);
            
            
            $('#subtotal').val(subtotal);
            $('#subtotal_txt').html( subtotal.toLocaleString("en-US", {minimumFractionDigits:2})); 
            
            $('#roundoff').val(roundoff);
            $('#roundoff_txt').html( roundoff.toLocaleString("en-US", {minimumFractionDigits:2}));  

            $('#grandtotal').val(grandtotal);
            $('#grandtotal_txt').html('₦ '+ grandtotal.toLocaleString("en-US", {minimumFractionDigits:2}));  
            
            $('#amount_due').val(amount_due);
            $('#inwords').html(NumToWordsDec(grandtotal));
            
    }


    function get_amount_due(){
        var customer_id = $('#customer-id').val();

        $.ajax({
            url: "{{ route('customers.amountdue') }}",
            type: "get", //send it through get method
            data: { 
                id: customer_id, 
            },
            success: function(response) {
                if (response == 0){
                    $('.customer_previous_due').removeClass('text-red');
                } else {
                    $('.customer_previous_due').addClass('text-red');
                }
                formated = parseFloat(response).toLocaleString(undefined, {minimumFractionDigits:0});
                $('.customer_previous_due').html('₦ ' + formated);
            },
            error: function(xhr) {
                //Do Something to handle error
            }
        });
    }


        
    </script>
@endsection




