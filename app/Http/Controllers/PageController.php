<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use Illuminate\Http\Request;    

class PageController extends Controller
{
    public function makeNewPage(Request $request)
    {
        $name = $request->name;
        $photo_url = '/storage/' . $request->file('photo')->store('pages', 'public');
        $page = Page::create([
            'page_name'=>$name,
            'page_owner'=>auth()->id(),
            'page_photo'=>$photo_url
        ]);
        return response()->json([
            'message'=>trans('response.page_201'),
            'page'=>$page
        ], 201);
    }
}
