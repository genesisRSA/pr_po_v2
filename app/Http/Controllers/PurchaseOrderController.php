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
                    'user_id' => isset(User::where('id',$query->user_id)->first()->name) ? User::where('id',$query->user_id)->first()->name : 'Unknown',
                    'pr_no' => str_replace('PR','PO',strtoupper($query->pr_no)),
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
                        'pr_no' => str_replace('PR','PO',strtoupper($query->pr_no)),
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
                        'user_id' => isset(User::where('id',$query->user_id)->first()->name) ? User::where('id',$query->user_id)->first()->name : 'Unknown',
                        'pr_no' => str_replace('PR','PO',strtoupper($query->pr_no)),
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
                        'user_id' => isset(User::where('id',$query->user_id)->first()->name) ? User::where('id',$query->user_id)->first()->name : 'Unknown',
                        'pr_no' => str_replace('PR','PO',strtoupper($query->pr_no)),
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
                        'user_id' => isset(User::where('id',$query->user_id)->first()->name) ? User::where('id',$query->user_id)->first()->name : 'Unknown',
                        'pr_no' => str_replace('PR','PO',strtoupper($query->pr_no)),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'lead_time' => (strtoupper($query->status) == 'PO APPROVED' ||  str_contains(strtoupper($query->status),'DECLINED')) ?
                                        (Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        str_replace('before','',Carbon::parse($query->created_at)->diffForHumans($query->pr_approved_date)) :
                                        Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date). ' day(s)' ):
                                        (Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        Carbon::parse($query->created_at)->diffForHumans($query->pr_approved_date). ' (ONGOING)':
                                        Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date). ' day(s) (ONGOING)' ),
                        'created_at' => $query->created_at->toDayDateTimeString()
                    ];
                });
            }

            if($user->user_position->position == 'BUYER'){
                $myPR = PurchaseOrderList::all()->map( function($query){
                    return[
                        'id' => $query->id,
                        'pr_id' => $query->pr_id,
                        'user_id' => isset(User::where('id',$query->user_id)->first()->name) ? User::where('id',$query->user_id)->first()->name : 'Unknown',
                        'pr_no' => str_replace('PR','PO',strtoupper($query->pr_no)),
                        'so_no' => strtoupper($query->so_no),
                        'department' => strtoupper($query->department),
                        'item_category' => $query->item_category,
                        'status' => strtoupper($query->status),
                        'lead_time' => (strtoupper($query->status) == 'PO APPROVED' ||  str_contains(strtoupper($query->status),'DECLINED')) ?
                                        (Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        str_replace('before','',Carbon::parse($query->created_at)->diffForHumans($query->pr_approved_date)) :
                                        Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date). ' day(s)' ):
                                        (Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date) == 0 ?
                                        Carbon::parse($query->created_at)->diffForHumans($query->pr_approved_date). ' (ONGOING)':
                                        Carbon::parse($query->created_at)->diffInDays($query->pr_approved_date). ' day(s) (ONGOING)' ),
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

    public function repeatPRConfirm(Request $request){
        $po = PurchaseOrderList::findOrFail($request->params);
        $list = PurchaseRequestList::where('id',$po->pr_id)->first();

        $itemNext = PurchaseRequestList::whereDate('created_at',Carbon::today())->withTrashed()->get()->count() + 1;
        $prNo = 'PR-'.Carbon::parse(Carbon::today())->format('Ymd').'-'.$itemNext;


        $pr_id = PurchaseRequestList::insertGetId([
            'user_id' => Auth::id(),
            'pr_no' =>  $prNo,
            'so_no' => $po->purchase_request->so_no,
            'department' => $po->purchase_request->department,
            'item_category' => $po->purchase_request->item_category,
            'status' => 'FOR SUPPLIER APPROVAL',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $arrain = array();

        foreach($list->pr_items as $key => $b){
            $pr_item_id = PurchaseRequestItem::insertGetId([
                'purchase_request_list_id' => $pr_id,
                'part_name' => $b['part_name'] == 'N/A' ? '' : $b['part_name'],
                'material' => $b['material'] == 'N/A' ? '' : $b['material'],
                'dimension' => $b['dimension'] == 'N/A' ? '' : $b['dimension'],
                'quantity' => $b['quantity'],
                'remarks' => $b['remarks'] == null ? '' : $b['remarks'],
                'supplier_one' => $b['supplier_one'],
                'supplier_two' => $b['supplier_two'],
                'supplier_three' => $b['supplier_three'],
                'target_cost' => $b['target_cost'],
                'item_due_id' => ItemList::where('part_name',$b['part_name'])
                ->where('material',$b['material'])
                ->where('dimension',$b['dimension'])
                ->first()
                ->id
            ]);

            $arrain[] = $pr_item_id;

        }

        foreach($list->suppliers as $key => $b){
            SupplierDetails::create([
                'purchase_request_item_id' => 999999999,
                'supplier_no' => $b['supplier_no'],
                'is_preferred' => $b['is_preferred'],
                'supplier_cost' => $b['supplier_cost'],
                'payment_method' => $b['payment_method'],
                'eta' => $b['eta'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }


        $lastThirtyDaysRecord = SupplierDetails::where('purchase_request_item_id',999999999)->get()->chunk(3);

        foreach($lastThirtyDaysRecord as $key => $c){
                foreach($c as $kk => $lee){
                    SupplierDetails::where('id',$lee->id)->update(['purchase_request_item_id'=>$arrain[$key]]);
                }
        }




        return response()->json($arrain);
    }

    public function checkIfCanRepeatPR(Request $request){
        $PO = PurchaseOrderList::findOrFail($request[0]);

        $detection = 0;
        $arr = array();
        foreach($PO->request_items as $items){
            if($items->item_due_id != null){
                if(ItemList::findOrFail($items->item_due_id)->validity_date <= Carbon::today()->toDateString()){
                    $detection +=1 ;
                }

            }
        }
        return response()->json($detection);
    }

    public function POReport(Request $request){

        // $requestor = User::findOrFail($pr_no->user_id)->name;
        // $all = PurchaseRequestItem::where('purchase_request_list_id',$request->id)->get();
        // $president = UserPosition::where('position','PRESIDENT')->first()->user->name;
        // $purch_mngr = UserPosition::where('position','PURCHASE MNGR.')->first()->user->name;
        // $data = [
        //     'suppliers' => $all
        // ];
        $getSupp = PurchaseRequestItem::where('purchase_request_list_id',$request->pr_id)->get()->map( function($query){
            return [
                'chosen_supplier' => $query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three),
                'part_name'       => isset($query->part_name) ? $query->part_name : '(No matching part name)',
                'chosen_supp_cost'=> $query->chosen_supp_cost,
                'quantity'        => $query->quantity,
                'company_address' => isset(Vendor::where('company_name',$query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three))->first()->address) ?
                                     Vendor::where('company_name',$query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three))->first()->address : 
                                    '(No matching address)',
                 'contact'        => isset(Vendor::where('company_name',$query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three))->first()->contact_number) ?
                                     Vendor::where('company_name',$query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three))->first()->contact_number : 
                                    '(No matching contact #)',
                 'contact_person' => isset(Vendor::where('company_name',$query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three))->first()->contact_person) ?
                                     Vendor::where('company_name',$query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three))->first()->contact_person : 
                                    '(No matching contact person)',
                 'date_of_order'  => isset(PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->status) ? 
                                     (PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->status == 'PO APPROVED' ? 
                                     PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->pr_approved_date : '(No Date found)' ) : '(No Date found)',
                 'pr_no'          => isset(PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->pr_no) ? 
                                     PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->pr_no : '(No PR # found)',
                 'user_requestor' => isset(User::where('id',PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->user_id)->first()->name) ? 
                                     User::where('id',PurchaseOrderList::where('pr_id',$query->purchase_request_list_id)->first()->user_id)->first()->name : '(User not found)'
            ];
        })->groupBy('chosen_supplier');

        $data = [
            'data' => $getSupp
        ];

        $pdf = PDF::loadView('po_rep',$data)->setPaper('a4','portrait');

        return $pdf->stream('itsolutionstuff.pdf');
    }
}
