<?php

namespace App\Http\Controllers;

// use App\Website;
use Illuminate\Http\Request;
use App\Models\Website;
use Validator;

class WebsiteController extends Controller
{
    //

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'url' => 'required|url',
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $website = Website::create($request->all());

        return response()->json($website, 201);
    }

    public function check(){
        return response()->json(array());
    }

}
