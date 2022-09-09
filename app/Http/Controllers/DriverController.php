<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Driveraccount;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    function getDrivers(Request $request){
        
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
            "user_type" => "required|in:driver",
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= User::where('user_type', $request->user_type)->get();
            $response = [
                'status' => "Done",
                'message' => "All Drivers in DB",
                'Drivers' => $riders,
                
            ];
            return response($response, 200);

            
            }
        }
    }
    function getDriver(Request $request){
       
        $rules =array(
        "id" => "required",
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
        $riders= User::where('user_type', 'driver')->where('id', $request->id)->first();
        $response = [
            'status' => "Done",
            'message' => "Drivers Fetched By Email",
            'data' => $riders,
            
        ];
        return response($response, 200);

        
        
    }
}
function driversRides(Request $request){
        
        
    $rules =array(
   
    "driver_id" => "required",
    
);
    $validator= Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return $validator->errors();
    } else {
    $riders= Ride::where('driver_id', $request->driver_id)->get();
    $response = [
        'status' => "Done",
        'message' => "All Rides of Driver",
        'rides' => $riders,
        
    ];
    return response($response, 200);  
  }
}

function uploadDoc(Request $request){
    
    $rules =array(
        "driver_id" => "required",
        'driving_licence' => 'mimes:png,jpg,jpeg,gif|max:2048',
        'identity_proof' => 'mimes:png,jpg,jpeg,gif|max:2048',
        'vehicle_insaurance' => 'mimes:png,jpg,jpeg,gif|max:2048',
        'vehicle_proof' => 'mimes:png,jpg,jpeg,gif|max:2048',

    );
    $validator= Validator::make($request->all(),$rules);
    $test = 0;
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = new Document;
            $user->driver_id = $request->driver_id;
            if($request->hasFile('driving_licence')) {
                $test = $test+1;
                $file = $request->file('driving_licence')->store('public/img/driving_licence');
                $user->driving_licence = $file;
            }
            if($request->hasFile('identity_proof')) {
                $test = $test+1;
                $file = $request->file('identity_proof')->store('public/img/identity_proof');
                $user->identity_proof = $file;
            }
            if($request->hasFile('vehicle_insaurance')) {
                $test = $test+1;
                $file = $request->file('vehicle_insaurance')->store('public/img/vehicle_insaurance');
                $user->vehicle_insaurance = $file;
            }
            if($request->hasFile('vehicle_proof')) {
                $test = $test+1;
                $file = $request->file('vehicle_proof')->store('public/img/vehicle_proof');
                $user->vehicle_proof = $file;
            }
            if($test==0){
               
                return response([
                    'message' => "Please Add Atleast one Identies"
                    
                ], 201);
            }
            else{
                if(Document::where('driver_id', $request->driver_id)->first())Document::where('driver_id', $request->driver_id)->first()->delete();
                   
                $result= $user->save();
                if ($result) {
                    $response = [
                'message' => "Identies Sucessfully Added"
                
            ];
                    return response($response, 201);
                } else {
                    return response("Driver Id not updated", 401);
                }
            }
        }
}

function getDoc(Request $request){        
    $rules =array(
        "driver_id" => "required",   
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
        $riders= Document::where('driver_id', $request->driver_id)->first();
        $response = [
            'status' => "Done",
            'message' => "All Documents of Driver",
            'rides' => $riders,
            
        ];
        return response($response, 200);  
      }

}
function addDriverPayment(Request $request){
       
    $isadmin= User::where('email', $request->admin_email)->first();
    if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
        return response([
            'message' => ['Wrong Admin Credentials']
        ], 401);
    } 
    else{
        $rules =array(
        "admin_email" => "required|email",
        "admin_password" => "required",
        "drivers_id" => "required|unique:driveraccounts",
        "account_no" => "required",
        "bank_name" => "required",
        "site_comission" => "required",
        "total_amount" => "required",
        "payable_amount" => "required",
        "payment_method" => "required",   
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        }
        else
        {
            
            
            $user = new Driveraccount;
            $user->drivers_id=$request->drivers_id;
            $user->account_no=$request->account_no;
            $user->bank_name=$request->bank_name;
            $user->site_comission=$request->site_comission;
            $user->total_amount=$request->total_amount;
            $user->payable_amount=$request->payable_amount;
            $user->payment_method=$request->payment_method;
    
            $result= $user->save();
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' =>'driver account created successfully',
                'data' => $user,
                
            ];
                return response($response, 201);
            } else {
                return response("Driver account creation failed is not created", 401);
            }

        

        
        }
    }  
}

function updateDriverPayment(Request $request){
     
    $isadmin= User::where('email', $request->admin_email)->first();
    if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
        return response([
            'message' => ['Wrong Admin Credentials']
        ], 401);
    } 
    else{
        $rules =array(
        "admin_email" => "required|email",
        "admin_password" => "required",
        "drivers_id" => "required",
        "account_no" => "required",
        "bank_name" => "required",
        "site_comission" => "required",
        "total_amount" => "required",
        "payable_amount" => "required",
        "payment_method" => "required",   
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        }
        else
        {
            if(Driveraccount::find($request->account_id)){
            $id = $request->drivers_id;
            $user = Driveraccount::find($request->account_id);
            $user->drivers_id=$request->drivers_id;
            $user->account_no=$request->account_no;
            $user->bank_name=$request->bank_name;
            $user->site_comission=$request->site_comission;
            $user->total_amount=$request->total_amount;
            $user->payable_amount=$request->payable_amount;
            $user->payment_method=$request->payment_method;
            $result= $user->save();
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' =>'driver account updated successfully',
                'data' => $user,
                
            ];
                return response($response, 201);
            } else {
                return response("Driver account updation failed is not created", 401);
            }

        }
        else{
            return response("Account ID does not exist", 401);
        }

        
        }
    } 
}

function getDriverAccount(Request $request){
    
    $rules =array(
        "drivers_id" => "required",
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
        $riders= Driveraccount::where('drivers_id', $request->drivers_id)->first();
        $response = [
            'status' => "Done",
            'message' => "Driver account",
            'Drivers' => $riders,
            
        ];
        return response($response, 200);

        
        }
}



}