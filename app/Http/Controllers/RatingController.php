<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Driveraccount;
use App\Models\Driversrating;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use App\Models\Ridersrating;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    function addDriverRating(Request $request){
        
        // use Illuminate\Validation\Validator::validateIn;
        $rules =array(
            "ride_id" => "required|unique:driversratings",
            "driver_id" => "required",
            "rider_id" => "required",
            "rating" => "required", 
            "comment" => "max:100",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = new Driversrating;
         $user->rider_id = $request->rider_id;
         $user->driver_id = $request->driver_id;
         $user->ride_id = $request->ride_id;
         $user->rating = $request->rating;
         $user->comment = $request->comment;

            $result= $user->save();
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' =>'Review successfully Created!',
                'data' => $user,
                
            ];
                return response($response, 201);
            } else {
                return response("Review is not created", 401);
            }
        }

    }

    function deleteDriverRating(Request $request){
            
        $rules =array(
            "rating_id" => "required",
         );
                 $validator= Validator::make($request->all(), $rules);
                 if ($validator->fails()) {
                     return $validator->errors();
                 } else {
                     if (Driversrating::find($request->rating_id)) {
                         $user = Driversrating::find($request->rating_id);
                        $result= $user->forceDelete();
                         if ($result) {
                             $response = [
                             'Status' => 'success',
                             'message' => 'Rating was successfully deleted',
                             
             ];
                             return response($response, 201);
                         } else {
                             return response("Rating is not deleted", 401);
                         }
                     }
                     else{
                         return response("Invaild Delete Id Nothing to delete", 401);
                     }
                 }
        
    }

    function getReviewByDriverId(Request $request){

        $rules =array(
   
            "driver_id" => "required",
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Driversrating::where('driver_id', $request->driver_id)->get();
            $response = [
                'status' => "Done",
                'message' => "Reviews of Driver by Driver ID",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }
    
    }

    function getReviewByRiderId(Request $request){
        
        $rules =array(
   
            "rider_id" => "required",
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Driversrating::where('rider_id', $request->rider_id)->get();
            $response = [
                'status' => "Done",
                'message' => "Reviews of Driver By Rider",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }
    }


    function addRiderRating(Request $request){
        
        // use Illuminate\Validation\Validator::validateIn;
        $rules =array(
            "ride_id" => "required|unique:driversratings",
            "driver_id" => "required",
            "rider_id" => "required",
            "rating" => "required", 
            "comment" => "max:100",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = new Ridersrating;
         $user->rider_id = $request->rider_id;
         $user->driver_id = $request->driver_id;
         $user->ride_id = $request->ride_id;
         $user->rating = $request->rating;
         $user->comment = $request->comment;

            $result= $user->save();
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' =>'Review successfully Created!',
                'data' => $user,
                
            ];
                return response($response, 201);
            } else {
                return response("Review is not created", 401);
            }
        }

    }

    function deleteRiderRating(Request $request){
            
        $rules =array(
            "rating_id" => "required",
         );
                 $validator= Validator::make($request->all(), $rules);
                 if ($validator->fails()) {
                     return $validator->errors();
                 } else {
                     if (Ridersrating::find($request->rating_id)) {
                         $user = Ridersrating::find($request->rating_id);
                        $result= $user->forceDelete();
                         if ($result) {
                             $response = [
                             'Status' => 'success',
                             'message' => 'Rating was successfully deleted',
                             
             ];
                             return response($response, 201);
                         } else {
                             return response("Rating is not deleted", 401);
                         }
                     }
                     else{
                         return response("Invaild Delete Id Nothing to delete", 401);
                     }
                 }
        
    }

    function riderReviewByDriverId(Request $request){

        $rules =array(
   
            "driver_id" => "required",
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ridersrating::where('driver_id', $request->driver_id)->get();
            $response = [
                'status' => "Done",
                'message' => "Reviews of Rider by Driver ",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }
    
    }

    function riderReviewByRiderId(Request $request){
        
        $rules =array(
   
            "rider_id" => "required",
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ridersrating::where('rider_id', $request->rider_id)->get();
            $response = [
                'status' => "Done",
                'message' => "Reviews of Riders By Rider",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }
    }
}