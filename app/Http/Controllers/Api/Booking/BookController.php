<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusSeat;
use App\Models\Line;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    //
    public function book(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'line_station_id' => 'required',
            'bus_seat_id' => 'required',
            'line_id' => 'required',
            'bus_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        try {
            DB::beginTransaction();
            $user = Auth::guard('user-api')->user();
            $user_wallet = Wallet::where('user_id', $user->id)->first();
            $amount = Line::where('id', $request->line_id)->first();
            if ($amount->money > $user_wallet->amount) {
                return response()->json(['message' => 'Your balance is insufficient'], 404);
            }
            $qr_code = Str::uuid();
            $booking = Booking::create([
                'user_id' => $user->id,
                'bus_id' => $request->bus_id,
                'line_id' => $request->line_id,
                'bus_seat_id' => $request->bus_seat_id,
                'line_station_id' => $request->line_station_id,
                'qr_code' => $qr_code

            ]);
            BusSeat::where('id', $request->bus_seat_id)->update(['status' => '1']);
            DB::commit();

            return response()->json([
                'bookings'=>$booking,
                'message' => trans('Added successfully'),

            ]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
}
