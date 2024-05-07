<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forget(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'email' => 'required|email|exists:clients',
        ]
        ,[
            'email.required'=>trans('auth.email.register'),
          
           'email.exists'=>trans('auth.login.exists')
         ]);
        if ($validator->fails()) {
        return response()->json(['message'=>$validator->errors()->first()]);
  
    } 
         
        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $code = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create(['email'=>$request->email,
        'code'=>$code]);
      
        // Mail::to($request->email)->send(new SendCodeResetPassword($code));
      
       

        return response(['message' => trans('passwords.sent')], 200);

    }
}
