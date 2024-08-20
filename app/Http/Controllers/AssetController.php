<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $base_url = url('/');
        if ($request->ajax()) {
            $data = Asset::select('id','user_id','event_id','title','detail', DB::raw("CASE WHEN order_status = '0' THEN 'Pending' WHEN order_status = '1' THEN 'Approved' ELSE '' END order_status"), DB::raw("DATE_FORMAT(created_at, '%d %M %Y %h:%i %p') AS bill_date"),"quantity","amount")
            ->where('status',1)
            ->orderBy('id', 'desc')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if($row->order_status == 'Approved') {
                        $actionBtn = 'Approved';
                        return $actionBtn;
                    } else if($row->order_status == 'Completed') {
                        $actionBtn = ' - ';
                    } else{
                        $actionBtn = '
                        <button class="store btn btn-success btn-sm approve" onclick="approveItem('.$row->id.')">Approve</button>
                        ';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('assets.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $param = explode('/', $request->bid);
        $bill_id = base64_decode($param[0]);
        $event_id = base64_decode($param[1]);
        $user_id = base64_decode($param[2]);

        $sellecrOrderData = Asset::where('user_id', '=', $user_id)
        ->where('event_id', '=', $event_id)
        ->where('id', '=', $bill_id)
        ->orderBy('created_at', 'desc')
        ->first();
        // dd($sellecrOrderData->amount);
        return view('assets.add', compact('bill_id','event_id','user_id','sellecrOrderData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'detail' => 'required',
        ]);
        //  dd($request);
        $bill_Arr = Asset::where('id', $request->bid)->first();
        
        $bill_Arr->order_status = '1';
        $bill_Arr->amount = $request->amount;
        $bill_Arr->save();

        return redirect()->route('asset.index')
            ->with('success', 'Assets Update successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $order)
    {
        return view('assets.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Asset::find($id);
        return view('assets.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       
        $order = Asset::find($id);
        $order->order_status = '1';
        $order->amount = $request->amount;
        $order->save();

        $rewarddata = ['points' => (int)$request->amount,'event_id'=> $order->event_id, 'user_id' => $order->user_id,'detail' => 'Deduct for '.$order->title, 'transaction_type' => '2', 'created_at' => date('Y-m-d H:i:s')];
        DB::table('rewards')->insert($rewarddata);


        return response()->json(['success' => 'Assets Approved successfully']);
        return redirect()->route('asset.index')
            ->with('success', 'Assets Completed successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $book = Asset::find($id);
        $book->order_status = '2';
        $book->reasons = $request->reasons;
        $book->save();
        return response()->json(['success' => 'Assets declined Successfully!']);
        return redirect()->route('asset.index')
            ->with('success', 'Assets declined successfully');
    }
}
