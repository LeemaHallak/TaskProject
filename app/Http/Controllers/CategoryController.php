<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $categoryName = $request->name;
        $newCategory = Category::create([
            'category_name'=>$categoryName,
        ]);
        return response()->json([
            'message'=>trans('response.category_success'),
            'category'=>$newCategory,
        ], 201);
    }
}
