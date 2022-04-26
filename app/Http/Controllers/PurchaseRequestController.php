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
use App\Models\PurchaseRequestItem;
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
                    'user_id' => User::findOrFail($query->user_id)->name,
                    'pr_no' => strtoupper($query->pr_no),
                    'so_no' => strtoupper($query->so_no),
                    'department' => strtoupper($query->department),
                    'item_category' => $query->item_category,
                    'status' => strtoupper($query->status),
                    'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
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
                        'item_category' => $query->item_category,
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
                'text' => $query->part_name == '' ? 'N/A' : $query->part_name,
                'value' => $query->part_name == '' ? 'N/A' : $query->part_name
            ];
        });

        return response()->json($item);
    }

    public function getMaterialVal(Request $request){

        $item = ItemList::where('category_item_id',$request->cat)
                        ->where('sub_category_item_id',$request->subcat)
                        ->where('part_name',$request->part_name == 'N/A' ? '' : $request->part_name)
                        ->get()
                        ->map( function($query){
                        return [
                        'text' => $query->material == '' ? 'N/A' : $query->material,
                        'value' => $query->material == '' ? 'N/A' : $query->material
                        ];
                        });
        return response()->json($item);
    }

    public function getDimensionVal(Request $request){

        $item = ItemList::where('category_item_id',$request->cat)
                        ->where('sub_category_item_id',$request->subcat)
                        ->where('part_name',$request->part_name == 'N/A' ? '' : $request->part_name)
                        ->where('material',$request->material == 'N/A' ? '' : $request->material)
                        ->get()
        ->map( function($query){
            return [
                'text' => $query->dimension == '' ? 'N/A' : $query->dimension,
                'value' => $query->dimension == '' ? 'N/A' : $query->dimension
            ];
        });
        return response()->json($item);
    }

    public function getComputedPRprice(Request $request){

        $price = ItemList::where('category_item_id',$request->category)
                ->where('sub_category_item_id',$request->subcategory)
                ->where('part_name',$request->part_name == 'N/A' ? '' : $request->part_name)
                ->where('material',$request->material == 'N/A' ? '' : $request->material)
                ->where('dimension',$request->dimension == 'N/A' ? '' : $request->dimension)
                ->first();

        return response()->json(str_replace('₱ ','',$price->unit_price));
    }

    public function savePr(Request $request){

        $detection = 0;

        foreach(PurchaseRequestList::all() as $list){
            if(in_array($request->params['pr_details']['pr_no'],[$list->pr_no])){
                $detection += 1;
            }
        }

        if($detection==0){

            $dept = Department::where('id',$request->params['pr_details']['department'])->first();

            $arr = array();
            foreach($request->params['pr_items'] as $key => $a){
                $arr[] = json_decode(str_replace(',','',mb_substr($a['target_cost'],1)));
            }
            $grand_total = '₱'.number_format(array_sum($arr),2, '.', ',');

            $pr_id = PurchaseRequestList::insertGetId([
                'user_id' => Auth::id(),
                'pr_no' => $request->params['pr_details']['pr_no'],
                'so_no' => $request->params['pr_details']['so_no'],
                'department' => $dept->dept_code,
                'item_category' => $grand_total,
                'status' => 'FOR CANVASSING',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            foreach($request->params['pr_items'] as $key => $b){
                PurchaseRequestItem::create([
                    'purchase_request_list_id' => $pr_id,
                    'part_name' => $b['part_name'] == 'N/A' ? '' : $b['part_name'],
                    'material' => $b['material'] == 'N/A' ? '' : $b['material'],
                    'dimension' => $b['dimension'] == 'N/A' ? '' : $b['dimension'],
                    'quantity' => $b['quantity'],
                    'remarks' => $b['remarks'] == null ? '' : $b['remarks'],
                    'supplier_one' => $b['supplier_one'],
                    'supplier_two' => $b['supplier_two'],
                    'supplier_three' => $b['supplier_three'],
                    'target_cost' => $b['target_cost']
                ]);
            }

            return response()->json();
        } else {
            return response()->json('dupli');
        }
    }

    public function deletePr(Request $request){
        PurchaseRequestList::findOrFail($request->params)->delete();
        return response()->json('Successfully deleted!');
    }

    public function viewPR(Request $request){
        $list = PurchaseRequestList::findOrFail($request[0]);
        return response()->json($list->pr_items);
    }
}
