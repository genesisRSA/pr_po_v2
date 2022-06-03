<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;

use App\Models\CertificationType;
use App\Models\Employee;
use App\Models\EmployeeCertification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\SitePermission;
use App\Models\User;
use App\Models\PaymentTerm;
use App\Models\PlatingProcess;
use App\Models\PurchaseRequestList;
use App\Models\PurchaseOrderList;
use App\Models\SubCategoryItem;
use App\Models\ItemList;
use App\Models\PurchaseRequestItem;
use App\Models\UserPosition;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/carbon', function(){
//     $get = Carbon::now();
//     return $get->toFormattedDateString();
// });
Route::get('/testing', function(){
    return isset(User::where('id',1)->first()->name) ? User::where('id',1)->first()->name : 'Not Available';
});

Route::get('/arr', function(){

    // $start_date = Carbon::parse('2022-04-13')->toDateString();
    // $items = ItemList::pluck('validity_date');

    // $today = Carbon::today()->subMonths(1)->toDateString();

    // $arr = array();
    // foreach($items as $item){
    //     $start_date = Carbon::parse($item->validity_date)->toDateString();

    // }

    // if($start_date >= $today){
    //     return 'true';
    // }

    // $arr = ['can','lor'];

    // $lastThirtyDaysRecord = CertificationType::all()->chunk(3);

    // $array = array();

    // foreach($lastThirtyDaysRecord as $key => $c){
    //         foreach($c as $kk => $lee){
    //                 CertificationType::where('id',$lee->id)->update(['cert_types'=>$arr[$key]]);
    //         }
    // }



//     if(ItemList::findOrFail(21)->validity_date > Carbon::today()->toDateString()){
//         $verdict = 'item not expired';
//     } else {
//         $verdict = 'item expired';
//     }


return PurchaseRequestItem::all()
      ->map(function($query){
          return [
              'po_no' => isset($query->pr_list->id) ? (isset(PurchaseOrderList::withTrashed()->where('pr_id',$query->pr_list->id)->first()->pr_no) ? PurchaseOrderList::withTrashed()->where('pr_id',$query->pr_list->id)->first()->pr_no : 'Not Available') : 'Not Available',
              'po_date' => isset($query->pr_list->id) ? (isset(PurchaseOrderList::withTrashed()->where('pr_id',$query->pr_list->id)->first()->created_at)? PurchaseOrderList::withTrashed()->where('pr_id',$query->pr_list->id)->first()->created_at->toDateTimeString() : 'Not Available'): 'Not Available',
              'pr_no' => isset($query->pr_list->id) ? (isset(PurchaseRequestList::withTrashed()->where('id',$query->pr_list->id)->first()->pr_no) ? PurchaseRequestList::withTrashed()->where('id',$query->pr_list->id)->first()->pr_no : 'Not Available') : 'Not Available',
              'vendor' => isset($query->chosen_supplier) ? ($query->chosen_supplier == 1 ? $query->supplier_one : ($query->chosen_supplier == 2 ? $query->supplier_two : $query->supplier_three ) )  : 'Not Available',
              'item_code' => isset($query->item_due_id) ? ( (isset(ItemList::where('id',$query->item_due_id)->first()->item_code) ? ItemList::where('id',$query->item_due_id)->first()->item_code : null) == null ? 'Not Available' : ItemList::where('id',$query->item_due_id)->first()->item_code) : 'Not Available',
              'item_subcat' => isset($query->item_due_id) ? SubCategoryItem::where('id',ItemList::where('id',$query->item_due_id)->first()->sub_category_item_id)->first()->subcategory_name :
                                'Not Available',
              'item_desc' => $query->part_name.' '.($query->material != '' ? '('.$query->material.')' : '').($query->dimension != '' ? '('.$query->dimension.')' : ''),
              'req_quantity' => $query->quantity,
              'unit_cost' => '₱'.number_format((json_decode(str_replace(['₱',','],'',$query->target_cost)) / $query->quantity),2,'.',','),
              'total_amount' => $query->target_cost,
              'currency' => 'PHP',
              'PR_remarks' => isset($query->pr_list->id) ?
                        (PurchaseRequestList::withTrashed()->where('id',$query->pr_list->id)->first()->remarks==''? 'Not Available' : PurchaseRequestList::withTrashed()->where('id',$query->pr_list->id)->first()->remarks. ' PREPARED BY: '.
                            (isset(User::where('id',PurchaseRequestList::withTrashed()->where('id',$query->pr_list->id)->first()->user_id)->first()->name) ? User::where('id',PurchaseRequestList::withTrashed()->where('id',$query->pr_list->id)->first()->user_id)->first()->name : 'Not Available')) : 'Not Available'
          ];
      });


});


