<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RiderController extends Controller
{
    function getRiders(Request $request){
        $isadmin= User::where('email', $request->admin_email)->first();
        if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
            return response([
                'message' => ['Wrong Admin Credentials']
            ], 401);
        } 
        else{
            $rules =array(
            "admin_email" => "required|email",
            "admin_password" => "required|min:6",
            "user_type" => "required|in:rider",
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= User::where('user_type', $request->user_type)->get();
            $response = [
                'status' => "Done",
                'message' => "All Riders in DB",
                'riders' => $riders,
                
            ];
            return response($response, 200);

            
            }
        }
    }

    function getRider(Request $request){
       
            $rules =array(
           
            "rider_id" => "required",

        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= User::where('user_type', 'rider')->where('id', $request->rider_id)->first();
            $response = [
                'status' => "Done",
                'message' => "Rider Fetched By its ID",
                'data' => $riders,
                
            ];
            return response($response, 200);

            
            
        }
    }

    function ridersRides(Request $request){
        
        
            $rules =array(
           
            "rider_id" => "required",
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ride::where('rider_id', $request->rider_id)->get();
            $response = [
                'status' => "Done",
                'message' => "All Rides of Rider",
                'rides' => $riders,
                
            ];
            return response($response, 200);

            
            
        }
    }

}