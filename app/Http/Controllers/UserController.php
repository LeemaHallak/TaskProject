<?php

namespace App\Http\Controllers;

use App\Models\Earning;
use App\Models\Friendship;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|numeric|digits:10',
            'address' => 'required',
        ]);

        $registerInputs = $request->all();
        $registerInputs['password'] = Hash::make($registerInputs['password']);

        $user = User::create($registerInputs);

        $accessToken = $user->createToken('personal-access-token', ['user']);

        return response()->json([
            'message' => trans('response.user_registerd'),
            'data' => $user,
            'token_type' => 'Bearer',
            'access_token' => $accessToken->plainTextToken,
        ], 201);
    } 

    public function logIn(Request $request)
    {
        $request -> validate([
            'email' => 'email|required|exists:users',
            'password' => 'required',
        ]);

        $user = User::firstwhere('email', $request->email);

        if(!$user || !Hash::check($request->password, $user->password))
        {
            return response()->json([
                'status' => false,
                'message' => trans('response.logIn_failed')
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => trans('response.logIn_success'),
            'token' => $user->createToken('authToken', ['student'])->plainTextToken
        ]);
    }

    public function userFinancial()
    {
        $payments = Purchase::where('user_id', auth()->id())->sum('cost');
        $earnings = Earning::where('user_id', auth()->id())->sum('value');
        
        return !$payments && !$earnings
        ? response()->json(['message' => trans('response.user_financial_error')], Response::HTTP_NO_CONTENT)
        : response()->json([
            'your payments'=>$payments,
            'your earnings'=>$earnings], Response::HTTP_OK);
    }

    public function addFriend(Request $request)
    {
        $user1 = User::find(auth()->id());
        $user2 = User::find($request->friend);

        $friendship = Friendship::create([
            'user_id' => $user1->id,
            'friend_id' => $user2->id,
        ]);

        return response()->json([
            'message'=>trans('response.add_friend_success')
        ], 201);
    }

    public function updateRequest(Request $request)
    {        
        $requestId = $request->requestId;
        $state = $request->state;
        $friendship = Friendship::find($requestId);
        if($state == 'reject'){
            $friendship->delete();
            return response()->json([
                'message'=>trans('response.friendship_deleted')
            ], 200);
        }
        $friendship->update([
            'requestState'=>$state,
        ]);
        return response()->json([
            'message'=>trans('response.request_accepted')
        ], 200);
    }

    public function showFriends()
    {
        $user = User::find(auth()->id());
        $friends = $user->friends()->where('requestState', 'Accepted')->get();
        return $friends->isEmpty()
            ? response()->json(['message' => trans('response.friends_404')], Response::HTTP_NO_CONTENT)
            : response()->json([
                'message' => trans('response.show_friends_success'),
                'data' => $friends], Response::HTTP_OK); 
    }

    public function showPendingRequests()
    {
        $user = User::find(auth()->id());
        $pendingRequests = $user->friends()->where('status', 'pending')->get();
        return !$pendingRequests
            ? response()->json(['message' => trans('response.friends_404')], Response::HTTP_NO_CONTENT)
            : response()->json([
                'message' => trans('response.show_pendings_success'),
                'data' => $pendingRequests], Response::HTTP_OK);
    }

}
