<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\FcmNotificationService;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Reward;

class AssetController extends Controller
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
           
            $data = Asset::select('asset_orders.id','asset_orders.user_id','asset_orders.event_id','asset_orders.title','asset_orders.detail', DB::raw("CASE WHEN asset_orders.order_status = '0' THEN 'Pending' WHEN asset_orders.order_status = '1' THEN 'Approved' WHEN asset_orders.order_status = '2' THEN 'Declined' ELSE '' END order_status"), DB::raw("DATE_FORMAT(asset_orders.created_at, '%d %M %Y %h:%i %p') AS bill_date"),"asset_orders.quantity","asset_orders.amount","asset_orders.reasons",DB::raw("CONCAT(users.storename, ' (', users.name, ' ',users.lname,' )') AS seller_name"))
            ->selectSub(
                DB::table('rewards')
                    ->selectRaw('SUM(CASE WHEN transaction_type = "1" THEN points ELSE 0 END)')
                    ->whereColumn('rewards.user_id', 'asset_orders.user_id')
                    ->toSql(),
                'total_points'
            )
            ->selectSub(
                DB::table('rewards')
                    ->selectRaw('SUM(CASE WHEN transaction_type = "1" THEN points ELSE 0 END) - SUM(CASE WHEN transaction_type = "2" THEN points ELSE 0 END)')
                    ->whereColumn('rewards.user_id', 'asset_orders.user_id')
                    ->toSql(),
                'remaining_points'
            )
            ->leftJoin('users', 'users.id', '=', 'asset_orders.user_id')
            ->where('asset_orders.status',1)
            ->orderBy('asset_orders.id', 'desc')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $role = myRolesFunction(Session::get('role_name'));
                    if($row->order_status == 'Approved') {
                         if($role == "1" || $role == "2") {
                            $actionBtn = '<span class="badge bg-success shadow-md dark:group-hover:bg-transparent">Approved</span>';
                         }
                        return $actionBtn;
                    } else if($row->order_status == 'Completed') {
                        $actionBtn = ' - ';
                    } else if($row->order_status == 'Declined') {
                        $actionBtn = ' <span class="badge bg-danger shadow-md dark:group-hover:bg-transparent">Declined</span> ';
                    }else{
                        if ($role == "1" || $role == "2") {
                        $actionBtn = '
                        <button class="store btn btn-success btn-sm approve" onclick="approveItem('.$row->id.')">Approve</button>
                        <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$row->id.')">Decline</button>';
                         }
                        
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('assets.list', compact('role'));
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
     * Show the form for creating a new resource.
     */
    public function addAsset(Request $request)
    {
        $eventList = Event::all()->where('status', 1);
        $userList = User::all()->where('user_type',2)->where('id', '!=', 1)->where('status', '1');
        return view('assets.addForm', compact('eventList','userList'));
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
            'quantity' => 'required'
        ], [
            'event_id' => 'The event name is required',
            'user_id' => 'The seller name is required',
        ]);
        $input = $request->all();

        $checkPoints = $this->getPoints($input['user_id'], $input['event_id']);
        $checkPoints = json_decode($checkPoints->getContent());
        if($checkPoints->points <  $input['amount']){
            return redirect()->route('asset.index')
            ->with('error', 'Insufficient points available.');
        }
        $data['event_id'] = $input['event_id'];
        $data['user_id'] = $input['user_id'];
        $data['title'] = $input['title'];
        $data['detail'] = $input['detail'];
        $data['amount'] = $input['amount'];
        $data['order_status'] = '1';
        $data['quantity'] = $input['quantity'];
        Asset::create($data);

        $rewarddata = ['points' => (int)$input['amount'],'event_id'=> $input['event_id'], 'user_id' => $input['user_id'],'detail' => 'Deduct for '.$input['title'], 'transaction_type' => '2', 'created_at' => date('Y-m-d H:i:s')];
        DB::table('rewards')->insert($rewarddata);
        
        $newData  = json_encode(array('type'=> 'asset_status'));
        $body = array('receiver_id' => $input['user_id'],'title' => 'Assets Status Changed' ,'message' => 'Assets Approved','content_available' => true,'type' => 'asset_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);

        return redirect()->route('asset.index')
            ->with('success', 'Assets created successfully.');
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

        $checkPoints = $this->getPoints($order->user_id, $order->event_id);
        $checkPoints = json_decode($checkPoints->getContent());
        if($checkPoints->points <=  $request->amount){
            return response()->json(['success' => 'Insufficient points available']);
        }

        $order->order_status = '1';
        $order->amount = $request->amount;
        $order->save();

        $rewarddata = ['points' => (int)$request->amount,'event_id'=> $order->event_id, 'user_id' => $order->user_id,'detail' => 'Deduct for '.$order->title, 'transaction_type' => '2', 'created_at' => date('Y-m-d H:i:s')];
        DB::table('rewards')->insert($rewarddata);
        
        $newData  = json_encode(array('type'=> 'asset_status'));
        $body = array('receiver_id' => $order->user_id,'title' => 'Assets Status Changed' ,'message' => 'Assets Approved','content_available' => true,'type' => 'asset_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);

        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     return $sendNotification->getContent();
        // } else {
        //     return $sendNotification->getContent();
        // }
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

        $newData  = json_encode(array('type'=> 'asset_status'));
        $body = array('receiver_id' => $book->user_id,'title' => 'Assets Status Changed' ,'message' => 'Assets Declined','content_available' => true,'type' => 'asset_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);

        return response()->json(['success' => 'Assets declined Successfully!']);
        return redirect()->route('asset.index')
            ->with('success', 'Assets declined successfully');
    }

    public function getPoints($id, $event_id)
    {
        // Fetch customer by ID
        $customer =  Reward::where('rewards.status', '=', 1)
        ->addSelect(DB::raw("(SELECT SUM(points) FROM rewards WHERE user_id = '$id' AND event_id = '$event_id' AND transaction_type = 1 GROUP BY user_id, event_id) as totalPoints"))
        ->addSelect(DB::raw("((SELECT SUM(points) FROM rewards WHERE user_id = '$id' AND event_id = '$event_id' AND transaction_type = 1 GROUP BY user_id, event_id) - (SELECT SUM(points) FROM rewards WHERE user_id = '$id' AND event_id = '$event_id' AND transaction_type = 2 GROUP BY user_id, event_id)) as leftPoints"))
        ->leftJoin('users AS users2', 'users2.id', '=', 'rewards.user_id')
        ->leftJoin('events', 'events.id', '=', 'rewards.event_id')
        ->where('rewards.user_id', '=', $id)
        ->where('rewards.event_id', '=', '1')
        ->groupBy('rewards.user_id')
        ->first();
        if($customer) {
            $leftPoints = ($customer->leftPoints)??($customer->totalPoints)??0;
        }
        if ($customer) {
            return response()->json(['points' => $leftPoints]);
        } else {
           return response()->json(['points' => 0]);
        }
    }
}
