<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

use App\Models\CertificationType;
use App\Models\Employee;
use App\Models\EmployeeCertification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\SitePermission;
use App\Models\User;
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

    $user = User::find(2);

    $as = $user->permission()->where('module','RFQ')->update(['permission'=>'true,true,true,true']);
    dd($as);
});


Route::get('/send-mail', function () {


    $notices = EmployeeCertification::where('cert_expiration_date',Carbon::now()->addMonth()->format('Y-m-d'))->get();
   
    foreach($notices as $notice){

        $details = [
            'title' => 'Expiration Notice',
            'body' => 'The TNR certificate of '.$notice->employee_name.' '.$notice->employee_id.' will be expired at ' . Carbon::now()->addMonth()->format('Y-m-d')
        ];
       
     Mail::to(Auth::user()->email)->send(new App\Mail\NoticeMail($details));

    }

   
    dd('email sent.');
});

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

Route::get('/getRandomRFQCode', [DashboardController::class, 'getRandomRFQCode'])->name('getRandomRFQCode');
Route::get('/submitRFQ', [DashboardController::class, 'submitRFQ'])->name('submitRFQ');
Route::get('/getSitePermission', [DashboardController::class, 'getSitePermission'])->name('getSitePermission');
Route::get('/canViewSideBar', [DashboardController::class, 'canViewSideBar'])->name('canView');
Route::get('/viewCatItem', [DashboardController::class, 'viewCatItem'])->name('viewCatItem');
Route::get('/getEmpUser', [DashboardController::class, 'getEmpUser'])->name('getEmpUser');

Route::post('/addOrEditUserPermission', [DashboardController::class, 'addOrEditUserPermission'])->name('addOrEditUserPermission');
Route::post('/deleteUser', [DashboardController::class, 'deleteUser'])->name('deleteUser');
Route::post('/voidUser', [DashboardController::class, 'voidUser'])->name('voidUser');
Route::post('/addCategory', [DashboardController::class, 'addCategory'])->name('addCategory');
Route::post('/addSubCategory', [DashboardController::class, 'addSubCategory'])->name('addSubCategory');
Route::post('/deleteItemCategory',[DashboardController::class, 'deleteItemCategory'])->name('deleteItemCategory');
Route::post('/updateSubCategory',[DashboardController::class, 'updateSubCategory'])->name('updateSubCategory');


Route::middleware(['auth:sanctum','isRegularUser'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rfqs', [DashboardController::class, 'req_for_quotation'])->name('req_for_quotation_reg_user');
    Route::get('/purchase_request', [DashboardController::class, 'purchase_request'])->name('purch_req_reg_user');
    Route::get('/purchase_order', [DashboardController::class, 'purch_order'])->name('purch_order_reg_user');
    Route::get('/data_management', [DashboardController::class, 'data_management'])->name('data_management_reg_user');
});

