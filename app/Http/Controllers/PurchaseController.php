<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\PageProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseController extends Controller
{
    public function addPurchase(Request $request)
    {
        $product = $request->product;
        $productPrice = PageProduct::find($product)->price;
        $quantity = $request->quantity;
        $cost = $productPrice*$quantity;
        $date = $request->date;
        if($quantity <= 0){
            return response()->json([
                'message' => trans('response.quantity_error')
            ], Response::HTTP_BAD_REQUEST);
        }
        if(!$date){
            $date = Carbon::now();
        }
        $purchase = Purchase::create([
            'page_product_id'=>$product,
            'user_id'=>auth()->id(),
            'quantity'=>$quantity,
            'cost'=>$cost,
            'date'=>$date,
        ]);
        return response()->json([
            'message'=>trans('response.purchase_success'),
            'purchase data'=>$purchase,
        ], 201);
    }

    public function showPurchases()
    {
        $paymentsDetails = Purchase::where('user_id', auth()->id())->get();
        return !$paymentsDetails
            ? response()->json(['message' => trans('response.payment_error'),], Response::HTTP_NO_CONTENT)
            : response()->json([
                'message' => trans('response.payment_success'), 
                'data'=>$paymentsDetails],
                Response::HTTP_OK); 
    }
}
