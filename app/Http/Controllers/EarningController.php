<?php

namespace App\Http\Controllers;

use App\Models\Earning;
use App\Http\Requests\StoreEarningRequest;
use App\Http\Requests\UpdateEarningRequest;
use Illuminate\Http\Response;

class EarningController extends Controller
{
    public function showEarnings()
    {
        $earningsDetails = Earning::where('user_id', auth()->id())->get();
        return !$earningsDetails
            ? response()->json(['message' => trans('response.earnings_error')], Response::HTTP_NO_CONTENT)
            : response()->json([
                'message'=> trans('response.earnings_success'),
                'data' => $earningsDetails], Response::HTTP_OK); 
    }
}
