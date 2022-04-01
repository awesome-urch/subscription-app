<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Website;
use App\Models\User;
use App\Models\WebsiteUser;
use Validator;

class UserController extends Controller
{
    //

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function subscribeToWebsite(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'website_id' => 'required',
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $data = $request->all();
        $websiteId = $data['website_id'];
        $userId = $data['user_id'];

        $user = User::where([
            'id' => $userId,
        ])->get();

        if($user->count() < 1){
            return response()->json(['error'=>'true','message'=>'User does not exist']);
        }

        $subscriber = WebsiteUser::where([
            'user_id' => $userId,
            'website_id' => $websiteId,
        ])->get();

        // $website = Website::where('id',$websiteId)->get();
        if($subscriber->count() > 0){
            return response()->json(['error'=>'true','message'=>'User is already subscribed']);
        }

        $subscription = WebsiteUser::create($request->all());

        return response()->json($subscription, 201);
    }

}
