@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('New Customer') }}</h1>
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
                        <div class="card-body">


                            <form action = "{{ route('customers.create') }}" id="customers-edit-form" method="post"> 
                                @csrf
                               
                                  <div class="form-group">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"  placeholder="Enter Name">
                                  </div>
        
                                  <div class="form-group">
                                     <label for="customer_phone">Customer Phone</label>
                                     <input type="text" class="form-control" id="customer_phone" name="customer_phone"  placeholder="Enter Phone">
                                  </div>
        
                                  <div class="form-group">
                                    <label for="customer_email">Customer Email</label>
                                    <input type="email" class="form-control" id="customer_email" placeholder="Enter Email">
                                  </div>
        
                                  <input type="hidden" id="customer_id"  name="customer_id"  value="">
                                 
                                  
                          
                            </form>


                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection