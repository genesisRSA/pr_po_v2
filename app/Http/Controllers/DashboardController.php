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
       $getEmp =  User::where('role_as','!=',1)->get()->map( function($query){
           return [
               'name' => $query->name,
               'emp_id' => $query->emp_id,
               'email' => $query->email,
               'created_at' => Carbon::parse($query->created_at)->format('Y-m-d'),
               'updated_at' => Carbon::parse($query->updated_at)->format('Y-m-d'),
           ];
       })->toArray();
       return response()->json($getEmp);
    }

    public function getUserAuthorization(Request $request){
        $user = User::where('email',$request->email)->first();

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

        $crow = array();
        foreach($arr as $key => $amp){
            foreach($amp as $idx => $cool){
                $crow[$key][$idx] = json_decode($cool);
            }
        }

        $result = array();
        foreach($crow as $k => $v){
            $result[$k] = array_combine($modules[$k],$crow[$k]);
        }


        return response()->json($result ? $result : 'none');
    }

    public function addOrEditUserPermission(Request $request){
        $user = User::where('email',$request['params']['email'])->first();

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

        return response()->json('success');
    }

    public function deleteUser(Request $request){
        $user = User::where('email',$request->email)->first();

        if($user->permission->count() > 0){
            $user->permission()->delete();
        }

        $user->delete();

        return response()->json('deleted successfully!');
    }

    public function voidUser(Request $request){
        $user = User::where('email',$request->user)->first();
        $user->permission()->delete();
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

        CategoryItem::updateOrCreate(
            ['category_name'=>ucwords($request->cat_name)],
            ['category_name'=>ucwords($request->cat_name)]
        );

        return response()->json('Successfully Added');
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
        $catval = CategoryItem::findOrFail($request->cat_name);
        $catval->subcategory_items()->updateOrCreate(['subcategory_name'=>ucwords($request->subcat_name)],['subcategory_name'=>ucwords($request->subcat_name)]);

        return response()->json('success');
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

        $detection_for_cat_name = '';
        if($request->selected_val['subcategory_name'] == 'N/A'){
            $cat_item = CategoryItem::all()->pluck('category_name')->toArray();

            if(in_array(ucwords($request->updated_val['category_name']),$cat_item)){

            } else {
                CategoryItem::where('category_name',$request->selected_val['category_name'])->update(['category_name'=>ucwords($request->updated_val['category_name'])]);
            }

        } else {
            $cat_item = CategoryItem::where('category_name',$request->selected_val['category_name'])->first()->subcategory_items->pluck('subcategory_name')->toArray();
            if(in_array(ucwords($request->updated_val['subcategory_name']),$cat_item)){

            } else {
                $how = CategoryItem::where('category_name',ucwords($request->selected_val['category_name']))->first();
                $how->subcategory_items->where('subcategory_name',ucwords($request->selected_val['subcategory_name']))->first()->update(['subcategory_name'=>ucwords($request->updated_val['subcategory_name'])]);
            }

            $categ= CategoryItem::all()->pluck('category_name')->toArray();

            if(in_array(ucwords($request->updated_val['category_name']),$categ)){

            } else {
                CategoryItem::where('category_name',$request->selected_val['category_name'])->update(['category_name'=>ucwords($request->updated_val['category_name'])]);
            }
        }
        return response()->json();
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
                'subcat_val' => ['text'=>$query->subcategory_item->subcategory_name,'value'=>$query->sub_category_item_id]
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

        $detection = 0;

        foreach (ItemList::all() as $item) {
            if (in_array(strtolower($request->params['part_name']), [strtolower($item->part_name)]) &&
               in_array(strtolower($request->params['material']), [strtolower($item->material)]) &&
               in_array(strtolower($request->params['dimension']), [strtolower($item->dimension)]) &&
               in_array(strtolower($cat_val), [strtolower($item->category_item_id)]) &&
               in_array(strtolower($subcat_val), [strtolower($item->sub_category_item_id)])) {
                $detection += 1;
            }
        }

        if($detection == 0){
            ItemList::where('id',$request->params['id'])->update([
                'category_item_id' => $cat_val,
                'sub_category_item_id' => $subcat_val,
                'part_name' => $request->params['part_name'] == null ? '' : $request->params['part_name'],
                'material' => $request->params['material'] == null ? '' : $request->params['material'],
                'dimension' => $request->params['dimension'] == null ? '' : $request->params['dimension'],
            ]);
        }


        return response()->json('successfully updated');
    }

    public function addItemList(Request $request){

        $detection = 0;

        foreach (ItemList::all() as $item) {
            if (in_array(strtolower($request->partname_val), [strtolower($item->part_name)]) &&
               in_array(strtolower($request->material_val), [strtolower($item->material)]) &&
               in_array(strtolower($request->dimension), [strtolower($item->dimension)]) &&
               in_array(strtolower($request->cat_val), [strtolower($item->category_item_id)]) &&
               in_array(strtolower($request->subcat_val), [strtolower($item->sub_category_item_id)])) {
                $detection += 1;
            }
        }

            if($detection == 0){
                    ItemList::create([
                    'category_item_id' => $request->cat_val,
                    'sub_category_item_id' => $request->subcat_val,
                    'part_name' => $request->partname_val == null ? '' : $request->partname_val,
                    'material' => $request->material_val == null ? '' : $request->material_val,
                    'dimension' => $request->dimension == null ? '' : $request->dimension
                ]);
            }


        return response()->json();
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
        }

        if($detection==0){

            PlatingProcess::create([
                'plating_process' => strtoupper($request->params['plating_process']),
                'type'            => $request->params['type'] == null ? '' : strtoupper($request->params['type']),
                'price_per_square_inch' => $price
            ]);

        }

        
        return response()->json( 'success' );
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
        }

        if($detection==0){
            PlatingProcess::findOrFail($request->params['id'])->update([
                'plating_process' => strtoupper($request->params['plating_process']),
                'type' => $request->params['type'] == null ? '' : strtoupper($request->params['type']),
                'price_per_square_inch' => $price 
            ]);
        }

        return response()->json('success');
    }

    public function deletePlatingProcess(Request $request){
        PlatingProcess::findOrFail($request->params)
        ->delete();
       return response()->json('success');
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
