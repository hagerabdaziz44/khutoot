<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\BusSeat;
use App\Models\LineBuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BusesController extends Controller
{
    //
    public function get_buses_by_line_id(Request $request)
    {
       $buses = LineBuses::where('line_id', $request->line_id)->with(['buses' => function ($query) {
            $query->select('id', 'color', 'number', 'name_' . app()->getLocale() . ' as name', 'created_at', 'updated_at');
        }])->get();
        return Response::json(array(
            'data' =>  $buses,
        ));
    }
    public function get_seats_of_bus(Request $request)
    {
        $bus_seates = BusSeat::where('bus_id', $request->bus_id)->with(['buses' => function ($query) {
            $query->select('id', 'color', 'number', 'name_' . app()->getLocale() . ' as name', 'created_at', 'updated_at');
        },'seats'])->get();
        return Response::json(array(
            'data' =>  $bus_seates,
        ));
    }
}
