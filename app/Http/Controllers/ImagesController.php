<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Certification;
use Carbon\Carbon;

class ImagesController extends Controller
{
    public function delano(Request $request) {
        $certs = Certification::orderBy('id','DESC')->get();
        return response()->json( $certs );
    }
    public function upload_image(Request $request){
        if(count($request->images)){
            foreach($request->images as $image){
                $image->store('images');
            }
            return response()->json([
                'status' => 'Done!'
            ]);
        }
    }
}
