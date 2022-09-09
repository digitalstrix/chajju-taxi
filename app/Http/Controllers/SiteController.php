<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Driveraccount;
use App\Models\Promocode;
use App\Models\Pushnotification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use App\Models\Sitesetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    function addPushNotification(Request $request){
        
        $rules =array(
           "send_to" => "required|in:all,rider,driver",
           "message" => "required",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user = new Pushnotification;
            $user->send_to = $request->send_to;
            $user->message = $request->message;
         

            $result= $user->save();
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' =>'Notification saved successfully ',
                'data' => $user,
                
            ];
                return response($response, 201);
            } else {
                return response( [
                    'status' => 'Failed',
                    'message' =>'Notification Not saved'
                
            ], 401);
            }
        }

    }

    function getPushNotification(Request $request){
        
        $rules =array(
            "send_to" => "required|in:all,rider,driver",
         );
         $validator= Validator::make($request->all(),$rules);
         if($validator->fails()){
             return $validator->errors();
         }
         else{
             $user = Pushnotification::where('send_to',$request->send_to)->get();
            
             if ($user) {
                 $response = [
                     'status' => 'success',
                     'message' =>'Notification ',
                     'data' => $user,
                 
             ];
                 return response($response, 201);
             } else {
                 return response( [
                     'status' => 'Failed',
                     'message' =>'Notification Not Found'
                 
             ], 401);
             }
         }
    }

    function upadateSite(Request $request){
$rules =array(
"site_title" =>"required",
"site_logo" =>"required",
"site_favicon" =>"required",
"copyright" =>"required",
"playstore_link" =>"required",
"appstore_link" =>"required",
"driver_accept_timeout" =>"required",
"driver_search_radius" =>"required",
"tax" =>"required",
"site_comission" =>"required",
"contact_number" =>"required",
"email" =>"required|email",
"fb_link" =>"required",
"insta_link" =>"required",
"linkedin_link" =>"required",
"twitter_link" =>"required",
"pin_link" =>"required",
"social_login" =>"required|in:enabled,disabled",
        );
        $validator= Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
        }
        else{
            $user= Sitesetting::where('id',1)->first();
            $user->site_title = $request->site_title;
            // $user->site_logo = $request->site_logo;
            if($request->hasFile('site_logo')) {
                $file = $request->file('site_logo')->store('public/img/sitelogo');
                $user->site_logo = $file;
            
            }
            $user->site_favicon = $request->site_favicon;
            if($request->hasFile('site_favicon')) {
                $file = $request->file('site_favicon')->store('public/img/sitefavicon');
                $user->site_favicon = $file;
            }
            $user->site_comission = $request->site_comission;
            $user->copyright = $request->copyright;
            $user->playstore_link = $request->playstore_link;
            $user->appstore_link = $request->appstore_link;
            $user->driver_accept_timeout = $request->driver_accept_timeout;
            $user->driver_search_radius = $request->driver_search_radius;
            $user->tax = $request->tax;
            $user->contact_number = $request->contact_number;
            $user->email = $request->email;
            $user->fb_link = $request->fb_link;
            $user->pin_link = $request->pin_link;
            $user->linkedin_link = $request->linkedin_link;
            $user->insta_link = $request->insta_link;
            $user->twitter_link = $request->twitter_link;
            $user->google_link = $request->google_link;
            $user->social_login = $request->social_login;
            $user->google_map_key = $request->google_map_key;
            $user->privacy_policy = $request->privacy_policy;
            $user->terms_condition = $request->terms_condition;
            $result = $user->save();


           
        
if ($result) {
    $response = [
                "status" => "success",
                "message" => "Successfully Saved",
                'data' => $user,
               
            ];
}
else{
    return response(["status" => "error", "message" => "Site not saved"], 401);
}
        
            return response($response, 200);
        }
    }
function getSite(Request $request){
    
    $rules =array(
     );
     $validator= Validator::make($request->all(),$rules);
     if($validator->fails()){
         return $validator->errors();
     }
     else{
         $user = Sitesetting::get();
         $result= $user;
         if ($result) {
             $response = [
                 'status' => 'success',
                 'message' =>'Site Details',
             'data' => $user,
             
         ];
             return response($response, 201);
         } else {
             return response( [
                 'status' => 'Failed',
                 'message' =>'Site Not Fetched',
             
         ], 401);
         }
     }

}
    
}