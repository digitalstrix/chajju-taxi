<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    function getRideDetail(Request $request){

    $rules =array(
   
        "ride_id" => "required",
        
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
        $riders= Ride::where('id', $request->ride_id)->first();
        $response = [
            'status' => "Done",
            'message' => "Rides Details",
            'rides' => $riders,
            
        ];
        return response($response, 200);  
      }
    }

    function allRides(Request $request){
        
        $isadmin= User::where('email', $request->admin_email)->first();
        if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
            return response("Admin Credentials Wrong", 401);
        }  
        else {    
            if ($isadmin->user_type == 'admin') {
               
                
    $rules =array(
   
        
    );
        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
        $riders= Ride::get();
        $response = [
            'status' => "Done",
            'message' => "All Rides Details",
            'rides' => $riders,
            
        ];
        return response($response, 200);  
      }
               
            }
            else
            {

                return response("Logged In User is Not Admin", 401);
            }
        }


    }

    function createRide(Request $request){
        $rules =array(
            "rider_id" => "required",
            "vehicle_id" => "required",
            "pick_time" => "required",
            "pick_location" => "required",
            "drop_location" => "required",
            "total_distance" => "required",
            "base_fare" => "required",
            "total_amount" => "required",
            "vehicle_type" => "required",
            "ride_type" => "required",
            "ride_status" => "required| in:completed,pending,scheduled,cancelled",
            "payment_status" => "required|in:completed,pending,failed",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{

            
            $user = new Ride;
            $user->rider_id=$request->rider_id;
            $user->driver_id=$request->driver_id;
            $user->pick_time=$request->pick_time;
            $user->vehicle_id=$request->vehicle_id;
            $user->pick_location=$request->pick_location;
            $user->drop_location=$request->drop_location;
            $user->total_distance=$request->total_distance;
            $user->base_fare=$request->base_fare;
            $user->tax=$request->tax;
            $user->discount=$request->discount;
            $user->site_comission=$request->site_comission;
            $user->driver_payable_amount=$request->driver_payable_amount;
            $user->admin_earning=$request->admin_earning;
            $user->total_amount=$request->total_amount;
            $user->vehicle_type=$request->vehicle_type;
            $user->ride_type=$request->ride_type;
            $user->ride_status=$request->ride_status;
            $user->payment_status=$request->payment_status;
            $user->payment_type=$request->payment_type;
            $result= $user->save();
            if ($result) {
                $response = [
                    'Status' => "Done",
                    'Message' => "Ride is created",
                'ride data' => $user
            ];
                return response($response, 201);
            } else {
                return response([
                    'Status' => "Failed",
                    'Message' => "Ride is not created"
                ], 401);
            }
        }
       
    }

    function updateRide(Request $request){
        
        $rules =array(
            "rider_id" => "required",
            "driver_id" => "required",
            "vehicle_id" => "required",
            "pick_time" => "required",
            "drop_time" => "required",
            "pick_location" => "required",
            "drop_location" => "required",
            "total_distance" => "required",
            "base_fare" => "required",
            "total_amount" => "required",
            "vehicle_type" => "required",
            "ride_type" => "required",
            "ride_status" => "required| in:completed,pending,scheduled,cancelled",
            "payment_status" => "required|in:completed,pending,failed",
            "cancel_by" => "in:rider,driver",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = Ride::find($request->ride_id);
            $user->rider_id=$request->rider_id;
            $user->driver_id=$request->driver_id;
            $user->vehicle_id=$request->vehicle_id;
            $user->pick_time=$request->pick_time;
            $user->drop_time=$request->drop_time;
            $user->pick_location=$request->pick_location;
            $user->drop_location=$request->drop_location;
            $user->total_distance=$request->total_distance;
            $user->base_fare=$request->base_fare;
            $user->tax=$request->tax;
            $user->discount=$request->discount;
            $user->site_comission=$request->site_comission;
            $user->driver_payable_amount=$request->driver_payable_amount;
            $user->admin_earning=$request->admin_earning;
            $user->total_amount=$request->total_amount;
            $user->vehicle_type=$request->vehicle_type;
            $user->ride_type=$request->ride_type;
            $user->ride_status=$request->ride_status;
            $user->payment_status=$request->payment_status;
            $user->payment_type=$request->payment_type;
            $user->cancel_by=$request->cancel_by;
            $user->cancel_reason=$request->cancel_reason;
            $result= $user->save();
            if ($result) {
                $response = [
                    'Status' => "Done",
                    'Message' => "Ride is Updated",
                'ride data' => $user
            ];
                return response($response, 201);
            } else {
                return response([
                    'Status' => "Failed",
                    'Message' => "Ride is not Updated",
                ], 401);
            }
        }
 
    }

    function cancelledRides(Request $request){

        $rules =array(
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ride::where('ride_status', 'cancelled')->get();
            $response = [
                'status' => "Done",
                'message' => "Cancelled Rides",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }

    }

    function pendingRide(Request $request){

        $rules =array(
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ride::where('ride_status', 'pending')->get();
            $response = [
                'status' => "Done",
                'message' => "pending Rides",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }

    }

    function completedRide(Request $request){

        $rules =array(
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ride::where('ride_status', 'completed')->get();
            $response = [
                'status' => "Done",
                'message' => "Completed Rides",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }

    }

    function scheduledRide(Request $request){

        $rules =array(
            
        );
            $validator= Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
            $riders= Ride::where('ride_status', 'scheduled')->get();
            $response = [
                'status' => "Done",
                'message' => "Schedule Rides",
                'rides' => $riders,
                
            ];
            return response($response, 200);  
          }

    }

    function addVehicle(Request $request){
                
                $rules =array(
        
           "type_name" => "required",
           "type_image" => "required",
           "cost_per_km" => "required",
           "status" => "required|in:active,disabled", 
            

        );
                $validator= Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return $validator->errors();
                } else {
                    $user = new Vehicle;
                    $user->type_name =$request->type_name;
                    $user->cost_per_km=$request->cost_per_km;
                    $user->status =$request->status;
                    $user->car_model =$request->car_model;
                    $user->car_model=$request->car_model;
                    $user->car_number =$request->car_number;
                    $user->seats =$request->seats;
                    $user->fuel_type =$request->fuel_type;
                    // $user->type_image =$request->type_image;
                    $request->hasFile('type_image');
                    $file = $request->file('type_image')->store('public/img/vehicle');
                    $user->type_image  = $file;
                    $result= $user->save();
                    if ($result) {
                        $response = [
                            'Status' => 'success',
                            'message' => 'Vehicle was successfully created',
                            'data' => $user,
            ];
                        return response($response, 201);
                    } else {
                        return response("Vehicle is not created", 401);
                    }
                }
            }
            
     

    function updateVehicle(Request $request){

                
                $rules =array(
           "vehicle_id" => "required",
           "type_name" => "required",
           "type_image" => "required",
           "cost_per_km" => "required",
           "status" => "required|in:active,disabled",  

        );
                $validator= Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return $validator->errors();
                } else {
                    $user = Vehicle::find($request->vehicle_id);
                    $user->type_name =$request->type_name;
                    $user->cost_per_km=$request->cost_per_km;
                    $user->status =$request->status;
                    $user->car_model = $request->car_model;
                    $user->car_number = $request->car_number;
                    // $user->car_type = $request->car_type;
                    $user->car_brand = $request->car_brand;
                    $user->seats=$request->seats;
                    // $user->type_image =$request->type_image;
                    $request->hasFile('type_image');
                    $file = $request->file('type_image')->store('public/img/vehicle');
                    $user->type_image  = $file;
                    $result= $user->save();
                    if ($result) {
                        $response = [
                            'Status' => 'success',
                            'message' => 'Vehicle was successfully updated',
                            'data' => $user,
            ];
                        return response($response, 201);
                    } else {
                        return response("Vehicle is not Updated", 401);
                    }
                }
    }
     
    function deleteVehicle(Request $request){
        
        
        $isadmin= User::where('email', $request->admin_email)->first();
        if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
            return response("Admin Credentials Wrong", 401);
        }  
        else {
            if ($isadmin->user_type == 'admin') {
                
                $rules =array(
           "vehicle_id" => "required",
           "admin_email" => "required | email",
           "admin_password" => "required",

        );
                $validator= Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return $validator->errors();
                } else {
                    if (Vehicle::find($request->vehicle_id)) {
                        $user = Vehicle::find($request->vehicle_id);
                       $result= $user->forceDelete();
                        if ($result) {
                            $response = [
                            'Status' => 'success',
                            'message' => 'Vehicle was successfully deleted',
                            
            ];
                            return response($response, 201);
                        } else {
                            return response("Vehicle is not deleted", 401);
                        }
                    }
                    else{
                        return response("Invaild Delete Id Nothing to delete", 401);
                    }
                }
            }
            else{
                return response("User type is not admin", 401);
            }
        }

       
    }
}