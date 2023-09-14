<?php

namespace App\Http\Controllers;

use App\Models\PageUser;
use App\Http\Requests\StorePageUserRequest;
use App\Http\Requests\UpdatePageUserRequest;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response ;
use Illuminate\Support\Facades\Hash;

class PageUserController extends Controller
{
    public function addPageUser($pageId, Request $request)
    {
        $user_id = $request->user;
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
        $pageUser= PageUser::create([
            'page_id'=>$pageId,
            'user_id'=>$user_id,
            'state'=>'accessible'
        ]);
        return response()->json([
            'message'=>trans('response.user_201'),
            'data'=>$pageUser
        ], 201);
    }
    public function BlockUser($pageId, $pageUserId, $state)
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
                Response::HTTP_FORBIDDEN);
        }
        $user = PageUser::find($pageUserId);
        if (!$user) {
            return response()->json([
                'message' => trans('response.user_404')], 
                Response::HTTP_NOT_FOUND);
        }
        if ($user->state == $state) {
            return response()->json([
                'message' => "The user is already $state."]);
        }
        $user->update(['state' => $state]);
        return response()->json([
            'message' => trans('response.blocked')], 
            Response::HTTP_OK);
    }
    
}
