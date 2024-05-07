<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\SMSGateways\VictoryLinkSms;
use App\Models\UserVerification;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    

    public function Register(Request $request)
    {
          $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'phone' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
       ]
       ,[
           'email.required'=>trans('auth.email.register'),
           'email.unique'=>trans('auth.email.unique.register'),
    
           
        ]);
    if ($validator->fails()) {
        return response()->json(['message'=>$validator->errors()->first()]);


    }
 
    // Generate a random code for the user
    $code = mt_rand(100000, 999999);

    $photo = $request->file('image');
    $ext = $photo->getClientOriginalName();
    $image = "image" . uniqid() . ".$ext";
    $photo->move(public_path('images/users'), $image);

    // Create the user record
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'image' => $request->image,
        'code' => $code,
       
    ]);
    

  
    // Mail::to($user->email)->send(new CodeMail($code));
     return response()->json([
           'message' =>trans('auth.register.success'),
           'user' => $user,
       ]);
    }
         //logout
    public function logout(Request $request)
{
    $token = $request -> header('auth-token');
        if($token){
            // try {

                JWTAuth::setToken($token)->invalidate(); //logout
            // }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            //     return  $this -> returnError('','some thing went wrongs');
            // }
            return response()->json(['message' =>trans('auth.logout.success')]);
        }else{
            return response()->json(['message' =>trans('auth.logout.failed')]);
        }
}

public function getUserData()
{
    $user=User::where('id',Auth::guard('user-api')->user()->id)->first();
    return Response::json(array(
        'data'=>$user,
    ));
}
public function login(Request $request)
{
    $validator = Validator::make($request->all(),[
        'email' => 'required|string|email|max:255',
    ] ,[
        'email.required'=>trans('auth.email.register'),
      
       'password.required'=>trans('auth.password.register'),
      
       'email.exists'=>trans('auth.login.exists')
     ]);
     if ($validator->fails()) {
        return response()->json(['message'=>$validator->errors()->first(),'status'=> 422]);
   
    }
    $user = User::where('email', $request->email)->first();

    // Check if the user exists
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    $code = mt_rand(100000, 999999);

// Update the code in the database
$user->code = $code;
$user->save();
    // Mail::to($user->email)->send(new CodeMail($code));
     return response()->json([

           'message' =>trans('mail send successfully'),
           'code'=>$code
          
       ]);
    
   
}

public function check_code(Request $request)
{
    $validator = Validator::make($request->all(),[
        'email' => 'required|string|email|max:255',
        'code' => 'required|string|max:6', // Assuming code is a 6-digit string
    ] ,[
        'email.required'=>trans('auth.email.register'),
      
       'password.required'=>trans('auth.password.register'),
      
       'email.exists'=>trans('auth.login.exists')
     ]);
     if ($validator->fails()) {
        return response()->json(['message'=>$validator->errors()->first(),'status'=> 422]);
   
    }
    $user = User::where('email', $request->email)->first();

    // Check if the user exists
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    
                if ($request->code === $user->code) {
                 
                    $token= auth()->guard('user-api')->login($user);
                    return $this->respondWithToken($token);
      
                } else {
                    // Code doesn't match, return an error message
                    return response()->json(['message' => 'Code is invalid'], 400);
                }

}
protected function respondWithToken($token)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'status'=>200,
        'message'=>trans('auth.login.success'),
        'user'=>Auth::guard('user-api')->user(),
    ]);
}

    public function ftoken(Request $request)
    {

        $user = Patient::where('id', Auth::guard('user-api')->user()->id)->update(
            ['ftoken' => $request->ftoken]
        );
        return Response::json(array(
            'message' => 'تمت الاضافة بنجاح',
        ));
    }
    public function all_users_notifications()
    {
         $notifications= Notification::where('receiver_id',Auth::guard('user-api')->user()->id)->where('receiver_type','user')->get();
        return response()->json([
            
            'noifications'   => $notifications
        ]);
    }
    public function removeFCMToken(Request $request)
    {
        $client =  User::where('id', Auth::guard('user-api')->user()->id)->first();
        $client->ftoken = 0;
        $client->save();
        return response()->json([
            'message' => 'success',
        ]);
    }
}


