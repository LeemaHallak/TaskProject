<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\PageUserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PageProductController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/logIn', [UserController::class, 'logIn']);
Route::group( ['middleware' => 'auth:sanctum' ],function(){
    Route::get('/showEarnings', [EarningController::class, 'showEarnings']);
    Route::put('/block/{pageId}/{pageUserId}/{state}', [PageUserController::class, 'BlockUser']);
    Route::post('/discount', [DiscountController::class, 'makeDiscount']);
    Route::post('/makePage', [PageController::class, 'makeNewPage']);
    Route::post('/addCategory', [CategoryController::class, 'addCategory']);
    Route::controller(InvitationController::class)->group(function(){
        Route::post('/invite', 'invite');
        Route::post('/accept', 'acceptInvite');
    }); 
    Route::controller(PageProductController::class)->group(function(){
        Route::get('/purchases/{pageId}', 'purchasesQuantity');
        Route::get('/productsQuantity/{pageId}', 'productsQuantity');
        Route::post('/addProduct', 'addPageProduct')->middleware('accessible');
        Route::get('/showPageProducts/{pageId}', 'showPageProducts')->middleware('accessible');
    });
    Route::controller(UserController::class)->group(function(){
        Route::get('/financial', 'userFinancial');
        Route::get('/showFriends', 'showFriends');
        Route::post('/addFriend', 'addFriend');
        Route::post('/updateFriends', 'updateRequest');
    });
    Route::controller(PurchaseController::class)->group(function(){
        Route::get('/showPayments', 'showPurchases');
        Route::post('/addPurchase', 'addPurchase');
    });
});
