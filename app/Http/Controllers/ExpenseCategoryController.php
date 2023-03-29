<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Exception;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        $categories = ExpenseCategory::all();
        return view('expenses.category', compact('categories'));
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

        // Total records
        $totalRecords = ExpenseCategory::select('count(*) as allcount')->count();

        $totalRecordswithFilter = ExpenseCategory::select('count(*) as allcount')
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orWhere('category_name', 'like', '%' . $searchValue . '%')
            ->orWhere('category_description', 'like', '%' . $searchValue . '%')
            ->orWhere('category_status', 'like', '%' . $searchValue . '%')
            ->count();

        // $users = User::where('active','1')->where(function($query) {
                //     $query->where('email','jdoe@example.com')
                //                 ->orWhere('email','johndoe@example.com');
        // })->get();

        // Fetch records
        $records = ExpenseCategory::orderBy($columnName, $columnSortOrder)
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orwhere('category_name', 'like', '%' . $searchValue . '%')
            ->orWhere('category_description', 'like', '%' . $searchValue . '%')
            ->orWhere('category_status', 'like', '%' . $searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $category_name = $record->category_name;
            $category_description = $record->category_description;
            $category_status = $record->category_status;
           

            $data_arr[] = array(
                "id"           =>  $id,
                "category_name"         =>  $category_name,
                "category_description"  =>  $category_description,
                "category_status"       =>  $category_status,
                "action"                =>  ($id <> 1) ? "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'><a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                <a class='dropdown-item' onclick='delete_category(".$id.")'  href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a></div></div>" : "",
            );
        }




            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            return response()->json($response);
    }



    public function store(Request $request){

        $category_name = $request->category_name;
        $category_description = $request->category_description;
        $category_status = "active"; 

        if (ExpenseCategory::where('category_name', $category_name)->count()){
            return (['status'=> 0, 'message' => 'Deja vu! Category name exists!']);
        }
        
       
        DB::beginTransaction();
        try {
            //SAVE CATEGORY DETAILS
            $category = new ExpenseCategory();
            $category->category_name = $category_name;
            $category->category_description = $category_description;
            $category->category_status = $category_status;

            $category->save();
           
            DB::commit();
            return (['status'=> 1, 'message' => 'Category has been successfully created.']);

        }  catch(Exception $ex){
            DB::rollBack();
            //throw $ex;
            return (['status'=> 0, 'message' => $ex.'Oh! Oh!. Something unusal just happened. Please try again.']);

        } //END DB TRANSACTIONS


    }



    public function edit (Request $request){
        $category = ExpenseCategory::find($request->id);

        $response = array(
            "category_name" => $category->category_name,
            "category_description" => $category->category_description,
        );

        return response()->json($response);
    }




    public function update(Request $request)
    {
        $id = $request->id;
        $category_name = $request->category_name;
        $category_description = $request->category_description;
       

        try {
            $edit = ExpenseCategory::where('id', $id)
                ->update([
                    'category_name'          =>  $category_name,
                    'category_description'   =>  $category_description,
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
            'message' => 'Expense Category record has been updated.',
        ]);
    }



    public function delete($id){
        $expenseCat = ExpenseCategory::findOrFail($id);
        	
        $expenses = Expense::where('category_id', $id);
        $expenses->update([
            'category_id' => 1
        ]);

        $expenseCat->delete();
        return (['status'=> 1, 'message' => 'Expense record '.$id.' has been successfully deleted.']);
    }




}
