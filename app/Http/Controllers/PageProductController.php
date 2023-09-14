<?php

namespace App\Http\Controllers;

use App\Models\PageProduct;
use App\Http\Requests\StorePageProductRequest;
use App\Http\Requests\UpdatePageProductRequest;
use App\Models\Page;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageProductController extends Controller
{
    
    public function showpageProducts($pageId)
    {
        $products = PageProduct::where('page_id', $pageId)->get();
        return !$products
            ? response()->json(['message' => trans('response.product_404')], Response::HTTP_NO_CONTENT)
            : response()->json(['products' => $products], Response::HTTP_OK); 
    }

    public function addPageProduct(Request $request)
    {
        $page_id = $request->page;
        $page = Page::find($page_id);
        $product_id = $request->product;
        $quantity = $request->quantity;
        $price = $request->price;
        if ($page->page_owner != auth()->id() && $page_id != auth()->user()->pagesUsers->first()->page_id) {
            return response()->json([
                'message' => trans('response.403')], 
                Response::HTTP_BAD_REQUEST);
        }
        $pageProduct = PageProduct::create([
            'page_id'=>$page_id,
            'product_id'=>$product_id,
            'quantity'=>$quantity,
            'price'=>$price,
        ]);        
        return response()->json([
            'message' => trans('response.product_201'),
            'product' => $pageProduct
        ], 201);
    }

    public function productsQuantity($pageId)
    {
        $page = Page::find($pageId);
        if (!$page) {
            return response()->json([
                'message' => trans('response.page_404')],
                Response::HTTP_NOT_FOUND);
        }
        if ($page->page_owner != auth()->id()) {
            return response()->json([
                'message' => trans('response.403')], 
                Response::HTTP_BAD_REQUEST);
        }
        $productQuantity = $page->products()
            ->groupBy('product_id')
            ->selectRaw('sum(quantity) as sum, product_id')
            ->pluck('sum', 'product_id');
        $allQuantity = $page->products()->sum('quantity');
    
        return response()->json([
            'products_quantities' => $productQuantity,
            'total_quantities' => $allQuantity,
        ], Response::HTTP_OK);
    }
    

    public function purchasesQuantity($pageId)
    {
        $page = Page::find($pageId);
        $owner = $page->page_owner;
        if($owner == auth()->id()){
            $quantity = PageProduct::where('page_id', $pageId)->withAggregate('purchases', 'quantity')->sum('quantity');
            return !$quantity
                ? response()->json(['message' => trans('response.no_quantities')], Response::HTTP_NO_CONTENT)
                : response()->json([
                    'message'=>trans('response.total_bought'),
                    'data'=>$quantity],
                    Response::HTTP_OK);
        }
        else{
            return response()->json([
                'message'=>trans('response.403')
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
