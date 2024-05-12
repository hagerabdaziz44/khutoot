<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LinesController extends Controller
{
    //
    public function get_lines_by_categoryid(Request $request)
    {
        $lines = Line::where('category_id', $request->category_id)->select('id', 'start_time', 'end_time', 'money', 'destination_' . app()->getLocale() . ' as destination', 'route_' . app()->getLocale() . ' as route', 'category_id', 'created_at', 'updated_at')->with(['linestations' => function ($query) {
            $query->select('id', 'line_id', 'bus_id', 'name_' . app()->getLocale() . ' as name', 'time', 'created_at', 'updated_at');
        }])->paginate(8);
        return Response::json(array(
            'status' => 200,
            'lines' => $lines,
        ));
    }
    
}
