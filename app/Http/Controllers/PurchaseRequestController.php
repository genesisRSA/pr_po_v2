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

            if($user->user_position->position == 'REQUESTOR'){
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

            if($user->user_position->position == 'CEO'){
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
            }

            if($user->user_position->position == 'PRESIDENT'){
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
            }

            if($user->user_position->position == 'PURCHASE MNGR.'){
                $myPR = PurchaseRequestList::all()->map( function($query){
                    return[
                        'id' => $query->id,
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
            }

        }

        return response()->json($myPR);
    }

    public function getPRNumber(){
        $itemNext = PurchaseRequestList::whereDate('created_at',Carbon::today())->withTrashed()->get()->count() + 1;

        $prNo = 'PR-'.Carbon::parse(Carbon::today())->format('Ymd').'-'.$itemNext;

        $authName = Auth::user()->name;

        $today = Carbon::parse(Carbon::today())->format('Y-m-d');

        $dept = Department::orderBy('dept_code')->get()->map( function($query){
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
                    'target_cost' => $b['target_cost']
                ]);

                SupplierDetails::create([
                    'purchase_request_item_id' => $pr_item_id,
                    'supplier_no' => 'SUPPLIER ONE',
                    'is_preferred' => 0,
                    'supplier_cost' => '',
                    'payment_method' => '',
                    'eta' => ''
                ]);

                SupplierDetails::create([
                    'purchase_request_item_id' => $pr_item_id,
                    'supplier_no' => 'SUPPLIER TWO',
                    'is_preferred' => 0,
                    'supplier_cost' => '',
                    'payment_method' => '',
                    'eta' => ''
                ]);

                SupplierDetails::create([
                    'purchase_request_item_id' => $pr_item_id,
                    'supplier_no' => 'SUPPLIER THREE',
                    'is_preferred' => 0,
                    'supplier_cost' => '',
                    'payment_method' => '',
                    'eta' => ''
                ]);
            }


            return response()->json();
        } else {
            return response()->json('dupli');
        }
    }

    public function deletePr(Request $request){
        PurchaseRequestList::findOrFail($request->params)->delete();
        PurchaseOrderList::where('pr_id',$request->params)->delete();
        return response()->json();
    }

    public function viewPR(Request $request){
        $list = PurchaseRequestList::findOrFail($request[0]);
        $vendors = Vendor::all()->map( function($query){
            return [
                'text' => $query->company_name,
                'value' => $query->company_name,
                'id' => $query->id
            ];
        });

        if($list->status == 'FOR CANVASSING'){
            $canChoose = false;
        } else {
            $canChoose = true;
        }

        $a = $list->suppliers()->orderBy('purchase_request_item_id','ASC')->get()->groupBy('purchase_request_item_id');


            $key = array();
            foreach($a as $k => $am){
                foreach($am as $kk => $ap){
                   $key[$k][$kk] = str_replace(' ','_',$ap->supplier_no);
                }
            }

            $val = array();
            foreach($a as $k => $am){
                foreach($am as $kk => $ap){
                   $val[$k][$kk] = json_decode($ap->is_preferred);
                }
            }

            $result = array();
            foreach($key as $k => $v){
                $result[] = array_combine($key[$k],$val[$k]);
            }


           $suppItems = PurchaseRequestList::find($request[0])->pr_items->toArray();

           $finalResult = array();

           if($result != null){
                foreach($suppItems as $kkk => $v){
                    $finalResult[$kkk] = array_merge($suppItems[$kkk],isset($result[$kkk]) ? $result[$kkk] : ['N/A' => 'N/A']);
                }
           } else {
                    $finalResult = $list->pr_items;
           }

        return response()->json([$finalResult,$vendors,$canChoose]);
    }

    public function saveEditedSupp(Request $request){
        $item = PurchaseRequestItem::where('purchase_request_list_id',$request->params[0]['purchase_request_list_id'])->get();
        foreach($item as $k => $val){
            $item[$k]->update(['supplier_one' => $request->params[$k]['supplier_one'],
                               'supplier_two' => $request->params[$k]['supplier_two'],
                               'supplier_three' => $request->params[$k]['supplier_three'],
                            ]);
        }
        if($item[0]->pr_list->status == 'FOR CANVASSING' || $item[0]->pr_list->status == 'FOR SUPPLIER APPROVAL' ){
            $item[0]->pr_list->update(['status' => 'FOR SUPPLIER APPROVAL']);
        }
        return response()->json($request);
    }

    public function getSuppInfo(Request $request){


        $supp = json_decode($request[0]);


        $getSuppDetails = SupplierDetails::where('purchase_request_item_id',$supp->id)
                                         ->where('supplier_no',$request[1])
                                        ->get()
                                        ->map( function($query){
                                            return [
                                                'id'          => $query->id,
                                                'is_preferred' => json_decode($query->is_preferred),
                                                'supplier_cost' => json_decode(str_replace(',','',mb_substr($query->supplier_cost,1))),
                                                'payment_method' => json_decode($query->payment_method),
                                                'eta' => $query->eta
                                            ];
                                        });

        $payment_term = PaymentTerm::all()
        ->map(function($query) {
            return [
                'text' => $query->payment_term,
                'value' => $query->id
            ];
        });;



       return response()->json([$getSuppDetails,$payment_term]);
    }

    public function saveEditedVendor(Request $request){
       $owl =PurchaseRequestItem::where('id',$request->params['one']['id'])->first();

       $owl->update([
           'supplier_one' => $request->params['two']['supplier_one'] == null ? 'PENDING' : $request->params['two']['supplier_one'],
           'supplier_two' => $request->params['two']['supplier_two']  == null ? 'PENDING' : $request->params['two']['supplier_two'],
           'supplier_three' => $request->params['two']['supplier_three'] == null ? 'PENDING' : $request->params['two']['supplier_three']
       ]);
        return response()->json('success');
    }

    public function saveSuppInfo(Request $request){

        $getSupp = SupplierDetails::where('id',$request->params['id'])->first();

        if($request->params['id'] == null){
            SupplierDetails::create([
            'purchase_request_item_id' => $request->local_supp_id,
            'supplier_no' => $request->supp_no,
            'is_preferred' => $request->params['isPreferred'],
            'supplier_cost' => '₱'.number_format($request->params['supplier_cost'],2, '.', ','),
            'payment_method' => $request->params['payment_method'][0]['value'],
            'eta' => $request->params['eta'],
            ]);
        } else {
            SupplierDetails::where('id',$request->params['id'])->update([
                'purchase_request_item_id' => $getSupp->supp_detail->id,
                'supplier_no' => $request->supp_no,
                'is_preferred' => $request->params['isPreferred'],
                'supplier_cost' => '₱'.number_format($request->params['supplier_cost'],2, '.', ','),
                'payment_method' => $request->params['payment_method'][0]['value'],
                'eta' => $request->params['eta']
            ]);

            $getSupplier = SupplierDetails::findOrFail($request->params['id']);
            $getSupplier->supp_detail()->update(['chosen_supp_cost' => '₱'.number_format($request->params['supplier_cost'],2, '.', ',')]);
        }

        // $getSupp->updateOrCreate([
        //     'id' => $request->params['id']
        // ],[
        //     'purchase_request_item_id' => $getSupp->supp_detail->id,
        //     'supplier_no' => $request->supp_no,
        //     'is_preferred' => $request->params['isPreferred'],
        //     'supplier_cost' => '₱'.number_format($request->params['supplier_cost'],2, '.', ','),
        //     'payment_method' => $request->params['payment_method'][0]['value'],
        //     'eta' => $request->params['eta'],
        // ]);

        return response()->json($request);
    }

    public function getSelectedFinalSupp(Request $request){

         $getSelected = PurchaseRequestItem::findOrFail($request->params['id']);
         $getSupp = SupplierDetails::where('purchase_request_item_id',$request->params['id'])->where('supplier_no',($request->params['chosen_supplier'] === '1') ? 'SUPPLIER ONE' : (($request->params['chosen_supplier'] === '2') ? 'SUPPLIER TWO' : 'SUPPLIER THREE' ))->first();

        if($getSupp != null){
            $getSelected->update(['chosen_supplier' => $request->params['chosen_supplier'],
            'chosen_supp_cost' => $getSupp->supplier_cost]);
        } else {
            $getSelected->update(['chosen_supplier' => $request->params['chosen_supplier']]);
        }

        $items = PurchaseRequestItem::where('purchase_request_list_id',$getSelected->purchase_request_list_id)->get();

        $list = PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id);

        $arr = array();
        foreach($items as $item){
            $arr[] = $item->chosen_supplier;
        }

        $detection = 0;
        if(in_array(null,$arr)){
            $detection += 1;
        }

        if($detection == 0){
            $price = json_decode(str_replace(['₱',','],'',PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id)->item_category));

            if($price <= 10000){
                $list->update(['status'=>'PR APPROVED']);
                PurchaseOrderList::create([
                    'pr_id'=>$list->id,
                    'user_id'=>$list->user_id,
                    'pr_no'=>$list->pr_no,
                    'so_no'=>$list->so_no,
                    'department'=>$list->department,
                    'item_category'=>$list->item_category,
                    'status'=>'PENDING APPROVAL',
                    'created_at'=>Carbon::now()
                ]);
            } else {
                if(PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id)->status=='PR APPROVED' ||
                   PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id)->status=='PENDING PRESIDENTIAL APPROVAL' ||
                   PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id)->status=='PENDING CEO APPROVAL' ||
                   PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id)->status=='PR DECLINED BY CEO' ||
                   PurchaseRequestList::findOrFail($getSelected->purchase_request_list_id)->status=='PR DECLINED BY PRESIDENT' ){
                    return response()->json();
                }
                $list->update(['status'=>'PENDING PRESIDENTIAL APPROVAL']);
            }
        }

        return response()->json($detection);
    }

    public function checkIfNoSupplier(Request $request){

        $obj_count = 0;

        foreach(request()->all() as $req){
             $obj_count += 3;
        }


      $arr = array();
      foreach(request()->all() as $req){
          $arr[] = json_decode($req);
      }

      $count_id = array();
      foreach($arr as $ar){
        $count_id[] = $ar->id;
      }

      $count_item = 0;
      foreach(SupplierDetails::all() as $supp){
        if(in_array($supp->purchase_request_item_id,$count_id)){
            if($supp->supplier_cost != '' || $supp->payment_method != '' || $supp->eta != ''){
                $count_item += 1;
            }
        }
      }

      if($count_item == $obj_count){
            $message = 'Good';
      } else {
            $message = 'Some supplier info is not yet filled!';
      }



        return response()->json($message);
    }

    public function ApprovePRPresident(Request $request){

        $price = json_decode(str_replace(['₱',','],'',$request->item_category));

        if($price > 50000){
            PurchaseRequestList::findOrFail($request->id)->update(['status'=>'PENDING CEO APPROVAL']);
        } else {
            PurchaseRequestList::findOrFail($request->id)->update(['status'=>'PR APPROVED']);

            $list = PurchaseRequestList::findOrFail($request->id);
            PurchaseOrderList::create([
                'pr_id'=>$list->id,
                'user_id'=>$list->user_id,
                'pr_no'=>$list->pr_no,
                'so_no'=>$list->so_no,
                'department'=>$list->department,
                'item_category'=>$list->item_category,
                'status'=>'PENDING APPROVAL',
                'created_at'=>Carbon::now()
            ]);
        }

        return response()->json();
    }

    public function DeclinePRPresident(Request $request){
        PurchaseRequestList::findOrFail($request->id)->update(['status'=>'PR DECLINED BY PRESIDENT']);
        return response()->json($request);
    }

    public function ApprovePRCEO(Request $request){
        PurchaseRequestList::findOrFail($request->id)->update(['status'=>'PR APPROVED']);
        $list = PurchaseRequestList::findOrFail($request->id);
        PurchaseOrderList::create([
            'pr_id'=>$list->id,
            'user_id'=>$list->user_id,
            'pr_no'=>$list->pr_no,
            'so_no'=>$list->so_no,
            'department'=>$list->department,
            'item_category'=>$list->item_category,
            'status'=>'PENDING APPROVAL',
            'created_at'=>Carbon::now()
        ]);
    }
    public function DeclinePRCEO(Request $request){
        PurchaseRequestList::findOrFail($request->id)->update(['status'=>'PR DECLINED BY CEO']);
    }

}
