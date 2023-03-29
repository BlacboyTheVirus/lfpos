<?php

namespace App\Http\Controllers;


use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\throwException;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
       
        return view('expenses.index');
    }

    public function create(){
        $categories = ExpenseCategory::all(); 
        $expenses = new Expense();
        return view('expenses.create', compact(['categories', 'expenses']));
    }


    public function store(Request $request){
       
        $expenses_date = $request->expenses_date;
        $expenses_category = $request->category_id;
        $expenses_for = $request->expenses_for;
        $expenses_amount = $request->expenses_amount;
        $expenses_note = $request->expenses_note;
        $expenses_reference = $request->expenses_reference;
        $expenses_created_by = Auth::user()->name;

        DB::beginTransaction();
        try {
            $expenses = new Expense();
            $expenses->expenses_date = Carbon::parse($expenses_date)->format('Y-m-d');
            $expenses->category_id = $expenses_category;
            $expenses->expenses_for = $expenses_for;
            $expenses->expenses_amount = $expenses_amount;
            $expenses->expenses_note = $expenses_note;
            $expenses->expenses_reference = $expenses_reference;
            $expenses->expenses_created_by = $expenses_created_by;

            $result = $expenses->save();
            
            if (!$result) throw new Exception("Sorry! Something went wrong.");

            DB::commit();
            return (['status'=> 1, 'message' => 'Expense record has been succesfully created.']);

        }  catch(Exception $ex){
            
            DB::rollBack();
            return (['status'=> 0, 'message' => 'Oh! Oh!. Something unusal just happened. Please try again.']);
        } 
       
        //return (['status'=> 0, 'message' => 'Oh! Oh!. Something unusal just happened. Please try again.']);
    }




    //////////////////////////////// AJAX ////////////////////////

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

        // Total records
        $totalRecords = DB::table('expenses')->select('count(*) as allcount')->count();

        $totalRecordswithFilter = DB::table('expenses')->select('count(*) as allcount')
            ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
            ->where('expenses_date', 'like', '%' . $searchValue . '%')
            ->orWhere('category_name', 'like', '%' . $searchValue . '%')
            ->orWhere('expenses_for', 'like', '%' . $searchValue . '%')
            ->orWhere('expenses_amount', 'like', '%' . $searchValue . '%')
            ->orWhere('expenses_reference', 'like', '%' . $searchValue . '%')
            ->orWhere('expenses_note', 'like', '%' . $searchValue . '%')
            ->orWhere('expenses_created_by', 'like', '%' . $searchValue . '%')
            ->count();


        // Fetch records
      
        $records = DB::table('expenses')->orderBy($columnName, $columnSortOrder)
        ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
        ->where('expenses_date', 'like', '%' . $searchValue . '%')
        ->orWhere('category_name', 'like', '%' . $searchValue . '%')
        ->orWhere('expenses_for', 'like', '%' . $searchValue . '%')
        ->orWhere('expenses_amount', 'like', '%' . $searchValue . '%')
        ->orWhere('expenses_reference', 'like', '%' . $searchValue . '%')
        ->orWhere('expenses_note', 'like', '%' . $searchValue . '%')
        ->orWhere('expenses_created_by', 'like', '%' . $searchValue . '%')
            ->select('expenses.*', 'expense_categories.category_name')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {

            $id = $record->id;
            $expenses_date = Carbon::parse($record->expenses_date)->format('d-m-Y');
            $expenses_category = $record->category_name;
            $expenses_for = $record->expenses_for;
            $expenses_amount = $record->expenses_amount;
            $expenses_reference = $record->expenses_reference;
            $expenses_note = $record->expenses_note;
            $expenses_created_by = $record->expenses_created_by;

        
          
                $data_arr[] = array(
                        "expenses_date"             =>  $expenses_date,
                        "category_name"          =>  $expenses_category,
                        "expenses_for"              =>  $expenses_for,   
                        "expenses_amount"           =>  $expenses_amount,
                        "expenses_note"             =>  $expenses_note,
                        "expenses_reference"        =>  $expenses_reference,
                        "expenses_created_by"       =>  $expenses_created_by,
                        "action"                    =>  "<div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button>
                        <div class='dropdown-menu' role='menu'>
                        <a class='dropdown-item' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                        <div class='dropdown-divider'></div>
                        <a class='dropdown-item' onclick= 'delete_expenses(".$id.")' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>
                        </div>
                        </div>" ,
                );
          


        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    } //end AJAX




    /////////////// EDIT ////////////////
    public function edit(Request $request){
        $expenses = Expense::findOrFail($request->id);
        $expenses->expenses_date = Carbon::parse($expenses->expenses_date)->format('d-m-Y');

        $categories = ExpenseCategory::all(); 
       
        return view('expenses.create', compact(['expenses', 'categories']));
    }
  

    public function update(Request $request){

        $expenses_date = Carbon::parse($request->expenses_date)->format('Y-m-d');;
        $expenses_category = $request->category_id;
        $expenses_for = $request->expenses_for;
        $expenses_amount = $request->expenses_amount;
        $expenses_note = $request->expenses_note;
        $expenses_reference = $request->expenses_reference;
        $expenses_id = $request->expenses_id;

        DB::beginTransaction();
        try {

            $expense = Expense::findOrFail($expenses_id);
            $expense->update([
                            'expenses_date'       => $expenses_date,
                            'category_id'       => $expenses_category,
                            'expenses_for'       => $expenses_for,
                            'expenses_amount'       => $expenses_amount,
                            'expenses_note'       => $expenses_note,
                            'expenses_reference'       => $expenses_reference,
            ]);

                    
            DB::commit();
            return (['status'=> 1, 'message' => 'Expense record has been successfully updated.']);

        }  catch(Exception $ex){
            DB::rollBack();
            return (['status'=> 0, 'message' => 'Oh! Oh!. Something unusal just happened. Please try again.']);

        } //END DB TRANSACTIONS
                   
    }


    public function delete($id){
        $expense = Expense::findOrFail($id);

        $expense->delete();

        return (['status'=> 1, 'message' => 'Expense record '.$id.' has been successfully deleted.']);
    }
    

}
