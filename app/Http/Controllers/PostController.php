<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Website;
use Validator;

class PostController extends Controller
{
    //

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'website_id' => 'required',
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $data = $request->all();
        $websiteId = $data['website_id'];

        $website = Website::where('id',$websiteId)->get();
        if($website->count() < 1){
            return response()->json(['error'=>'true','message'=>'Selected website does not exist']);
        }

        $post = Post::create($request->all());

        return response()->json($post, 201);
    }

}
