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
use App\Models\PurchaseOrderList;
use App\Models\SupplierDetails;
use App\Models\Department;
use App\Models\UserPosition;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use PDO;

class PurchaseOrderController extends Controller
{
    public function getMyPOlist(Request $request){

        $user = User::findOrFail(Auth::id());

        $myPR = [];
        if($user->role_as == 1){

            $myPR = PurchaseOrderList::all()->map( function($query){
                return[
                    'id' => $query->id,
                    'pr_id' => $query->pr_id,
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

            if($user->user_position->position == 'REQUESTOR'){
                $myPR = PurchaseOrderList::where('user_id',Auth::id())->get()->map( function($query){
                    return[
                        'id' => $query->id,
                        'pr_id' => $query->pr_id,
                        'pr_no' => strtoupper($query->pr_no),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'created_at' => $query->created_at->toDayDateTimeString()
                    ];
                });
            }

            if($user->user_position->position == 'CEO'){
                $myPR = PurchaseOrderList::all()->map( function($query){
                    return[
                        'id' => $query->id,
                        'pr_id' => $query->pr_id,
                        'user_id' => User::findOrFail($query->user_id)->name,
                        'pr_no' => strtoupper($query->pr_no),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'created_at' => $query->created_at->toDayDateTimeString()
                    ];
                });
            }

            if($user->user_position->position == 'PRESIDENT'){
                $myPR = PurchaseOrderList::all()->map( function($query){
                    return[
                        'id' => $query->id,
                        'pr_id' => $query->pr_id,
                        'user_id' => User::findOrFail($query->user_id)->name,
                        'pr_no' => strtoupper($query->pr_no),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'created_at' => $query->created_at->toDayDateTimeString()
                    ];
                });
            }

            if($user->user_position->position == 'PURCHASE MNGR.'){
                $myPR = PurchaseOrderList::all()->map( function($query){
                    return[
                        'id' => $query->id,
                        'pr_id' => $query->pr_id,
                        'user_id' => User::findOrFail($query->user_id)  ->name,
                        'pr_no' => strtoupper($query->pr_no),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'lead_time' => (strtoupper($query->status) == 'PO APPROVED' ||  str_contains(strtoupper($query->status),'DECLINED')) ?
                                        (Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        str_replace('before','ago',Carbon::parse($query->purchase_request->created_at)->diffForHumans($query->pr_approved_date)) :
                                        Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date). ' day(s) ago' ):
                                        (Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        Carbon::parse($query->purchase_request->created_at)->diffForHumans($query->pr_approved_date). ' (ONGOING)':
                                        Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date). ' day(s) ago (ONGOING)' ),
                        'created_at' => $query->created_at->toDayDateTimeString()
                    ];
                });
            }

            if($user->user_position->position == 'BUYER'){
                $myPR = PurchaseOrderList::all()->map( function($query){
                    return[
                        'id' => $query->id,
                        'pr_id' => $query->pr_id,
                        'user_id' => User::findOrFail($query->user_id)->name,
                        'pr_no' => strtoupper($query->pr_no),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'lead_time' => (strtoupper($query->status) == 'PO APPROVED' ||  str_contains(strtoupper($query->status),'DECLINED')) ?
                                        (Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        str_replace('before','ago',Carbon::parse($query->purchase_request->created_at)->diffForHumans($query->pr_approved_date)) :
                                        Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date). ' day(s) ago' ):
                                        (Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        Carbon::parse($query->purchase_request->created_at)->diffForHumans($query->pr_approved_date). ' (ONGOING)':
                                        Carbon::parse($query->purchase_request->created_at)->diffInDays($query->pr_approved_date). ' day(s) ago (ONGOING)' ),
                        'created_at' => $query->created_at->toDayDateTimeString()
                    ];
                });
            }

        }

        $hasManyThrough =  PurchaseOrderList::all();
        $arr = array();
        $first = array();

        foreach($hasManyThrough as $k => $a){
            foreach($a->request_items as $kk => $ap){
               $key[$k][$kk] = json_decode(str_replace(',','',mb_substr($ap->chosen_supp_cost,1))) ;
            }
        }

        $lou = array();

        foreach($key as $kk => $l){
            $lou[] = '₱'.number_format(array_sum($l),2, '.', ',');
        }

        $first = array();
        foreach($lou as $kk => $l){
            $first[] = 'actual_cost_supp';
        }

        $res = array();

        foreach($first as $keys => $firsts){
            $res[] = array_combine([$first[$keys]], [$lou[$keys]]);
        }

        $all_PO = $myPR;  ////////////// merge the PO list//////////////////

        $finalResult = array();

        foreach($all_PO as $kkk => $v){
            $finalResult[$kkk] = array_merge($all_PO[$kkk],$res[$kkk]);
        }

        return response()->json($finalResult);
    }

    public function viewPO(Request $request){
        $list = PurchaseRequestList::findOrFail($request->pr_id);
        // $vendors = Vendor::all()->map( function($query){
        //     return [
        //         'text' => $query->company_name,
        //         'value' => $query->company_name,
        //         'id' => $query->id
        //     ];
        // });

        // if($list->status == 'FOR CANVASSING'){
        //     $canChoose = false;
        // } else {
        //     $canChoose = true;
        // }
        return response()->json($list->pr_items);
    }

    public function ApprovePOManager(Request $request){

        $price = json_decode(str_replace(['₱',','],'',$request->item_category));

        if($price <= 10000){
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PO APPROVED','pr_approved_date'=>Carbon::now()]);
        } else {
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PENDING PRESIDENT APPROVAL']);
        }

        return response()->json($request);
    }

    public function DeclinePOManager(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'DECLINED BY MANAGER','pr_approved_date'=>Carbon::now()]);
        return response()->json($request);
    }

    public function ApprovePOPresident(Request $request){

        $price = json_decode(str_replace(['₱',','],'',$request->item_category));

        if($price <= 50000){
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PO APPROVED','pr_approved_date'=>Carbon::now()]);
        } else {
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PENDING CEO APPROVAL']);
        }

        return response()->json($request);
    }

    public function DeclinePOPresident(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'DECLINED BY PRESIDENT','pr_approved_date'=>Carbon::now()]);
        return response()->json($request);
    }

    public function ApprovePOCeo(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PO APPROVED','pr_approved_date'=>Carbon::now()]);
        return response()->json($request);
    }

    public function DeclinePOCeo(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'DECLINED BY CEO','pr_approved_date'=>Carbon::now()]);
        return response()->json($request);
    }
}