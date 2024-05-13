<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OfferController extends Controller
{
    public function get_offer_by_id($id){
   $offer=Offer::where('id',$id)->first();
        return Response::json(array(
            'data' => $offer,
        ));
    }
}
