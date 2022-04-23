<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use PDF;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Rfq_record;
use App\Models\PurchaseRequestList;
use App\Models\SitePermission;
use App\Models\CategoryItem;
use App\Models\SubCategoryItem;
use App\Models\ItemList;
use App\Models\PlatingProcess;
use App\Models\Vendor;
use App\Models\PaymentTerm;
use App\Models\PlatingCostUpdate;
use App\Models\ItemListCostUpdate;
use App\Models\Department;
use App\Models\UserPosition;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;

class PurchaseRequestController extends Controller
{
    public function getUserPositionPR(){

        $user = User::findOrFail(Auth::id());

        if($user->role_as == 1){

            $position = 'ADMIN';

        } else {

            $position = $user->user_position->position ? $user->user_position->position : 'none';

        }

        return response()->json($position);
    }

    public function getMyPRlist(){

        $user = User::findOrFail(Auth::id());

        $myPR = [];
        if($user->role_as == 1){

            $myPR = PurchaseRequestList::all()->map( function($query){
                return[
                    'id' => $query->id,
                    'user_id' => $query->user_id,
                    'pr_no' => strtoupper($query->pr_no),
                    'so_no' => strtoupper($query->so_no),
                    'department' => strtoupper($query->department),
                    'item_category' => strtoupper($query->item_category),
                    'status' => strtoupper($query->status),
                    'created_at' => $query->created_at
                ];
            });

        } else {

            if($user->user_position->position = 'REQUESTOR'){
                $myPR = $user->purchase_requests->map( function($query){
                    return[
                        'id' => $query->id,
                        'user_id' => $query->user_id,
                        'pr_no' => strtoupper($query->pr_no),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => strtoupper($query->item_category),
                        'status' => strtoupper($query->status),
                        'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
                    ];
                });
            }

            if($user->user_position->position = 'CEO'){

            }

            if($user->user_position->position = 'PRESIDENT'){

            }

            if($user->user_position->position = 'PURCHASE MNGR.'){

            }

            if($user->user_position->position = 'BUYER'){

            }

        }

        return response()->json($myPR);
    }

    public function getPRNumber(){
        $itemNext = PurchaseRequestList::whereDate('created_at',Carbon::today())->withTrashed()->get()->count() + 1;

        $prNo = 'PR-'.Carbon::parse(Carbon::today())->format('Ymd').'-'.$itemNext;

        $authName = Auth::user()->name;

        $today = Carbon::parse(Carbon::today())->format('Y-m-d');

        $dept = Department::all()->map( function($query){
            return [
                'text' => $query->dept_code,
                'value' => $query->id
            ];
        });

        $cat = CategoryItem::all()->map( function($query){
            return [
                'text' => $query->category_name,
                'value' => $query->id
            ];
        });


        return response()->json([$prNo,$authName,$today,$dept,$cat]);
    }

    public function getSubCatVal(Request $request){
        $subcat = SubCategoryItem::where('category_item_id',$request[0])->get()
        ->map( function($query){
            return [
                'text' => $query->subcategory_name,
                'value' => $query->id
            ];
        });
        return response()->json($subcat);
    }

    public function getPartNameVal(Request $request){

        $item = ItemList::where('category_item_id',$request->cat)->where('sub_category_item_id',$request->subcat)->get()
        ->map( function($query){
            return [
                'text' => $query->part_name,
                'value' => $query->part_name
            ];
        });

        return response()->json($item);
    }

    public function getMaterialVal(Request $request){

        $item = ItemList::where('category_item_id',$request->cat)
                        ->where('sub_category_item_id',$request->subcat)
                        ->where('part_name',$request->part_name)
                        ->get()
                        ->map( function($query){
                        return [
                        'text' => $query->material,
                        'value' => $query->material
                        ];
                        });
        return response()->json($item);
    }

    public function getDimensionVal(Request $request){

        $item = ItemList::where('category_item_id',$request->cat)
                        ->where('sub_category_item_id',$request->subcat)
                        ->where('part_name',$request->part_name)
                        ->where('material',$request->material)
                        ->get()
        ->map( function($query){
            return [
                'text' => $query->dimension == '' ? 'N/A' : $query->dimension,
                'value' => $query->dimension == '' ? 'N/A' : $query->dimension
            ];
        });
        return response()->json($item);
    }
}
