<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

  public function index()
  {
    $allsProducts = Product::all();

    $products = array_map(function($product){    
    return [
      'id' => $product->id,
      'detail' => $product->detail,
      'price'  => $product->price,
      'category' => $product->category->detail,
    ];

    },$allsProducts->all());

    return response()->json(
      $products,
      Response::HTTP_OK,
    );
  }

  public function show($id)
  {
    $product = Product::find($id);


    if(!$product) {
      return response()->json(
        [
          'status' => 'not found',
        ],
        Response::HTTP_NOT_FOUND,
      );
    }

    return response()->json(
      [
        'detail' => $product->detail,
        'price' => $product->price,
        'category' => $product->category->detail,
      ],
      Response::HTTP_OK,
    );
  }

  public function store(Request $request) 
  {
    $validator = Validator::make($request->all(), 
      [
        'detail' => 'required',
        'category_id' => 'required',
        'price' => 'required|regex:/^\d+(.(\d)+)*$/',
      ]
    );

    if($validator->fails()) {
      return response()->json(
        $validator->errors(),
        Response::HTTP_BAD_REQUEST,
      );
    }

    $product = new Product();
    $product->detail = $request->detail;
    $product->category_id = $request->category_id;
    $product->price = $request->price;

    $product->save();


    return response()->json(
      [
        'status' => 'created',
      ],
      Response::HTTP_CREATED,
    );
  }

  public function update(Request $request, $id) 
  {
    $product = Product::find($id);

    $validator = Validator::make($request->all(), 
      [
        'detail' => 'regex:/^\w+(\s\w+)*$/',
        'category' => 'regex:/^\d+$/',
        'price' => 'regex:/^\d+(.(\d+))*$/',
        'itsOnSale' => 'boolean',  
      ]
    );    

    if($validator->fails()) {
      return response()->json(
        $validator->errors(),
        Response::HTTP_BAD_REQUEST,
      );
    }

    $product->detail = $request->has('detail') ? $request->detail : $product->detail;
    $product->category_id = $request->has('category_id') ?  $request->category_id : $product->category_id;
    $product->price = $request->has('price') ? $request->price : $product->price;

    $product->save();


    return response()->json(
      [
        'status' => 'updated successfully',
        $product,
      ],
      Response::HTTP_OK,  
    );
  }

  public function destroy($id)
  {
    $product = Product::find($id);

    if($product) {
      $product->delete();

      return response()->json(
        [
          'status' => 'deleted successfully',
        ],
        Response::HTTP_OK,
      );
    }

    return response()->json(
      [
        'status' => 'not found',
      ],
      Response::HTTP_NOT_FOUND,
    );
  }

}
