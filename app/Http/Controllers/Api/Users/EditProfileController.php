<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class EditProfileController extends Controller
{
    public function Editprofile(Request $request)
    {

            $validator = Validator::make($request->all(),[
               'name'=>'nullable',
                'phone'=>'nullable',
                'email' => 'required|unique:patients,email,'.auth('user-api')->user()->id,
            ]
        ,[
            'name.required'=>trans('editProfile.nameRequired'),
            'name.string'=>trans('editProfile.nameString'),
            'phone.required'=>trans('editProfile.phoneRequired'),
            'photo.required'=>trans('editProfile.photoRequired'),
            'photo.image'=>trans('editProfile.photoImage'),
            'email.required'=>trans('editProfile.emailRequired'),
            'email.unique'=>trans('editProfile.emailUnique'),

        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'=>$validator->errors()->first()
            ]);
        }

        try {
            $user=Patient::where('id',Auth::guard('user-api')->user()->id)->first();
            DB::beginTransaction();
                $name=$user->photo;
                
                if($request->hasFile('photo'))
                    {

                    $photo=$request->file('photo');
                    $ext=$photo->getClientOriginalName();
                    $name="user-".uniqid().".$ext";
                    $photo->move(public_path('images/users'),$name);
                    }

                        $users= Patient::where('id',auth('user-api')->user()->id)->update([
                        'email' => $request->email,
                        'name'=>$request->name,
                        'phone'=>$request->phone,
                        'photo'=>$name,
                      
                        ]);
                        DB::commit();
                        return Response::json(array(
                        'message'=>trans('msg.updateSuccess'),
                        ));
                    }
                catch (\Exception $ex) {
                DB::rollback();
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password'=>'required',
            'password'=>'required|min:6|max:100',
            'confirm_password'=>'required|same:password'
        ],[

            'password.required' =>trans('editProfile.passwordRequired'),
            'confirm_password.required'=>trans('editProfile.confirm_passwordRequired'),
            'confirm_password.same'=>trans('editProfile.confirm_passwordSame'),
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message'=>$validator->errors()->first()
            ]);
        }
        $user=Auth::guard('user-api')->user();
        if(Hash::check($request->old_password,$user->password)){
            Patient::findOrfail(Auth::guard('user-api')->user()->id)->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
                'message'=>trans('msg.pwSuccess'),
            ],200);
        }else
        {
            return response()->json([
                'message'=>trans('msg.pwError'),
            ],400);
        }

    }
 
}
