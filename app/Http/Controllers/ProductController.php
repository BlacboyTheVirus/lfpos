<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getproducts(){
       
        $products = Product::where('status',1)->get();

        $response = array();
        foreach($products as $product){
            $response[] = array(
                "product_id"=>$product->id,
                "product_name"=>$product->product_name,
                "product_price"=>$product->product_price,
            );
        }
        return response()->json($response);
       
       
    }
}
