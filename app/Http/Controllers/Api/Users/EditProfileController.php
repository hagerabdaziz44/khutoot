<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class EditProfileController extends Controller
{
    public function Editprofile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' .Auth::guard('user-api')->user()->id, // Exclude current user's email from unique check
            'phone' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        try {
        $user = Auth::guard('user-api')->user();
        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $ext = $photo->getClientOriginalName();
            $image = "image" . uniqid() . ".$ext";
            $photo->move(public_path('images/users'), $image);
                DB::beginTransaction();
                // Update the user record
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $image, // Use the image variable
                ]);
                DB::commit();
                return response()->json([
                    'message' => trans('auth.update.success'),
                    'user' => $user,
                ]);
            } else{
            DB::beginTransaction();
            // Update the user record
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            DB::commit();
            return response()->json([
                'message' => trans('auth.update.success'),
                'user' => $user,
            ]);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }


}
