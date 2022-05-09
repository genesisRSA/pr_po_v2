<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Rfq_record;
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
use PDF;
use Auth;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */

    public function testPDF(Request $request){

        $pdf = PDF::loadView('test')->setPaper('a4','portrait');

        return $pdf->download('itsolutionstuff.pdf');
    }
    public function index() {

        return Inertia::render('Dashboard');
    }
    public function req_for_quotation() {

        if(Auth::user()->role_as == '1'){
            return Inertia::render('Rfq');
        }
        else{
            $canView = SitePermission::where('requestor',Auth::id())->where('module','RFQ')->first();
            if($canView){
                $getCanView = explode(',',$canView->permission);
                if($getCanView[0]=="true"){
                    return Inertia::render('Rfq');
                }
                return back();
            }
            else{
                return back();
            }
        }
    }
    public function purchase_request() {
        if(Auth::user()->role_as == '1'){
            return Inertia::render('PurchaseRequest');
        }
        else{
            $canView = SitePermission::where('requestor',Auth::id())->where('module','PR')->first();
            if($canView){
                $getCanView = explode(',',$canView->permission);
                if($getCanView[0]=="true"){
                    return Inertia::render('PurchaseRequest');
                }
                return back();
            }
            else{
                return back();
            }
        }
    }
    public function purch_order() {
        if(Auth::user()->role_as == '1'){
            return Inertia::render('PurchaseOrder');
        }
        else{
            $canView = SitePermission::where('requestor',Auth::id())->where('module','PO')->first();
            if($canView){
                $getCanView = explode(',',$canView->permission);
                if($getCanView[0]=="true"){
                    return Inertia::render('PurchaseOrder');
                }
                return back();
            }
            else{
                return back();
            }
        }
    }

    public function data_management() {
        if(Auth::user()->role_as == '1'){
            return Inertia::render('DataManagement');
        }
        else{
            $canView = SitePermission::where('requestor',Auth::id())->where('module','DM')->first();
            if($canView){
                $getCanView = explode(',',$canView->permission);
                if($getCanView[0]=="true"){
                    return Inertia::render('DataManagement');
                }
                return back();
            }
            else{
                return back();
            }
        }
    }

    public function getRandomRFQCode() {
        $randomCode = 'RSA-RFQ' . hexdec(uniqid());
        return response()->json($randomCode);
    }

    public function submitRFQ(Request $request) {
        $arr = array();

        foreach(request()->all() as $req){
            $arr[] = json_decode($req);

        }

        foreach($arr as $a){
            Rfq_record::create(['pr_code'=>$a->pr_code,
                                'item_code'=>$a->item_code,
                                'description'=>$a->description ? $a->description : 'N/A',
                                'quantity'=>$a->quantity,
                                 'unit_of_measure'=>$a->unit_of_measure,
                                 'delivery_date'=>$a->delivery_date
        ]);
        }

        $all = Rfq_record::latest()->take(count($arr))->get();
        $data = [
            'title' => $all[0]->pr_code,
            'date' => date('m/d/Y'),
            'rfqs' => $all
        ];

        $pdf = PDF::loadView('rfq', $data);

        return $pdf->download('RFQ.pdf');


    }

    public function getSitePermission(){
        if(Auth::user()->role_as == '1')
        {
            return response()->json('has permission');
        }
        else
        {
           return response()->json(Auth::user()->permission->count() > 0 ? 'has permission' : 'has no permission');
        }
    }

    public function admin_authorization(){
        return Inertia::render('Authorization');
    }

    public function getEmpUser(){
       $getEmp =  User::all()->map( function($query){
           return [
               'name' => $query->name,
               'emp_id' => $query->emp_id,
               'email' => $query->email,
               'created_at' => Carbon::parse($query->created_at)->format('Y-m-d'),
               'updated_at' => Carbon::parse($query->updated_at)->format('Y-m-d'),
               'role_as' => $query->role_as,
               'position' => $query->user_position
           ];
       })->toArray();
       return response()->json([$getEmp]);
    }

    public function getUserAuthorization(Request $request){
        $user = User::where('email',$request->email)->first();
        $user_pos = $user->user_position;
        $modules = [
                        ['view_rfq','add_rfq','update_rfq','delete_rfq'],
                        ['view_pr','add_pr','update_pr','delete_pr'],
                        ['view_po','add_po','update_po','delete_po'],
                        ['view_dm','add_dm','update_dm','delete_dm'],
                   ];
        $arr = array();
        foreach($user->permission as $perm){
            $arr[] = explode(',',$perm->permission);
        }


        $result = array();
        foreach($crow as $k => $v){
            $result[$k] = array_combine($modules[$k],$crow[$k]);
        }


        return response()->json([$result ? $result : 'none', $user_pos ? $user_pos->position : '']);
    }

    public function addOrEditUserPermission(Request $request){
        $user = User::where('email',$request['params']['email'])->first();

        $isCEO = 0;
        $isPresident = 0;
        $isPurchaseMNGR = 0;

        if($request['params']['selected'] == 'CEO'){
            if(UserPosition::where('position','CEO')->count() > 0){
                return response()->json();
            }
        }
        if($request['params']['selected'] == 'PRESIDENT'){
            if(UserPosition::where('position','PRESIDENT')->count() > 0){
                return response()->json();
            }
        }
        if($request['params']['selected'] == 'PURCHASE MNGR.'){
            if(UserPosition::where('position','PURCHASE MNGR.')->count() > 0){
                return response()->json();
            }
        }

            UserPosition::updateOrCreate([
                'user_id' => $user->id
            ],[
                'position' => $request['params']['selected']
            ]);



        $collection = array();
        foreach($request->params['chk'] as $key => $chk){
            foreach($chk as $kk => $el){
                $collection[$key][$kk] = json_encode($el);
            }
        }

        $val = array();
        foreach($collection as $v){
            $val[] = implode(',',array_values($v));
        }

        if($user->permission->count() > 0){

           $user->permission()->where('module','RFQ')->update(['permission'=>$val[0]]);
           $user->permission()->where('module','PR')->update(['permission'=>$val[1]]);
           $user->permission()->where('module','PO')->update(['permission'=>$val[2]]);
           $user->permission()->where('module','DM')->update(['permission'=>$val[3]]);

           if($val[0] =='false,false,false,false' && $val[1] =='false,false,false,false' && $val[2] =='false,false,false,false' && $val[3] =='false,false,false,false'){
            $user = User::where('email',$request['params']['email'])->first();
            $user->permission()->delete();
           }

        } else {

            $user->permission()->create(['module'=>'RFQ', 'permission'=>$val[0]]);
            $user->permission()->create(['module'=>'PR', 'permission'=>$val[1]]);
            $user->permission()->create(['module'=>'PO', 'permission'=>$val[2]]);
            $user->permission()->create(['module'=>'DM', 'permission'=>$val[3]]);

            if($val[0] =='false,false,false,false' && $val[1] =='false,false,false,false' && $val[2] =='false,false,false,false' && $val[3] =='false,false,false,false'){
             $user = User::where('email',$request['params']['email'])->first();
             $user->permission()->delete();
            }
        }

        return response()->json($request);
    }

    public function deleteUser(Request $request){
        $user = User::where('email',$request->email)->first();

        if($user->permission->count() > 0){
            $user->permission()->delete();
        }

        if($user->user_position->count() > 0){
            $user->user_position()->delete();
        }

        $user->delete();

        return response()->json('deleted successfully!');
    }

    public function voidUser(Request $request){
        $user = User::where('email',$request->user)->first();
        $user->permission()->delete();
        $user->user_position()->delete();
        return response()->json('user permission has been voided successfully.');
    }

    public function canViewSideBar(){
        if(Auth::user()->role_as == '1'){
             return response()->json(['true','true','true','true']);

        } else {

             $user = User::find(Auth::id())->permission;

             if($user->count() > 0)
             {
                $arr = array();
                foreach($user as $val){
                       $arr[] = $val->permission;
                }
                $col = array();
                foreach($arr as $data){
                   $col[] = explode(',',$data);
                }


                return response()->json([$col[0][0],
                                         $col[1][0],
                                         $col[2][0],
                                         $col[3][0]]);
             }
             else{
                return response()->json(['false','false','false','false']);
             }
        }
    }

    public function addCategory(Request $request){

        $detection = 0;

        foreach(CategoryItem::all() as $cat){
            if(in_array(ucwords($request->cat_name), [$cat->category_name] ) ){
                $detection += 1;
            }
        }
        CategoryItem::updateOrCreate(
            ['category_name'=>ucwords($request->cat_name)],
            ['category_name'=>ucwords($request->cat_name)]
        );

        return response()->json($detection);
    }

    public function viewCatItem(){
        $getCatItem =  CategoryItem::orderBy('category_name','ASC')->get()->map( function($query){
            return [
                'value' => $query->id,
                'text' => $query->category_name,
            ];
        })->toArray();

        $hasSubCatItem =  SubCategoryItem::all()->map( function($query){
            return [
                'category_name' => $query->category_item->category_name,
                'subcategory_name' => $query->subcategory_name
            ];
        })->toArray();
        $noSubcat = CategoryItem::has('subcategory_items', '0')->get()->map( function($query){
            return[
                'category_name' => $query->category_name,
                'subcategory_name' => 'N/A'
            ];
        })->toArray();


        return response()->json([$getCatItem,array_merge($noSubcat,$hasSubCatItem)]);
    }

    public function addSubCategory(Request $request){
        $detection = 0;
        $catval = CategoryItem::findOrFail($request->cat_name);

        $arr = array();
        foreach($catval->subcategory_items->pluck('subcategory_name')->toArray() as $a){
            $arr[] = strtolower($a);
        }

            if(in_array(strtolower($request->subcat_name), $arr)){
                $detection += 1;
            }



        $catval->subcategory_items()->updateOrCreate(['subcategory_name'=>ucwords($request->subcat_name)],['subcategory_name'=>ucwords($request->subcat_name)]);

        return response()->json($detection);
    }

    public function deleteItemCategory(Request $request){

        $category = CategoryItem::where('category_name',$request->params['category_name'])->first();

        if($request->params['subcategory_name'] == "N/A"){
            $category->delete();
        } else {
           $id = $category->subcategory_items()->where('subcategory_name',$request->params['subcategory_name'])->first();
           ItemList::where('sub_category_item_id',$id->id)->delete();
           $id->delete();
        }

        return response()->json('deleting success');
    }

    public function updateSubCategory(Request $request){

        $detection = 0;
        if($request->selected_val['subcategory_name'] == 'N/A'){
            $cat_item = CategoryItem::all()->pluck('category_name')->toArray();

            if(in_array(ucwords($request->updated_val['category_name']),$cat_item)){
                $detection += 1;
            } else {
                CategoryItem::where('category_name',$request->selected_val['category_name'])->update(['category_name'=>ucwords($request->updated_val['category_name'])]);
            }

        } else {
            $cat_item = CategoryItem::where('category_name',$request->selected_val['category_name'])->first()->subcategory_items->pluck('subcategory_name')->toArray();
            if(in_array(ucwords($request->updated_val['subcategory_name']),$cat_item)){
                $detection += 1;
            } else {
                $how = CategoryItem::where('category_name',ucwords($request->selected_val['category_name']))->first();
                $how->subcategory_items->where('subcategory_name',ucwords($request->selected_val['subcategory_name']))->first()->update(['subcategory_name'=>ucwords($request->updated_val['subcategory_name'])]);
            }

            $categ= CategoryItem::all()->pluck('category_name')->toArray();

            if(in_array(ucwords($request->updated_val['category_name']),$categ)){
                $detection += 1;
            } else {
                CategoryItem::where('category_name',$request->selected_val['category_name'])->update(['category_name'=>ucwords($request->updated_val['category_name'])]);
            }
        }
        return response()->json($detection);
    }

    public function getAvailableItemList(){
        $allItems = ItemList::all()->map( function($query){
            return [
                'id' => $query->id,
                'category_name' => $query->category_item->category_name,
                'subcategory_name' => $query->subcategory_item->subcategory_name,
                'part_name' => $query->part_name == '' ? 'N/A' : $query->part_name,
                'material' => $query->material == '' ? 'N/A' : $query->material,
                'dimension' => $query->dimension == '' ? 'N/A' : $query->dimension,
                'cat_val' => ['text'=>$query->category_item->category_name,'value'=>$query->category_item_id],
                'subcat_val' => ['text'=>$query->subcategory_item->subcategory_name,'value'=>$query->sub_category_item_id],
                'unit_price' => $query->unit_price == '' ? 'N/A' : $query->unit_price,
                'raw_unit_price' => str_replace(',','',mb_substr($query->unit_price,2))
            ];
        });
        return response()->json($allItems);
    }

    public function getcat_subcat_ItemList(Request $request){
        $cat_id = json_decode($request->cat_val);
        $cat_val = CategoryItem::all()->map(function ($query){
            return [
                'text' => $query->category_name,
                'value' => $query->id
            ];
        });
        $subcat_val = SubCategoryItem::where('category_item_id',$cat_id->value)->get()
        ->map( function($query){
            return [
                'text' => $query->subcategory_name,
                'value' => $query->id
            ];
        });


        return response()->json([$cat_val,$subcat_val]);
    }

    public function selectingCategoryNameList(Request $request){
        $cat_val = $request->cat_val;

        $sub_cat = SubCategoryItem::where('category_item_id',$cat_val)->get()
        ->map( function($query){
            return [
                'text' => $query->subcategory_name,
                'value' => $query->id
            ];
        });

        return response()->json($sub_cat);
    }

    public function updateItemList(Request $request){

        $cat_val = isset($request->params['cat_val']['value']) ? $request->params['cat_val']['value'] : $request->params['cat_val'];
        $subcat_val = isset($request->params['subcat_val']['value']) ? $request->params['subcat_val']['value'] : $request->params['subcat_val'];
        $unit_price = '₱ '.number_format($request->params['raw_unit_price'],2, '.', ',');

        $detection = 0;

        foreach (ItemList::all() as $item) {
            if (in_array(strtolower($request->params['part_name']), [strtolower($item->part_name)]) &&
               in_array(strtolower($request->params['material']), [strtolower($item->material)]) &&
               in_array(strtolower($request->params['dimension']), [strtolower($item->dimension)]) &&
               in_array(strtolower($cat_val), [strtolower($item->category_item_id)]) &&
               in_array(strtolower($subcat_val), [strtolower($item->sub_category_item_id)]) ) {
                $detection += 1;
            }
        }

        if($detection == 0){
            if($unit_price != ItemList::findOrFail($request->params['id'])->unit_price){
                ItemListCostUpdate::create([
                    'item_list_id' => $request->params['id'],
                    'updated_cost' => $unit_price
                ]);
            }
            ItemList::where('id',$request->params['id'])->update([
                'category_item_id' => $cat_val,
                'sub_category_item_id' => $subcat_val,
                'part_name' => $request->params['part_name'] == null ? '' : $request->params['part_name'],
                'material' => $request->params['material'] == null ? '' : $request->params['material'],
                'dimension' => $request->params['dimension'] == null ? '' : $request->params['dimension'],
                'unit_price' => $unit_price == null? '' : $unit_price
            ]);
        }


        return response()->json($detection);
    }

    public function addItemList(Request $request){

        $detection = 0;

        $price = '₱ '.number_format($request->unit_price,2, '.', ',');

        foreach (ItemList::all() as $item) {
            if (in_array(strtolower($request->partname_val), [strtolower($item->part_name)]) &&
               in_array(strtolower($request->material_val), [strtolower($item->material)]) &&
               in_array(strtolower($request->dimension), [strtolower($item->dimension)]) &&
               in_array(strtolower($request->cat_val), [strtolower($item->category_item_id)]) &&
               in_array(strtolower($request->subcat_val), [strtolower($item->sub_category_item_id)]) ) {
                $detection += 1;
            }
        }

            if($detection == 0){
                  $id =  ItemList::insertGetId([
                    'category_item_id' => $request->cat_val,
                    'sub_category_item_id' => $request->subcat_val,
                    'part_name' => $request->partname_val == null || $request->partname_val == 'N/A' ? '' : $request->partname_val,
                    'material' => $request->material_val == null || $request->material_val == 'N/A' ? '' : $request->material_val,
                    'dimension' => $request->dimension == null || $request->material_val == 'N/A' ? '' : $request->dimension,
                    'unit_price' => $price
                ]);

                ItemListCostUpdate::create([
                    'item_list_id' => $id,
                    'updated_cost' => $price
                ]);
            }


        return response()->json($detection);
    }

    public function getcat_subcat_for_add_ItemList(){
        $cat_val = CategoryItem::all()->map(function ($query){
            return [
                'text' => $query->category_name,
                'value' => $query->id
            ];
        });
        return response()->json($cat_val);
    }

    public function selectingCategoryNameListForAdd(Request $request){
        $subcat = SubCategoryItem::where('category_item_id', $request[0])->get()
        ->map(function($query) {
            return [
                'text' => $query->subcategory_name,
                'value' => $query->id
            ];
        });
        return response()->json($subcat);
    }

    public function deleteItemList(Request $request){
        ItemListCostUpdate::where('item_list_id',$request->params)->delete();
        ItemList::findOrFail($request->params)->delete();
        return response()->json();
    }

    public function getAvailablePlatingProcesses(){

        $all = PlatingProcess::all()
        ->map( function($query){
            return [
                'id' => $query->id,
                'plating_process' => $query->plating_process,
                'type' => $query->type == '' ? 'N/A' : $query->type,
                'price_per_square_inch' => $query->price_per_square_inch,
                'raw_price' => str_replace(',','',mb_substr($query->price_per_square_inch,2))
            ];
        });
        return response()->json($all);
    }

    public function addPlatingProcess(Request $request){
        $price = '₱ '.number_format($request->params['price_per_square_inch'],2, '.', ',');

        $detection = 0;

        foreach (PlatingProcess::all() as $item) {
            if (in_array(strtoupper($request->params['plating_process']), [strtoupper($item->plating_process)]) &&
                in_array($price, [$item->price_per_square_inch]) &&
                in_array(strtoupper($request->params['type']), [strtoupper($item->type)])) {
                $detection += 1;
            }
                if (in_array(strtoupper($request->params['plating_process']), [strtoupper($item->plating_process)]) &&
                in_array(strtoupper($request->params['type']), [strtoupper($item->type)])) {
                $detection += 1;
            }
        }

        if($detection==0){

           $id = PlatingProcess::insertGetId([
                'plating_process' => strtoupper($request->params['plating_process']),
                'type'            => $request->params['type'] == null ? '' : strtoupper($request->params['type']),
                'price_per_square_inch' => $price
            ]);

           PlatingCostUpdate::create([
               'plating_process_item_id' => $id,
               'updated_cost' => $price
           ]);


        }


        return response()->json(  $detection );
    }

    public function updatePlatingProcess(Request $request){

        $detection = 0;
        $price = '₱ '.number_format($request->params['raw_price'],2, '.', ',');

        foreach (PlatingProcess::all() as $item) {
            if (in_array(strtoupper($request->params['plating_process']), [strtoupper($item->plating_process)]) &&
                in_array($price, [$item->price_per_square_inch]) &&
                in_array(strtoupper($request->params['type']), [strtoupper($item->type)])) {
                $detection += 1;
            }
            if (in_array(strtoupper($request->params['plating_process']), [strtoupper($item->plating_process)]) &&
            in_array(strtoupper($request->params['type']), [strtoupper($item->type)])  && $request->params['id'] != $item->id) {
            $detection += 1;
        }

        }

        if($detection==0){

            if($price != PlatingProcess::findOrFail($request->params['id'])->price_per_square_inch){
                PlatingCostUpdate::create([
                    'plating_process_item_id' => $request->params['id'],
                    'updated_cost' => $price
                ]);
            }

            PlatingProcess::findOrFail($request->params['id'])->update([
                'plating_process' => strtoupper($request->params['plating_process']),
                'type' => $request->params['type'] == null ? '' : strtoupper($request->params['type']),
                'price_per_square_inch' => $price
            ]);

        }

        return response()->json($detection);
    }

    public function deletePlatingProcess(Request $request){
        PlatingCostUpdate::where('plating_process_item_id',$request->params)->delete();
        PlatingProcess::findOrFail($request->params)
        ->delete();
       return response()->json('success');
    }

    public function getAvailableVendor(){
        return response()->json(Vendor::all());
    }

    public function addVendor(Request $request){

        $detection = 0;

        foreach (Vendor::all() as $item) {
            if ((in_array(strtoupper($request->params['company_name']), [strtoupper($item->company_name)]) &&
                in_array(strtoupper($request->params['address']), [strtoupper($item->address)]) &&
                in_array(strtoupper($request->params['contact_person']), [strtoupper($item->contact_person)]) &&
                in_array(strtoupper($request->params['contact_number']), [strtoupper($item->contact_number)])
            ) || (in_array(strtoupper($request->params['company_name']), [strtoupper($item->company_name)]))) {
                $detection += 1;
            }
        }

        if($detection==0){
            Vendor::create([
                'company_name'=>strtoupper($request->params['company_name']),
                'address'=>strtoupper($request->params['address']),
                'contact_person'=>strtoupper($request->params['contact_person']),
                'contact_number'=>strtoupper($request->params['contact_number'])
            ]);
        }
        return response()->json($detection);
    }

    public function updateVendor(Request $request){
        $detection = 0;

        foreach (Vendor::all() as $item) {
            if (in_array(strtoupper($request->params['company_name']), [strtoupper($item->company_name)]) &&
                in_array(strtoupper($request->params['address']), [strtoupper($item->address)]) &&
                in_array(strtoupper($request->params['contact_person']), [strtoupper($item->contact_person)]) &&
                in_array(strtoupper($request->params['contact_number']), [strtoupper($item->contact_number)])) {
                $detection += 1;
            }
        }

        if($detection==0){
            Vendor::where('id',$request->params['id'])->update([
                'company_name'=>strtoupper($request->params['company_name']),
                'address'=>strtoupper($request->params['address']),
                'contact_person'=>strtoupper($request->params['contact_person']),
                'contact_number'=>strtoupper($request->params['contact_number'])
            ]);
        }

        return response()->json($detection);
    }

    public function deleteVendor(Request $request){
        Vendor::findOrFail($request->params)->delete();
        return response()->json('successfully deleted!');
    }

    public function getAvailablePaymentTerm(){
        $paymentTerm = PaymentTerm::all()
        ->map(function($query){
            return [
                'id' => $query->id,
                'payment_term' => $query->payment_term
            ];
        });
        return response()->json($paymentTerm);
    }

    public function addPaymentTerm(Request $request){

        $detection = 0;

        foreach (PaymentTerm::all() as $item) {
            if (in_array(strtoupper($request->params),
                        [strtoupper($item->payment_term)]) ) {
                $detection += 1;
            }
        }

        if($detection == 0){
            PaymentTerm::create([
                'payment_term' => strtoupper($request->params)
            ]);
        }
        return response()->json($detection);
    }

    public function updatePaymentTerm(Request $request){

        $detection = 0;

        foreach (PaymentTerm::all() as $item) {
            if (in_array(strtoupper($request->params['payment_type']),
                        [strtoupper($item->payment_term)]) ) {
                $detection += 1;
            }
        }

        if($detection==0){
            PaymentTerm::where('id',$request->params['id'])
            ->update([
                'payment_term' => strtoupper($request->params['payment_type'])
            ]);
        }

        return response()->json($detection);
    }

    public function deletePaymentTerm(Request $request){
        PaymentTerm::findOrFail($request->params)->delete();
        return response()->json();
    }

    public function getUpdatedPrice(Request $request){
        $getUpdatedCost = PlatingProcess::where('id',$request[0])->first();

        $format = $getUpdatedCost->updated_costs()->latest()->get()->map( function($query){
            return [
                'id' => $query->id,
                'plating_process_item_id' => $query->plating_process_item_id,
                'updated_cost' => $query->updated_cost,
                'created_at' => Carbon::parse($query->created_at)->format('Y-m-d'),
                'updated_at' => $query->updated_at,
            ];
        });

        return response()->json( $format);
    }

    public function getUpdatedPriceItemList(Request $request){
        $getUpdatedCost = ItemList::where('id',$request[0])->first();

        $format = $getUpdatedCost->updated_costs()->latest()->get()->map( function($query){
            return [
                'id' => $query->id,
                'plating_process_item_id' => $query->item_list_id,
                'updated_cost' => $query->updated_cost,
                'created_at' => Carbon::parse($query->created_at)->format('Y-m-d'),
                'updated_at' => $query->updated_at,
            ];
        });

        return response()->json($format);
    }

    public function getAvailableDept(Request $request){
        $dept = Department::all();
        return response()->json($dept);
    }

    public function updateDept(Request $request){

        $dept = Department::find($request->params['updated']['id']);

        $detection = 0;

        foreach (Department::all() as $item) {
            if (in_array(strtoupper($request->params['updated']['dept_code']),
                        [strtoupper($item->dept_code)]) &&
                in_array(strtoupper($request->params['updated']['dept_name']),
                        [strtoupper($item->dept_name)])) {
                $detection += 1;
            }
        }

        if($detection==0){
            Department::where('id',$request->params['updated']['id'])
            ->update([
                'dept_code' => strtoupper($request->params['updated']['dept_code']),
                'dept_name' => strtoupper($request->params['updated']['dept_name'])
            ]);
        }

        return response()->json($detection);
    }

    public function deleteDeptConfirm(Request $request){
        Department::find($request->params['dept']['id'])->delete();
        return response()->json();
    }

    public function addConfirmDept(Request $request){


        $detection = 0;

        foreach (Department::all() as $item) {
            if (in_array(strtoupper($request->params['dept']['dept_code']),
                        [strtoupper($item->dept_code)]) &&
                in_array(strtoupper($request->params['dept']['dept_name']),
                        [strtoupper($item->dept_name)])) {
                $detection += 1;
            }
        }

        if($detection==0){
            Department::create([
                'dept_code' => strtoupper($request->params['dept']['dept_code']),
                'dept_name' => strtoupper($request->params['dept']['dept_name'])
            ]);
        }


        return response()->json($detection);
    }

    public function getPermissionForDM(){

        if(Auth::user()->role_as == 1){

            $perm = ['true','true','true','true'];

        } else {

            if(Auth::user()->permission->count() == 0){

                $perm = ['false','false','false','false'];

            } else {

                $perm = Auth::user()->permission[3]->permission;

                $exp = explode(',',$perm);

                $arr = array();

                foreach($exp as $ex){
                    $arr[] = $ex;
                }

                $perm = $arr;
            }
        }
        return response()->json($perm);
    }

    public function authUserForSideBar(){
        $user = ucwords(Auth::user()->name);

        $exp = explode(' ',$user);

        return response()->json($exp[0]);
    }

    public function getDepartmentRegister(){
        $getDept = Department::orderBy('dept_code')->pluck('dept_code')->toArray();
        return response()->json($getDept);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
