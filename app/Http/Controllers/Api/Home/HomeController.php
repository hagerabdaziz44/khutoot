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
        $categories=Category::select('id','icon','name_'.app()->getLocale() .' as name')->paginate(8);
        $lines=Line::select('id','time','money','destination','route','category_id')->paginate(8);
        $buses=Bus::select('id','color','number','name_'.app()->getLocale() .' as name')->paginate(8);
        return Response::json(array(
            'status'=>200,
            'categories'=>$categories,
            'lines'=>$lines,
            'buses'=>$buses
            ));

    }
    public function get_lines_by_categoryid(Request $request)
    {
    $lines=Line::where('category_id',$request->categor_id)->select('id','time','money','destination','route','category_id')->paginate(8);
    }
}
