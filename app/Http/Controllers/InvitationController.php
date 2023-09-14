<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Earning;
use App\Models\PageProduct;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    public function invite(Request $request)
    {
        $sender_id = auth()->id();
        $reciever_id = $request->reciever;
        $pageProduct_id = $request->product;
        $invitation = Invitation::create([
            'sender_id'=>$sender_id,
            'reciever_id'=>$reciever_id,
            'pageProduct_id'=>$pageProduct_id,
            'is_bought'=>0
        ]);

        return response()->json([
            'message'=>trans('response.invitation_sent')
        ], 201);
    }

    public function addBonus($invite)
    {
        $productPrice = PageProduct::find($invite->pageProduct_id)->price;
        $bonus = $productPrice*20/100;
        $sender = $invite->sender_id;
        $earnings  = Earning::create([
            'user_id'=>$sender,
            'value'=>$bonus,
        ]);
    }

    public function invitePayment($invite, $quantity)
    {
        $product = $invite->pageProduct_id;
        $productPrice = PageProduct::find($product)->price;
        $quantity = $quantity;
        $cost = $productPrice*$quantity;
        $date = Carbon::now();
        if($quantity <= 0){
            return response()->json([
                'message'=>trans('response.quantity_error')],
                Response::HTTP_BAD_REQUEST);
        }
        $purchase = Purchase::create([
            'page_product_id'=>$product,
            'user_id'=>auth()->id(),
            'quantity'=>$quantity,
            'cost'=>$cost,
            'date'=>$date,
        ]);
    }

    public function acceptInvite( Request $request)
    {
        $inviteId = $request->invite;
        $invite = Invitation::find($inviteId);
        if (!$invite) {
            return response()->json([
                'message' => trans('response.invitation_404')], 
                Response::HTTP_NOT_FOUND);
        }
        if($invite->is_bought == 1){
            return response()->json([
                'message' => trans('response.invitation_already_accepted')],
                Response::HTTP_CONFLICT);
        }
        $quantity = $request->quantity;
        $this->invitePayment($invite, $quantity);
        $this->addBonus($invite);
        $invite->update(['is_bought'=>1]);
        return response()->json([
            'message' => trans('response.invite_accepted')], 
            Response::HTTP_CREATED);
    }
}
