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
                        'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
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
                        'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
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
                        'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
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
                        'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
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
                        'created_at' => Carbon::parse($query->created_at)->format('Y-m-d')
                    ];
                });
            }

        }

        return response()->json($myPR);
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
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PO APPROVED']);
        } else {
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PENDING PRESIDENT APPROVAL']);
        }
        
        return response()->json($request);
    }

    public function DeclinePOManager(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'DECLINED BY MANAGER']);
        return response()->json($request);
    }

    public function ApprovePOPresident(Request $request){

        $price = json_decode(str_replace(['₱',','],'',$request->item_category));

        if($price <= 20000){
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PO APPROVED']);
        } else {
            PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PENDING CEO APPROVAL']);
        }

        return response()->json($request);
    }

    public function DeclinePOPresident(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'DECLINED BY PRESIDENT']);
        return response()->json($request);
    }

    public function ApprovePOCeo(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'PO APPROVED']);
        return response()->json($request);
    }

    public function DeclinePOCeo(Request $request){
        PurchaseOrderList::findOrFail($request->id)->update(['status'=>'DECLINED BY CEO']);
        return response()->json($request);
    }
}
