<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Page;
use App\Models\PageProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiscountController extends Controller
{
    public function makeDiscount(Request $request)
    {
        $pageProduct = $request->product;
        $details = PageProduct::find($pageProduct);
        $page = $details->page_id;
        $productPrice = $details->price;
        $owner = Page::find($page)->owner_id;
        if($owner != auth()->id()){
            return response()->json([
                'message'=>trans('response.403')
            ], Response::HTTP_BAD_REQUEST);
        }
        $discountValue = $request->discountValue;
        $discount = $productPrice*$discountValue/100;
        $newPrice = $productPrice - $discount;
        $duration = $request->duration;

        $newDiscount = Discount::create([
            'pageProduct_id'=>$pageProduct,
            'discount_value'=>$discountValue,
            'new_price'=>$newPrice,
            'duration'=>$duration,
        ]);
        return response()->json([
            'message'=>trans('response.discount_created'),
            'discount data'=>$newDiscount
        ], 201);
    }
}