Route::get('/stamp', function(){

    // $list = PurchaseOrderList::find(6);
    // $a = $list->suppliers()->orderBy('purchase_request_item_id','ASC')->get()->groupBy('purchase_request_item_id');
    // $key = array();
    // foreach($a as $k => $am){
    //     foreach($am as $kk => $ap){
    //        $key[$k][$kk] = json_decode(str_replace(',','',mb_substr($ap->supplier_cost,1)));
    //     }
    // }
    // $lou = array();

    // foreach($key as $kk => $l){
    //     $lou[$kk] = array_sum($l);
    // }

    $hasManyThrough =  PurchaseOrderList::all();


    // $arr = array();
    // $first = array();

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

    $all_PO = PurchaseOrderList::all()->toArray();

    $finalResult = array();

    foreach($all_PO as $kkk => $v){
        $finalResult[$kkk] = array_merge($all_PO[$kkk],$res[$kkk]);
    }
    dd($finalResult);

    // $hasMany =  PurchaseOrderList::all();


    // foreach($hasMany as $k => $a){
    //     foreach($a->request_items as $kk => $ap){
    //        $key[$k][$kk] = ($ap->chosen_supplier === '1') ? 'SUPPLIER ONE' : (($ap->chosen_supplier === '2') ? 'SUPPLIER TWO' : 'SUPPLIER THREE' ) ;
    //     }
    // }

});

Route::get('/test', [DashboardController::class, 'testPDF'])->name('testPDF');

// Route::get('/exp', function(){
//     $list = PurchaseRequestList::findOrFail(48);

//     $a = $list->suppliers()->orderBy('purchase_request_item_id','ASC')->get()->groupBy('purchase_request_item_id');


//         $key = array();
//         foreach($a as $k => $am){
//             foreach($am as $kk => $ap){
//                $key[$k][$kk] = str_replace(' ','_',$ap->supplier_no);
//             }
//         }

//         $val = array();
//         foreach($a as $k => $am){
//             foreach($am as $kk => $ap){
//                $val[$k][$kk] = json_decode($ap->is_preferred);
//             }
//         }

//         $result = array();
//         foreach($key as $k => $v){
//             $result[] = array_combine($key[$k],$val[$k]);
//         }


//        $suppItems = PurchaseRequestList::find(48)->pr_items->toArray();
//        $finalResult = array();
//        foreach($suppItems as $kkk => $v){
//            $finalResult[$kkk] = $result[$kkk]?array_merge($suppItems[$kkk],$result[$kkk]) : $suppItems[$kkk];
//        }

//     //    $give = array();
//     //    foreach($a as $aaa){
//     //         $give[] = ['a'=>'a'];
//     //    }

//     //    $aaaaa= array();
//     //    foreach($a as $ke => $all){
//     //         $aaaaa[]= $all;
//     //    }

//     //    $antrum = $list->pr_items()->orderBy('id')->get()->toArray();

//     //    $merge = array_merge($aaaaa, [$antrum]);
//         return $a ;
// });
// Route::get('/send-mail', function () {


//     $notices = EmployeeCertification::where('cert_expiration_date',Carbon::now()->addMonth()->format('Y-m-d'))->get();

//     foreach($notices as $notice){

//         $details = [
//             'title' => 'Expiration Notice',
//             'body' => 'The TNR certificate of '.$notice->employee_name.' '.$notice->employee_id.' will be expired at ' . Carbon::now()->addMonth()->format('Y-m-d')
//         ];

