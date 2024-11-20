<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Reward;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\FcmNotificationService;
use App\Models\User;
use App\Models\Event;
use App\Http\Controllers\AssetController;

class BillController extends Controller
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
         $role = myRolesFunction(Session::get('role_name'));
        if ($request->ajax()) {
            $data = Bill::select('bills.id','bills.user_id','bills.event_id','bills.title','bills.amount','bills.detail','file',DB::raw("CASE WHEN bills.bill_status = '0' THEN 'Pending' WHEN bills.bill_status = '1' THEN 'Approved' WHEN bills.bill_status = '2' THEN 'Declined' WHEN bills.bill_status = '3' THEN 'Completed' ELSE 'Pending' END AS bill_status"), DB::raw("DATE_FORMAT(bills.created_at, '%d %M %Y %h:%i %p') AS bill_date"),'bills.reasons',DB::raw("CONCAT(users.storename, ' (', users.name, ' ',users.lname,' )') AS seller_name"))
            ->leftJoin('users', 'users.id', '=', 'bills.user_id')
            ->where('bills.status',1)
            ->orderBy('bills.id', 'desc')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $role = myRolesFunction(Session::get('role_name'));
                    $actionBtn = '';
                    if($row->bill_status == 'Approved') {
                        if($role == "1" || $role == "2") {
                            $actionBtn = '<button class="store btn btn-warning btn-sm completed" onclick="deleveryItem('.$row->id.')">Completed</button>';
                        }
                        return $actionBtn;
                    } else if($row->bill_status == 'Completed') {
                        $actionBtn = ' <span class="badge bg-success shadow-md dark:group-hover:bg-transparent">Completed</span> ';
                    } else if($row->bill_status == 'Declined') {
                        $actionBtn = ' <span class="badge bg-danger shadow-md dark:group-hover:bg-transparent">Declined</span> ';
                       
                    } else{
                        if ($role == "1" || $role == "2") {
                            $actionBtn = '<a href="' . route("bill.create", 'bid='.base64_encode($row->id).'/'.base64_encode($row->event_id).'/'.base64_encode($row->user_id)) . '"
                        class="store btn btn-success btn-sm approve">Approve</a> 
                        <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$row->id.')">Decline</button>';
                        }
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('bills.list', compact('role'));
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
     * Show the form for creating a new resource.
     */
    public function addBIll(Request $request)
    {
        $eventList = Event::all()->where('status', 1);
        $userList = User::all()->where('user_type',2)->where('id', '!=', '1')->where('status', '1');
        return view('bills.addForm', compact('eventList','userList'));
    }

     /**
     * Show the form for creating a new resource.
     */
    public function insertData(Request $request)
    {
       
         $request->validate([
            'event_id' => 'required|not_in:null',
            'user_id' =>'required|not_in:null',
            'title' => 'required',
            'detail' => 'required',
            'amount' => 'required|numeric',
            'docFile' => 'required|mimes:jpeg,png,jpg,gif,webp,pdf|max:5042',
        ], [
            'event_id' => 'The event name is required',
            'user_id' => 'The seller name is required',
        ]);
        $assetController = app('App\Http\Controllers\AssetController');
        $input = $request->all();
        $checkPoints = $assetController->getPoints($input['user_id'], $input['event_id']);
        $checkPoints = json_decode($checkPoints->getContent());
        
        if($checkPoints->points < $input['amount']){
            return redirect()->route('bill.index')
            ->with('error', 'Insufficient points available.');
        }
        if ($image = $request->file('docFile')) {
            $destinationPath = 'public/bills/';
            $billImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $billImage);
            $input['file'] = "$billImage";
        }
        $input['bill_status'] = '1';
        
        Bill::create($input);

      
        $rewarddata = ['points' => (int)$input['amount'],'event_id'=> $input['event_id'], 'user_id' => $input['user_id'],'detail' => 'Deduct for '.$input['title'], 'transaction_type' => '2', 'created_at' => date('Y-m-d H:i:s')];
        DB::table('rewards')->insert($rewarddata);

        $newData  = json_encode(array('type'=> 'bill_status'));
        $body = array('receiver_id' => $input['user_id'],'title' => 'Bill Status Changed' ,'message' => 'Bill Approved Successfully','content_available' => true,'type' => 'bill_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);

        return redirect()->route('bill.index')
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receipt' => 'required|mimes:jpeg,png,jpg,gif,svg,webp,pdf|max:5042',
        ]);
         
        $bill_Arr = Bill::where('id', $request->bid)->first();

        $assetController = app('App\Http\Controllers\AssetController');
        $input = $request->all();
        $checkPoints = $assetController->getPoints($request->user_id, $request->event_id);
        $checkPoints = json_decode($checkPoints->getContent());
        if($checkPoints->points <=  $request->amount){
            return redirect()->route('bill.index')
            ->with('error', 'Insufficient points available.');
        }

        if ($image = $request->file('receipt')) {
            $destinationPath = 'public/bills/';
            $billImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $billImage);
            $bill_Arr->receipt = $billImage;
        }

        $bill_Arr->bill_status = '1';
        $bill_Arr->save();
      
        $rewarddata = ['points' => (int)$request->amount,'event_id'=> $request->event_id, 'user_id' => $request->user_id,'detail' => 'Deduct for '.$bill_Arr->title, 'transaction_type' => '2', 'created_at' => date('Y-m-d H:i:s')];
        DB::table('rewards')->insert($rewarddata);

        $newData  = json_encode(array('type'=> 'bill_status'));
        $body = array('receiver_id' => $request->user_id,'title' => 'Bill Status Changed' ,'message' => 'Bill Approved Successfully','content_available' => true,'type' => 'bill_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);
        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     $sendNotification->getContent();
        // } else {
        //     $sendNotification->getContent();
        // }

        return redirect()->route('bill.index')
            ->with('success', 'Bill Approved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $detail = $request->input('Title');
        if(isset($detail)) {
            $id = $request->input('id');
            $Keywords = Bill::where('id', $id)->first();
          
            Bill::where('id', $id)->update([
                'detail' => $detail
            ]);
            
            return response()->json($Keywords);

        } else{
            $role = myRolesFunction(Session::get('role_name'));
            $Bill = Bill::where('id', $id)->first();
            $Reward = Reward::where('user_id', $Bill->user_id)->get();
            $User = User::where('id', $Bill->user_id)->first();
            $event_id = $Bill->event_id;
            $user_id = $Bill->user_id;
            return view('bills.show', compact('Bill','Reward', 'User', 'role', 'event_id','user_id','id'));
        }
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

        $newData  = json_encode(array('type'=> 'bill_status'));
        $body = array('receiver_id' => $order->user_id,'title' => 'Bill Status Changed' ,'message' => 'Bill Completed','content_available' => true,'type' => 'bill_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
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
        $newData  = json_encode(array('type'=> 'bill_status'));
        $body = array('receiver_id' => $book->user_id,'title' => 'Bill Status Changed' ,'message' => 'Bill Declined','content_available' => true,'type' => 'bill_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
        return response()->json(['success' => 'Bill declined Successfully!']);
        return redirect()->route('bill.index')
            ->with('success', 'Bill declined successfully');
    }

    public function savekeywordDetails(Request $request)
    {
        $detail = $request->input('Title');
        $id = $request->input('id');
        $Keywords = Bill::where('id', $id)->first();
        dd($Keywords);
        // $Keywords->detail = $detail;
        // $Keywords->save();

        Bill::where('id', $id)->update([
            'detail' => $detail
        ]);

        return response()->json($Keywords);
    }
}
