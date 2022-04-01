<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Rfq_record;
use App\Models\SitePermission;
use PDF;
use Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
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
           return response()->json(!Auth::user()->permission ? 'has no permission' : 'has permission');
        }
    }

    public function admin_authorization(){
        return Inertia::render('Authorization');
    }

    public function getEmpUser(){
       $getEmp =  User::where('role_as','!=',1)->get()->map( function($query){
           return [
               'name' => $query->name,
               'email' => $query->email,
               'created_at' => Carbon::parse($query->created_at)->format('Y-m-d'),
               'updated_at' => Carbon::parse($query->updated_at)->format('Y-m-d'),
           ];
       })->toArray();
       return response()->json($getEmp);
    }

    public function getUserAuthorization(Request $request){
        $user = User::where('email',$request->email)->first();

        $modules = [['view_rfq','add_rfq','update_rfq','delete_rfq'],['view_pr','add_pr','update_pr','delete_pr']];
        $arr = array();
        foreach($user->permission as $perm){
            $arr[] = explode(',',$perm->permission);
        }

        $crow = array();
        foreach($arr as $amp){
            foreach($amp as $cool){
                $crow[] = json_decode($cool);
            }
        }

        $chunk = array_chunk($crow,4);

        $result = array();
        foreach($chunk as $k => $v){
            $result[$k] = array_combine($modules[$k],$chunk[$k]);
        }
        

        return response()->json($result);
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
