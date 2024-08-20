<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $base_url = url('/');
        if ($request->ajax()) {
            $data = Bill::select('id','user_id','event_id','title','amount','detail','file',DB::raw("CASE WHEN bill_status = '0' THEN 'Pending' WHEN bill_status = '1' THEN 'Approved' WHEN bill_status = '2' THEN 'Declined' WHEN bill_status = '3' THEN 'Completed' ELSE 'Pending' END AS bill_status"), DB::raw("DATE_FORMAT(created_at, '%d %M %Y %h:%i %p') AS bill_date"),'reasons')
            ->where('status',1)
            ->orderBy('id', 'desc')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if($row->bill_status == 'Approved') {
                        $actionBtn = '<button class="store btn btn-warning btn-sm completed" onclick="deleveryItem('.$row->id.')">Completed</button>';
                        return $actionBtn;
                    } else if($row->bill_status == 'Completed') {
                        $actionBtn = ' - ';
                    } else if($row->bill_status == 'Declined') {
                        $actionBtn = ' - ';
                       
                    } else{
                        $actionBtn = '<a href="' . route("bill.create", 'bid='.base64_encode($row->id).'/'.base64_encode($row->event_id).'/'.base64_encode($row->user_id)) . '"
                        class="store btn btn-success btn-sm approve">Approve</a> 
                        <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$row->id.')">Decline</button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('bills.list');
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

        $sellecrOrderData = Bill::where('user_id', '=', $user_id)
        ->where('event_id', '=', $event_id)
        ->where('id', '=', $bill_id)
        ->orderBy('created_at', 'desc')
        ->first();
        // dd($sellecrOrderData->amount);
        return view('bills.add', compact('bill_id','event_id','user_id','sellecrOrderData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5042',
        ]);
         
        $bill_Arr = Bill::where('id', $request->bid)->first();
         if ($image = $request->file('receipt')) {
            $destinationPath = 'public/bills/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $bill_Arr->receipt = $profileImage;
        }

        $bill_Arr->bill_status = '1';
        $bill_Arr->save();

        return redirect()->route('bill.index')
            ->with('success', 'Bill Approved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $order)
    {
        return view('bills.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Bill::find($id);
        return view('bills.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
       
        $order = Bill::find($id);
        $order->bill_status = '3';
        $order->save();

        return response()->json(['success' => 'Bill Completed successfully']);
        return redirect()->route('bill.index')
            ->with('success', 'Bill Completed successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $book = Bill::find($id);
        $book->bill_status = '2';
        $book->reasons = $request->reasons;
        $book->save();
        return response()->json(['success' => 'Bill declined Successfully!']);
        return redirect()->route('bill.index')
            ->with('success', 'Bill declined successfully');
    }
}
