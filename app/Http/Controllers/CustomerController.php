<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    ///////////////////////////////////////////////
    /////// for Invoice form Customer List
    ///////////////////////////////////////////////

    public function getcustomers(Request $request) 
    { 
        $search = $request->search;
        if($search == ''){
            $customers = Customer::orderby('customer_code','asc')->select('id', 'customer_code', 'customer_name')->get();
        }else{
            $customers = Customer::orderby('customer_code','asc')->select('id', 'customer_code', 'customer_name')->where('customer_name', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($customers as $customer){
            $response[] = array(
                "id"=>$customer->id,
                "text"=>$customer->customer_code . " | ". $customer->customer_name,
            );
        }
        return response()->json($response);
    }



     ///////////////////////////////////////////////
    /////// Amount due
    ///////////////////////////////////////////////

    public function amountdue(Request $request) 
    { 
        $id = $request->id;
        
        $dues = Customer::where('id', '=', $id)->get();
       // dd($due);
foreach ($dues as $due) {
    $amountdue = $due->customer_amount_due + $due->customer_invoice_due;
}
       
        return $amountdue;
    }

    public function ajax(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $show_account_receivable = $request->get('show_account_receivable');

        // Total records
        $totalRecords = Customer::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Customer::select('count(*) as allcount')
            ->where('customer_code', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_email', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_amount_due', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_invoice_due', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records
        $records = Customer::orderBy($columnName, $columnSortOrder)
            ->where('customer_code', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_email', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_amount_due', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_invoice_due', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {

            $id = $record->id;
            $customer_code = $record->customer_code;
            $customer_name = $record->customer_name;
            $customer_phone = $record->customer_phone;
            $customer_email = $record->customer_email;
            $customer_amount_due = $record->customer_amount_due;
            $customer_invoice_due = $record->customer_invoice_due;


            if ($show_account_receivable == 'checked') {
                if (($customer_amount_due + $customer_invoice_due) > 0) {
                    $data_arr[] = array(
                        "customer_code"         =>  $customer_code,
                        "customer_name"         =>  $customer_name,
                        "customer_phone"        =>  $customer_phone,
                        "customer_email"        =>  $customer_email,
                        "customer_amount_due"   =>  $customer_amount_due + $customer_invoice_due,
                        "action"                =>  "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'><a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a><a class='dropdown-item' href= '#'><i class='fa fa-money-bill mr-2'></i>View Payments</a><div class='dropdown-divider'></div><a class='dropdown-item' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a></div></div>",
                    );
                    $totalRecords--;
                    $totalRecordswithFilter--;
                }
            } else {
                $data_arr[] = array(
                    "customer_code"         =>  $customer_code,
                    "customer_name"         =>  $customer_name,
                    "customer_phone"        =>  $customer_phone,
                    "customer_email"        =>  $customer_email,
                    "customer_amount_due"   =>  $customer_amount_due + $customer_invoice_due,
                    "action"                =>  "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'><a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a><a class='dropdown-item' href= '#'><i class='fa fa-money-bill mr-2'></i>View Payments</a><div class='dropdown-divider'></div><a class='dropdown-item' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a></div></div>",
                );
            }
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    ///////////////////////////////////////////////////
    // CREATE CUSTOMER FORM
    //////////////////////////////////////////////////

    public function create()
    {
        //get the current customer code and return to form
        $new_customer_id = Customer::max('count_id') + 1;

        return response()->json([
            'count_id'      => $new_customer_id,
            'customer_code' =>  config('global.customer_code_prefix') . str_pad($new_customer_id, 4, "0", STR_PAD_LEFT),
        ]);
    }


    ///////////////////////////////////////////////////
    // CUSTOMER.STORE
    ///////////////////////////////////////////////////

    public function store(Request $request)
    {
        $count_id = $request->count_id;
        $customer_code = $request->customer_code;
        $customer_name = $request->customer_name;
        $customer_phone = $request->customer_phone;
        $customer_email = $request->customer_email;
        $customer_amount_due = $request->customer_amount_due;

        try {
           $data =  Customer::create([
                'count_id'           =>  $count_id,
                'customer_code'      =>  $customer_code,
                'customer_name'      =>  $customer_name,
                'customer_phone'     =>  $customer_phone,
                'customer_email'     =>  $customer_email,
                'customer_amount_due' =>  $customer_amount_due,
                'customer_invoice_due' =>  0.00,
                'created_by'         =>  auth()->user()->name
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => $e . ' - Sorry, couldn\'t create a new customer.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Whoops! Something unusual just happened.',
            ]);
        }

        return response()->json([
            'status' => true,
            'id'=> $data->id,
            'text' => $customer_code . ' | '. $customer_name,
            'message' => 'Customer has been created.',
        ]);
    }



    public function edit(Request $request)
    {
        $customer = Customer::find($request->id);

        $response = array(
            "customer_name" => $customer->customer_name,
            "customer_phone" => $customer->customer_phone,
            "customer_email" => $customer->customer_email,
            "customer_code" => $customer->customer_code,
            "customer_amount_due" => $customer->customer_amount_due,
        );

        return response()->json($response);
    }


    public function update(Request $request)
    {
        $id = $request->id;
        $customer_name = $request->customer_name;
        $customer_phone = $request->customer_phone;
        $customer_email = $request->customer_email;
        $customer_amount_due = $request->customer_amount_due;

        try {
            Customer::where('id', $id)
                ->update([
                    'customer_name'      =>  $customer_name,
                    'customer_phone'     =>  $customer_phone,
                    'customer_email'     =>  $customer_email,
                    'customer_amount_due' =>  $customer_amount_due,
                ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, couldn\'t update the database.' . $e,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Whoops! Something unusual just happened.',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer record has been updated.',
        ]);
    }
}