//      Mail::to(Auth::user()->email)->send(new App\Mail\NoticeMail($details));

//     }


//     dd('email sent.');
// });
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/getDepartmentRegister', [DashboardController::class, 'getDepartmentRegister'])->name('getDepartmentRegister');

Route::middleware(['auth:sanctum','isAdmin'])->group(function() {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/rfqs', [DashboardController::class, 'req_for_quotation'])->name('req_for_quotation');
    Route::get('/admin/purchase_request', [DashboardController::class, 'purchase_request'])->name('purch_req');
    Route::get('/admin/purchase_order', [DashboardController::class, 'purch_order'])->name('purch_order');
    Route::get('/admin/data_management', [DashboardController::class, 'data_management'])->name('data_management');
    Route::get('/admin/authorization', [DashboardController::class, 'admin_authorization'])->name('admin_authorization');
    Route::get('/getUserAuthorization',[DashboardController::class, 'getUserAuthorization'])->name('getUserAuthorization');
});

Route::middleware(['auth:sanctum'])->group( function(){
    Route::get('/getUserForNotif', [DashboardController::class, 'getUserForNotif'])->name('getUserForNotif');
    Route::get('/getBadge',[DashboardController::class, 'getBadge'])->name('getBadge');
    Route::get('/seeNotif',[DashboardController::class, 'seeNotif'])->name('seeNotif');
    Route::get('/costing',[DashboardController::class, 'costing'])->name('costing');
    Route::get('/getMyCostinglist', [DashboardController::class, 'getMyCostinglist'])->name('getMyCostinglist');
    Route::get('/getStatuses',[DashboardController::class,'getStatuses'])->name('getStatuses');

    Route::get('/getRandomRFQCode', [DashboardController::class, 'getRandomRFQCode'])->name('getRandomRFQCode');
    Route::get('/submitRFQ', [DashboardController::class, 'submitRFQ'])->name('submitRFQ');
    Route::get('/getSitePermission', [DashboardController::class, 'getSitePermission'])->name('getSitePermission');
    Route::get('/canViewSideBar', [DashboardController::class, 'canViewSideBar'])->name('canView');
    Route::get('/viewCatItem', [DashboardController::class, 'viewCatItem'])->name('viewCatItem');
    Route::get('/getEmpUser', [DashboardController::class, 'getEmpUser'])->name('getEmpUser');
    Route::get('/getAvailableItemList', [DashboardController::class, 'getAvailableItemList'])->name('getAvailableItemList');
    Route::get('/getcat_subcat_ItemList', [DashboardController::class, 'getcat_subcat_ItemList'])->name('getcat_subcat_ItemList');
    Route::get('/selectingCategoryNameList', [DashboardController::class, 'selectingCategoryNameList'])->name('selectingCategoryNameList');
    Route::get('/getcat_subcat_for_add_ItemList', [DashboardController::class, 'getcat_subcat_for_add_ItemList'])->name('getcat_subcat_for_add_ItemList');
    Route::get('/selectingCategoryNameListForAdd', [DashboardController::class, 'selectingCategoryNameListForAdd'])->name('selectingCategoryNameListForAdd');
    Route::get('/getAvailablePlatingProcesses', [DashboardController::class, 'getAvailablePlatingProcesses'])->name('getAvailablePlatingProcesses');
    Route::get('/getAvailableVendor', [DashboardController::class, 'getAvailableVendor'])->name('getAvailableVendor');
    Route::get('/getAvailablePaymentTerm', [DashboardController::class, 'getAvailablePaymentTerm'])->name('getAvailablePaymentTerm');
    Route::get('/getUpdatedPrice', [DashboardController::class, 'getUpdatedPrice'])->name('getUpdatedPrice');
    Route::get('/getUpdatedPriceItemList', [DashboardController::class, 'getUpdatedPriceItemList'])->name('getUpdatedPriceItemList');
    Route::get('/getAvailableDept', [DashboardController::class, 'getAvailableDept'])->name('getAvailableDept');
    Route::get('/getPermissionForDM',[DashboardController::class, 'getPermissionForDM'])->name('getPermissionForDM');
    Route::get('/authUserForSideBar',[DashboardController::class, 'authUserForSideBar'])->name('authUserForSideBar');

    Route::post('/addOrEditUserPermission', [DashboardController::class, 'addOrEditUserPermission'])->name('addOrEditUserPermission');
    Route::post('/deleteUser', [DashboardController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/voidUser', [DashboardController::class, 'voidUser'])->name('voidUser');
    Route::post('/addCategory', [DashboardController::class, 'addCategory'])->name('addCategory');
    Route::post('/addSubCategory', [DashboardController::class, 'addSubCategory'])->name('addSubCategory');
    Route::post('/deleteItemCategory',[DashboardController::class, 'deleteItemCategory'])->name('deleteItemCategory');
    Route::post('/updateSubCategory',[DashboardController::class, 'updateSubCategory'])->name('updateSubCategory');
    Route::post('/updateItemList', [DashboardController::class, 'updateItemList'])->name('updateItemList');
    Route::post('/addItemList', [DashboardController::class, 'addItemList'])->name('addItemList');
    Route::post('/deleteItemList', [DashboardController::class, 'deleteItemList'])->name('deleteItemList');
    Route::post('/addPlatingProcess', [DashboardController::class, 'addPlatingProcess'])->name('addPlatingProcess');
    Route::post('/updatePlatingProcess', [DashboardController::class, 'updatePlatingProcess'])->name('updatePlatingProcess');
    Route::post('/deletePlatingProcess', [DashboardController::class, 'deletePlatingProcess'])->name('deletePlatingProcess');
    Route::post('/addVendor', [DashboardController::class, 'addVendor'])->name('addVendor');
    Route::post('/updateVendor', [DashboardController::class,'updateVendor'])->name('updateVendor');
    Route::post('/deleteVendor', [DashboardController::class, 'deleteVendor'])->name('deleteVendor');
    Route::post('/addPaymentTerm', [DashboardController::class, 'addPaymentTerm'])->name('addPaymentTerm');
    Route::post('/updatePaymentTerm',[DashboardController::class, 'updatePaymentTerm'])->name('updatePaymentTerm');
    Route::post('/deletePaymentTerm', [DashboardController::class, 'deletePaymentTerm'])->name('deletePaymentTerm');
    Route::post('/updateDept', [DashboardController::class, 'updateDept'])->name('updateDept');
    Route::post('/deleteDeptConfirm', [DashboardController::class, 'deleteDeptConfirm'])->name('deleteDeptConfirm');
    Route::post('/addConfirmDept', [DashboardController::class, 'addConfirmDept'])->name('addConfirmDept');

    Route::get('/getUserPositionPR', [PurchaseRequestController::class, 'getUserPositionPR'])->name('getUserPositionPR');

    ///////////Requestor-API//////////////
    Route::get('/getMyPRlistRequestor', [PurchaseRequestController::class, 'getMyPRlist'])->name('getMyPRlist');
    Route::get('/getPRNumberRequestor', [PurchaseRequestController::class, 'getPRNumber'])->name('getPRNumber');
    Route::get('/getSubCatValRequestor',[PurchaseRequestController::class, 'getSubCatVal'])->name('getSubCatVal');
    Route::get('/getPartNameValRequestor',[PurchaseRequestController::class,'getPartNameVal'])->name('getPartNameVal');
    Route::get('/getMaterialValRequestor',[PurchaseRequestController::class, 'getMaterialVal'])->name('getMaterialVal');
    Route::get('/getDimensionValRequestor',[PurchaseRequestController::class, 'getDimensionVal'])->name('getDimensionVal');
    Route::get('/getComputedPRprice',[PurchaseRequestController::class, 'getComputedPRprice'])->name('getComputedPRprice');
    Route::get('/viewPRRequestor',[PurchaseRequestController::class, 'viewPR'])->name('viewPR');
    Route::get('/getItemForSelectByAutocomplete',[PurchaseRequestController::class, 'getItemForSelectByAutocomplete'])->name('getItemForSelectByAutocomplete');
    Route::get('/getItemsBySelectedItemCode', [PurchaseRequestController::class, 'getItemsBySelectedItemCode'])->name('getItemsBySelectedItemCode');
    Route::get('/getPRReport',[PurchaseRequestController::class, 'getPRReport'])->name('getPRReport');

    Route::post('/savePrRequestor',[PurchaseRequestController::class, 'savePr'])->name('savePr');
    Route::post('/deletePrRequestor',[PurchaseRequestController::class, 'deletePr'])->name('deletePr');

    ///////////Buyer-API//////////////
    Route::get('/getSuppInfo', [PurchaseRequestController::class, 'getSuppInfo'])->name('getSuppInfo');
    Route::get('/checkIfNoSupplier', [PurchaseRequestController::class, 'checkIfNoSupplier'])->name('checkIfNoSupplier');

    Route::post('/saveVendor',[PurchaseRequestController::class, 'saveEditedSupp'])->name('saveEditedSupp');
    Route::post('/saveEditedVendor',[PurchaseRequestController::class, 'saveEditedVendor'])->name('saveEditedVendor');
    Route::post('/saveSuppInfo', [PurchaseRequestController::class, 'saveSuppInfo'])->name('saveSuppInfo');

    ///////////Manager////////////////
    Route::post('/getSelectedFinalSupp',[PurchaseRequestController::class, 'getSelectedFinalSupp'])->name('getSelectedFinalSupp');

    //////////President///////////////
    Route::post('/ApprovePRPresident',[PurchaseRequestController::class, 'ApprovePRPresident'])->name('ApprovePRPresident');
    Route::post('/DeclinePRPresident',[PurchaseRequestController::class, 'DeclinePRPresident'])->name('DeclinePRPresident');

    /////////CEO//////////////////////
    Route::post('/ApprovePRCEO',[PurchaseRequestController::class, 'ApprovePRCEO'])->name('ApprovePRCEO');
    Route::post('/DeclinePRCEO',[PurchaseRequestController::class, 'DeclinePRCEO'])->name('DeclinePRCEO');

////////////////////////////////////////////////////////////////Purchase Order////////////////////////////////////////////////////////////////////////////////////////////

    Route::get('/getMyPOlist',[PurchaseOrderController::class, 'getMyPOlist'])->name('getMyPOlist');
    Route::get('/viewPORequestor',[PurchaseOrderController::class, 'viewPO'])->name('viewPO');
    Route::get('/checkIfCanRepeatPR',[PurchaseOrderController::class, 'checkIfCanRepeatPR'])->name('checkIfCanRepeatPR');
    Route::get('/po_report',[PurchaseOrderController::class, 'POReport'])->name('POReport');

    Route::post('/ApprovePOManager',[PurchaseOrderController::class,'ApprovePOManager'])->name('ApprovePOManager');
    Route::post('/DeclinePOManager',[PurchaseOrderController::class,'DeclinePOManager'])->name('DeclinePOManager');

    Route::post('/ApprovePOPresident',[PurchaseOrderController::class,'ApprovePOPresident'])->name('ApprovePOPresident');
    Route::post('/DeclinePOPresident',[PurchaseOrderController::class,'DeclinePOPresident'])->name('DeclinePOPresident');

    Route::post('/ApprovePOCeo', [PurchaseOrderController::class, 'ApprovePOCeo'])->name('ApprovePOCeo');
    Route::post('/DeclinePOCeo',[PurchaseOrderController::class,'DeclinePOCeo'])->name('DeclinePOCeo');

    Route::post('/repeatPRConfirm',[PurchaseOrderController::class,'repeatPRConfirm'])->name('repeatPRConfirm');
});

Route::middleware(['auth:sanctum','isRegularUser'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rfqs', [DashboardController::class, 'req_for_quotation'])->name('req_for_quotation_reg_user');
    Route::get('/purchase_request', [DashboardController::class, 'purchase_request'])->name('purch_req_reg_user');
    Route::get('/purchase_order', [DashboardController::class, 'purch_order'])->name('purch_order_reg_user');
    Route::get('/data_management', [DashboardController::class, 'data_management'])->name('data_management_reg_user');
});

