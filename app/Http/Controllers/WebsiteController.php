<?php

namespace App\Http\Controllers;

// use App\Website;
use Illuminate\Http\Request;
use App\Models\Website;

class WebsiteController extends Controller
{
    //

    public function create(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|unique:categories'
        // ]);

        $category = Website::create($request->all());

        return response()->json($category, 201);
    }

    public function check(){
        return response()->json(array());
    }

}
