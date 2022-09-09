<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Driveraccount;
use App\Models\Promocode;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class CouponController extends Controller
{
function addCoupon(Request $request){

    
        // use Illuminate\Validation\Validator::validateIn;
        $rules =array(
            "coupon_name" => 'required',
            "value" => 'required',
            "usage_limit" => "required",
            "status" => "required",
            "type" => "required",
            "expiry_date" => "required|date",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = new Promocode;
         $user->coupon_name = $request->coupon_name;
         $user->type = $request->type;
         $user->expiry_date = $request->expiry_date;
         $user->usage_limit = $request->usage_limit;
         $user->value = $request->value;
         $user->status = $request->status;
         

            $result= $user->save();
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' =>'Coupon successfully Created!',
                'data' => $user,
                
            ];
                return response($response, 201);
            } else {
                return response("Coupon is not created", 401);
            }
        }

}

function updateCoupon(Request $request){
    

    
        // use Illuminate\Validation\Validator::validateIn;
        $rules =array(
            "coupon_id" => 'required',
            "coupon_name" => 'required',
            "value" => 'required',
            "usage_limit" => "required",
            "status" => "required",
            "type" => "required",
            "expiry_date" => "required|date",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            if (Promocode::find($request->coupon_id)) {
                $user = Promocode::find($request->coupon_id);
                $user->coupon_name = $request->coupon_name;
                $user->type = $request->type;
                $user->expiry_date = $request->expiry_date;
                $user->usage_limit = $request->usage_limit;
                $user->value = $request->value;
                $user->status = $request->status;
         

                $result= $user->save();
                if ($result) {
                    $response = [
                    'status' => 'success',
                    'message' =>'Coupon successfully Updated!',
                'data' => $user,
                
            ];
                    return response($response, 201);
                } else {
                    return response("Coupon is not Updated", 401);
                }
            }
            else{
                return response("Coupon ID Not Valid", 401);
            }
        }

}

function getCoupons(Request $request){
    $rules =array(
        
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
        $riders= Promocode::get();
        $response = [
            'status' => "Done",
            'message' => "All Coupons",
            'data' => $riders,
            
        ];
        return response($response, 200);  
      }

}

function availCoupon(Request $request){
    
        // use Illuminate\Validation\Validator::validateIn;
        $rules =array(
            "coupon_name" => 'required',
           "is_used" => "required|in:0,1",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            
            if (Promocode::where('coupon_name',$request->coupon_name)->first()) {
                $user =  Promocode::where('coupon_name',$request->coupon_name)->first();
                if ($user->status=='active') {
                    if ($user->usage_limit!=0) {
                        if ($user->expiry_date>=date("Y-m-d")) {
                            if ($request->is_used==1) {
                                $temp = $user->usage_limit;
                                $is_used = $temp-1;
                                $user->usage_limit = $is_used;
                                $result= $user->save();
                                if ($result) {
                                    $response = [
                    'status' => 'success',
                    'message' =>'Coupon successfully Availed!',
                'data' => $user,
                
            ];
                                    return response($response, 201);
                                } else {
                                    return response( [
                                        'message' => "Coupon is not Availed",
                                        'status' => "Failed"
                                ], 401);
                                }
                            }
                            else{
                                $response = [
                                    'status' => 'success',
                                    'message' =>'Coupon deatails',
                                'data' => $user ];
                                return response($response, 201);
                            }
                        }
                        else{
                            return response( [
                                'message' => "Coupon Expiry Date Reached!",
                                'status' => "Failed"
                        ], 403);
                        }
                    }
                    else {
                        return response( [
                            'message' => "Coupon Usage limit Reached!",
                            'status' => "Failed"
                    ], 403);
                    }
                    }
                else {
                    return response( [
                        'message' => "Coupon is not Active",
                        'status' => "Failed"
                ], 404);
                }
               
            }
            else {
                return response( [
                    'message' => "Coupon is not Found",
                    'status' => "Failed"
            ], 404);
            }
        }

}
}