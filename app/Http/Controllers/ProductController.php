<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

  public function index(): JsonResponse
  {
    $allsProducts = Product::with('category')->get();

    return new JsonResponse(
      $allsProducts,
      Response::HTTP_OK,
    );
  }

  public function show(Product $product): JsonResponse
  {
    $product = $product->load('category');

    return new JsonResponse([
      'status' => 'success',
      'product' => $product
    ],
    Response::HTTP_OK, 
    );
  }

  public function store(Request $request): JsonResponse 
  {
    $validator = Validator::make($request->all(), 
      [
        'detail' => 'required',
        'category_id' => 'required',
        'price' => 'required|regex:/^\d+(.(\d)+)*$/',
      ]
    );

    if($validator->fails()) {
      return new JsonResponse(
        $validator->errors(),
        Response::HTTP_BAD_REQUEST,
      );
    }

    $category = Category::find($request->category_id);
    if(!$category) {
      return new JsonResponse([
        'message' => '400 bad request',
        'error' => 'category not found',
      ],
      Response::HTTP_BAD_REQUEST, 
      );
    }

    $product = new Product();
    $product->detail = $request->detail;
    $product->category_id = $request->category_id;
    $product->price = $request->price;
    $product->save();

    return new JsonResponse(
      [
        'status' => 'created',
        'product' => $product,
      ],
      Response::HTTP_CREATED,
    );
  }

  public function update(Product $product, Request $request): JsonResponse 
  {
    if($request->has('category_id')) {
      $category = Category::find($request->category_id);

      if(!$category) {
        return new JsonResponse([
          'message' => '400 bad request',
          'error' => 'category not found',
        ],
        Response::HTTP_BAD_REQUEST
        );
      }
    }    

    $product->update($request->all());

    return new JsonResponse(
      [
        'status' => 'updated successfully',
        'product' => $product,
      ],
      Response::HTTP_OK,  
    );
  }

  public function destroy(Product $product): JsonResponse
  {
    return new JsonResponse([
      'status' => 'success',
      'product' => $product,
    ],
    Response::HTTP_OK, 
    );
  }

}
