<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  
  public function index()
  {
    $categories = Category::all();

    return response()->json(
      $categories,
      Response::HTTP_OK,
    );
  }

  public function show($id) 
  {
    $category = Category::find($id);

    if(!$category) {
      return response()->json(
        [
          'status' => 'not found',
        ],
        Response::HTTP_NOT_FOUND,
      );
    }

    return response()->json(
      $category,
      Response::HTTP_OK,
    );
  }

  public function store(Request $request) 
  {
    $validator = Validator::make($request->all(), 
      [
        'detail' => 'required',
      ]
    );

    if($validator->fails()) {
      return response()->json(
        $validator->errors(),
        Response::HTTP_NOT_FOUND,
      );
    }

    $category = new Category();
    $category->detail = $request->detail;
    $category->save();

    return response()->json(
      [
        'status' => 'created',
        $category,
      ],
      Response::HTTP_CREATED,
    );
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), 
      [
        'detail' => 'regex:/^\w+(\s\w+)*$/',
      ]
    );

    if($validator->fails()) {
      return response()->json(
        [
          'status' => 'not found',
        ],
        Response::HTTP_NOT_FOUND,
      );
    }

    $category = Category::find($id);

    if(!$category) {
      return response()->json(
        [
          'status' => 'not found',
        ],
        Response::HTTP_NOT_FOUND,
      );
    }

    $category->detail = $request->has('detail') ? $request->detail : $category->detail;
    $category->save();

    return response()->json(
      [
        'status' => 'updated successfully',
        $category,
      ],
      Response::HTTP_OK,
    );
  }

  public function destroy($id)
  { 
    $category = Category::find($id);

    if(!$category) {
      return response()->json(
        [
          'status' => 'not found',
        ],
        Response::HTTP_NOT_FOUND,
      );
    }

    return response()->json(
      [
        'status' => 'deleted successfully',
      ],
      Response::HTTP_OK,
    );
  }
}
