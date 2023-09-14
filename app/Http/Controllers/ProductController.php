<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProdctRequest;
use App\Http\Requests\UpdateProdctRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $category = $request->category;
        $photo_url = '/storage/' . $request->file('photo')->store('products', 'public');
        $product = Product::create([
            'name'=>$name,
            'category_id'=>$category,
            'photo'=>$photo_url,
            'description'=>$description,
        ]);
        return response()->json([
            'message' => trans('response.product_201'),
            'product' => $product
        ], 201);
    }
}
