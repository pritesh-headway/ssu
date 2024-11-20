<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Graphic;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\FcmNotificationService;

class GraphicController extends Controller
{
    protected $fcmNotificationService;
    public function __construct(FcmNotificationService $fcmNotificationService)
    {
         $this->fcmNotificationService = $fcmNotificationService;
        $this->middleware('role:Administrator,Accountant,Verifier');
    }
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $base_url = url('/');
        if ($request->ajax()) {
            $data = Graphic::select('graphics.id','graphics.user_id','graphics.event_id','graphics.title','graphics.detail', DB::raw("CASE WHEN graphics.graphic_status = '0' THEN 'Pending' WHEN graphics.graphic_status = '1' THEN 'Approved' WHEN graphics.graphic_status = '2' THEN 'Declined' ELSE '' END graphic_status"), DB::raw("DATE_FORMAT(graphics.created_at, '%d %M %Y %h:%i %p') AS bill_date"),"graphics.reasons",DB::raw("CONCAT(users.storename, ' (', users.name, ' ',users.lname,' )') AS seller_name"))
            ->leftJoin('users', 'users.id', '=', 'graphics.user_id')
            ->where('graphics.status',1)
            ->orderBy('graphics.id', 'desc')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if($row->graphic_status == 'Approved') {
                        $actionBtn = '<span class="badge bg-success shadow-md dark:group-hover:bg-transparent">Approved</span>';
                        return $actionBtn;
                    } else if($row->graphic_status == 'Completed') {
                        $actionBtn = ' - ';
                    } else if($row->graphic_status == 'Declined') {
                        $actionBtn = ' <span class="badge bg-danger shadow-md dark:group-hover:bg-transparent">Declined</span> ';
                    }else{
                        $actionBtn = '
                        <button class="store btn btn-success btn-sm approve" onclick="approveItem('.$row->id.')">Approve</button>
                        <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$row->id.')">Decline</button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('graphics.list');
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

        $sellecrOrderData = Graphic::where('user_id', '=', $user_id)
        ->where('event_id', '=', $event_id)
        ->where('id', '=', $bill_id)
        ->orderBy('created_at', 'desc')
        ->first();
        // dd($sellecrOrderData->amount);
        return view('graphics.add', compact('bill_id','event_id','user_id','sellecrOrderData'));
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
        $bill_Arr = Graphic::where('id', $request->bid)->first();
        
        $bill_Arr->graphic_status = '1';
        $bill_Arr->amount = $request->amount;
        $bill_Arr->save();

        return redirect()->route('graphic.index')
            ->with('success', 'Graphics Update successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $order)
    {
        return view('graphics.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Graphic::find($id);
        return view('graphics.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       
        $order = Graphic::find($id);
        $order->graphic_status = '1';
        $order->save();

        $newData  = json_encode(array('type'=> 'graphics_status'));
        $body = array('receiver_id' => $order->user_id,'title' => 'Graphics Status Changed' ,'message' => 'Graphics Approved','content_available' => true,'type' => 'graphics_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);

        return response()->json(['success' => 'Graphics Approved successfully']);
        return redirect()->route('graphic.index')
            ->with('success', 'Assets Completed successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $book = Graphic::find($id);
        $book->graphic_status = '2';
        $book->reasons = $request->reasons;
        $book->save();

        $newData  = json_encode(array('type'=> 'graphics_status'));
        $body = array('receiver_id' => $book->user_id,'title' => 'Graphics Status Changed' ,'message' => 'Graphics Declined','content_available' => true,'type' => 'graphics_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);

        return response()->json(['success' => 'Graphics declined Successfully!']);
        return redirect()->route('graphic.index')
            ->with('success', 'Assets declined successfully');
    }
}
