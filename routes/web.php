<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PurchaseRequestController;

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
Route::get('/test', function(){

    PurchaseRequestList::find(1)->delete();
    return 'deleted successfully!';
    // $item = PaymentTerm::find(1);
    // $item->update([
    //     'payment_term' => 'CASH ON DELIVERY'
    // ]);
    // dd($item->getChanges('payment_term'));
});


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

    Route::post('/savePrRequestor',[PurchaseRequestController::class, 'savePr'])->name('savePr');
    Route::post('/deletePrRequestor',[PurchaseRequestController::class, 'deletePr'])->name('deletePr');
});

Route::middleware(['auth:sanctum','isRegularUser'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rfqs', [DashboardController::class, 'req_for_quotation'])->name('req_for_quotation_reg_user');
    Route::get('/purchase_request', [DashboardController::class, 'purchase_request'])->name('purch_req_reg_user');
    Route::get('/purchase_order', [DashboardController::class, 'purch_order'])->name('purch_order_reg_user');
    Route::get('/data_management', [DashboardController::class, 'data_management'])->name('data_management_reg_user');
});

