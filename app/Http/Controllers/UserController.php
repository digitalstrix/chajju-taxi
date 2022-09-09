<?php

namespace App\Http\Controllers;
use Twilio;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
       function index(Request $request)
    {

        $rules =array(
            "email" => "required|email",
            "password" => "required"
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user= User::where('email', $request->email)->first();
            
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
            $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
      
            return response($response, 200);
        }
    }

    function register(Request $request){
        // use Illuminate\Validation\Validator::validateIn;
        $rules =array(
            "name" => "required",
            "email" => "required|email|unique:users",
            "phone" => "required|unique:users",
            "password" => "required|min:6",
            "user_type" => "required|in:admin,driver,rider",
            'profile_img' => 'mimes:png,jpg,jpeg,gif|max:2048',

        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = new User;
            $user->name=$request->name;
            $user->email=$request->email;
            $user->user_type=$request->user_type;
            $user->phone=$request->phone;
            $user->city=$request->city;
            $user->address=$request->address;
            $user->pan_card =$request->pan_card;
            $user->password=Hash::make($request->password);
            if($request->hasFile('profile_img')) {
                $file = $request->file('profile_img')->store('public/img/userprofiles');
                $user->profile_img = $file;
            
            }
            $result= $user->save();
            if ($result) {
                $token = $user->createToken('my-app-token')->plainTextToken;
                $response = [
                'user' => $user,
                'token' => $token
            ];
                return response($response, 201);
            } else {
                return response("User is not created", 401);
            }
        }

    }

    function updateUser(Request $request){

        $isadmin= User::where('email', $request->admin_email)->first();
        if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
            return response("Admin Credentials Wrong", 401);
        }  
        else {    
            if ($isadmin->user_type == 'admin') {
                if (User::find($request->id)) {
                    $user= User::where('id', $request->id)->first();
          
                    $rules =array(
                    "name" => "required",
                    "password" => "required|min:6",
                    "user_type" => "required|in:admin,driver,rider",
                    'profile_img' => 'mimes:png,jpg,jpeg,gif|max:2048',
                    'status' => 'in:active','disabled',
                    'reviews' => 'numeric|max:5'
                );
                    $validator= Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        return $validator->errors();
                    } else {
                        $update = User::find($request->id);
                        $update->name = $request->name;
                        $update->status = $request->status;
                        $update->address = $request->address;
                        // $update->profile_img = $request->profile_img;
                        $update->user_type = $request->user_type;
                        $update->phone = $request->phone;
                        $update->city = $request->city;
                        $update->password = Hash::make($request->password);
                        // $update->reviews = $request->reviews;
                        if($user->reviews != 0){
                            $review = $user->reviews;
                            $cal = ($review + $request->reviews)/2;
                            $update->reviews = $cal;
                        }

                        if($request->hasFile('profile_img')) {
                            $file = $request->file('profile_img')->store('public/img/userprofiles');
                            $update->profile_img = $file;
                        
                        }
                        


                        if ($update->save()) {
                            return response("User Sucessfully Updated", 400);
                        }
                    }
                } else {
                    return response("User Does Not Exist", 401);
                }
            }
            else{
                return response("Logged In User is Not Admin", 401);
            }
        }

    }

    function deleteUser(Request $request){
        

        $isadmin= User::where('email', $request->admin_email)->first();
        if (!$isadmin || !Hash::check($request->admin_password, $isadmin->password)) {
            return response("Admin Credentials Wrong", 401);
        }  
        else {    
            if ($isadmin->user_type == 'admin') {
                if (User::find($request->id)) {
                   
                    $rules =array(
                    "email" => "required",
                    "id" => "required"
                );
                    $validator= Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        return $validator->errors();
                    } else {
                        $user= User::where('email', $request->email,)->where('id', $request->id)->first();
                        if (!$user) {
                            return response("Please enter correct credentials to delete this user", 401);
                        } 
                        else{
                            $user = User::find($request->id);
                            if($user->forceDelete())
                            return response("User Sucessfully Deleted", 200);
                        }

                        
                    }
                } else {
                    return response("User Does Not Exist", 401);
                }
            }
            else{
                return response("Logged In User is Not Admin", 401);
            }
        }

    }
function otplogin(Request $request){

    $rules =array(
        "mobile_no" => "required",
        "user_type" => "required|in:rider,driver",

    );
    $validator= Validator::make($request->all(),$rules);
    if($validator->fails()){
        return $validator->errors();
    }
    else{
        $user= User::where('phone',$request->mobile_no)->where('user_type',$request->user_type)->first();
        if($user){
            $otp = rand(111111,999999);
            $receiverNumber = $request->mobile_no;
            $message = "Your Chajju Taxi Login OTP Code: ".$otp." Please Do not Share With others.";
      
            try {
                $account_sid = getenv("TWILIO_SID");
                $auth_token = getenv("TWILIO_TOKEN");
                $twilio_number = getenv("TWILIO_FROM");
      
                $client = new Client($account_sid, $auth_token);
                $client->messages->create($receiverNumber, [
                    'from' => $twilio_number, 
                    'body' => $message]);
                $user->otp = $otp;
                $user->save();
      
                    return response(["status" => "Done","message" => "OTP Sent to User"], 200);
      
            } catch (Exception $e) {
                // dd("Error: ". $e->getMessage());
                return response(["status" => "Done","message" => $e->getMessage()], 200);   
            }
        }
        else{
            return response(["status" => "failed","message" => "Phone number is not registered or Different User type Login"], 200);
        }
    }
}
    
function otpValidate(Request $request){
    
    $rules =array(
        "mobile_no" => "required",
        "user_type" => "required|in:rider,driver",
        "otp" => "required",

    );
    $validator= Validator::make($request->all(),$rules);
    if($validator->fails()){
        return $validator->errors();
    }
    else{
        $user= User::where('phone',$request->mobile_no)->where('user_type',$request->user_type)->where('otp',$request->otp)->first();
        if($user){
            $otp = "";
            $user->otp = $otp;
            $user->save();
            $token = $user->createToken('my-app-token')->plainTextToken;
            return response(["status" => "Sucess","message" => "Login Successfully","data" => $user,"token" => $token], 200);
        }
        else{
            return response(["status" => "failed","message" => "Invalid OTP"], 200);
        }
    }
}

}