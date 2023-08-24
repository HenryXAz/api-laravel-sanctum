<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  public function index(): JsonResponse
  {
    $categories = Category::all();

    return new JsonResponse(
      $categories,
      Response::HTTP_OK,
    );
  }

  public function show(Category $category): JsonResponse
  {
    return new JsonResponse(
      $category,
      Response::HTTP_OK,
    );
  }

  public function store(Request $request): JsonResponse
  {
    $validator = Validator::make(
      $request->all(),
      [
        'detail' => 'required',
      ]
    );

    if ($validator->fails()) {
      return new JsonResponse(
        $validator->errors(),
        Response::HTTP_NOT_FOUND,
      );
    }

    $category = new Category();
    $category->detail = $request->detail;
    $category->save();

    return new JsonResponse(
      [
        'status' => 'created',
        $category,
      ],
      Response::HTTP_CREATED,
    );
  }

  public function update(Category $category, Request $request): JsonResponse
  {
    $validator = Validator::make(
      $request->all(),
      [
        'detail' => 'min:1|regex:/^\w+(\s\w+)*$/',
      ]
    );

    if ($validator->fails()) {
      return new JsonResponse(
        [
          'status' => 'not found',
          'errros' => $validator->errors(),
        ],
        Response::HTTP_NOT_FOUND,
      );
    }

    $category->update($request->all());

    return new JsonResponse(
      [
        'status' => 'udpated',
        'category' => $category,
      ],
      Response::HTTP_OK,
    ); 
  }

  public function destroy(Category $category): JsonResponse
  {
    $category->delete();

    return new JsonResponse(
      [
        "status" => 'success',
        "message" => "category was remove successfully",
        "category" => $category,
      ],
      Response::HTTP_OK,
    );
  }
}
