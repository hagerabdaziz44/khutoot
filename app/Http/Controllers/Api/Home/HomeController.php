<?php

namespace App\Http\Controllers\Api\Home;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Line;
use App\Models\Bus;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $categories=Category::select('id','icon','name_'.app()->getLocale() .' as name', 'created_at','updated_at')->paginate(8);
        $lines=Line::select('id','start_time', 'end_time', 'money', 'destination_' . app()->getLocale() . ' as destination', 'route_' . app()->getLocale() . ' as route','category_id', 'created_at', 'updated_at')->with(['linestations' => function ($query) {
            $query->select('id', 'line_id', 'bus_id', 'name_' . app()->getLocale() . ' as name','time', 'created_at', 'updated_at'); 
        }])->paginate(8);
        $buses=Bus::select('id','color','number','name_'.app()->getLocale() .' as name', 'created_at', 'updated_at')->paginate(8);
        return Response::json(array(
            'status'=>200,
            'categories'=>$categories,
            'lines'=>$lines,
            'buses'=>$buses
            ));

    }
   

}
