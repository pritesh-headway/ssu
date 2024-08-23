<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\FcmNotificationService;
use Hash;

class OrderController extends Controller
{
    protected $fcmNotificationService;

    public function __construct(FcmNotificationService $fcmNotificationService)
    {
        $this->fcmNotificationService = $fcmNotificationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $base_url = url('/');
        if ($request->ajax()) {
            $orderSeller = DB::table('coupons_order')
            ->leftJoin('users', 'users.id', '=', 'coupons_order.user_id')
            ->leftJoin('events', 'events.id', '=', 'coupons_order.event_id')
            ->select('coupons_order.id','coupons_order.user_id','coupons_order.event_id','coupons_order.quantity','coupons_order.receipt_payment','users.storename',DB::raw("CONCAT(users.name, ' ',users.lname) AS seller_name"),DB::raw("CASE WHEN coupons_order.order_status = '0' THEN 'Pending' WHEN coupons_order.order_status = '1' THEN 'Approved' WHEN coupons_order.order_status = '2' THEN 'Declined' WHEN coupons_order.order_status = '3' THEN 'Delivered' ELSE 'Pending' END AS order_status"),'events.event_name', 'events.event_location',DB::raw("DATE_FORMAT(coupons_order.created_at, '%d-%m-%Y') AS order_date"),DB::raw("YEAR(events.start_date) AS event_year"),'coupons_order.reasons')
            ->orderBy('coupons_order.id', 'desc')
            ->get();

            return Datatables::of($orderSeller)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if($row->order_status == 'Approved') {
                        $actionBtn = '-';
                        //<button class="store btn btn-warning btn-sm" onclick="deleveryItem('.$row->id.')">Deliver</button>
                        return $actionBtn;
                    } else if($row->order_status == 'Delivered') {
                        $actionBtn = '-';
                    } else if($row->order_status == 'Declined') {
                        $actionBtn = '-';
                    } else{
                        $actionBtn = '<a href="' . route("order.create", 'oid='.base64_encode($row->id).'/'.base64_encode($row->event_id).'/'.base64_encode($row->user_id).'/'.base64_encode($row->quantity)) . '"
                        class="store btn btn-success btn-sm approve">Approve</a> 
                        <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$row->id.')">Decline</button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('orders.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $param = explode('/', $request->oid);
        $order_id = base64_decode($param[0]);
        $event_id = base64_decode($param[1]);
        $user_id = base64_decode($param[2]);
        $quantity = base64_decode($param[3]);

        $sellecrOrderData = DB::table('coupons_order')
        ->leftJoin('users', 'users.id', '=', 'coupons_order.user_id')
        ->leftJoin('events', 'events.id', '=', 'coupons_order.event_id')
        ->select('coupons_order.id','coupons_order.quantity','users.storename',DB::raw("CONCAT(users.name, ' ',users.lname) AS seller_name"))
        ->where('coupons_order.user_id', '=', $user_id)
        ->where('coupons_order.event_id', '=', $event_id)
        ->where('coupons_order.id', '=', $order_id)
        ->orderBy('coupons_order.created_at', 'desc')
        ->first();
        return view('orders.add', compact('order_id','event_id','user_id','quantity','sellecrOrderData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'addmore.*.from' => 'required',
            'addmore.*.to' => 'required',
        ]);
        $quantity = (int)$request->quantity;
        $slotCal = $quantity / 100;
        switch ($quantity) {
            case 1000:
                $rewdPoints = 20000;
                break;
            case 2000:
                $rewdPoints = 40000;
                break;
            default:
            $rewdPoints = $quantity * 20;
        }
    //   dd($request);
        foreach ($request->addmore as $key => $value) {
            $coupon_range_from = $value['from'];
            $coupon_range_to = $value['to'];

            $from[] = (int)$coupon_range_from;
            $to[] = (int)$coupon_range_to;
        }

        $arr_unique_from = array_unique($from);
        
        $check_from = count($from) !== count($arr_unique_from);
        $arr_unique_to = array_unique($to);
        $check_to = count($from) !== count($arr_unique_to);
        if($check_from == 1 || $check_to == 1) {
            return redirect()->route("order.create", 'oid='.base64_encode($request->oid).'/'.base64_encode($request->event_id).'/'.base64_encode($request->user_id).'/'.base64_encode($quantity))
            ->with('warning', 'Duplicate Coupon Number Found!. Please add proper coupons.');
        }


        $totalFromQty = array_sum($from);
        $totalToQty = array_sum($to);
        $diff = ($totalToQty - $totalFromQty) + $slotCal;
        // echo $diff .'=='. $quantity;die;
        if($diff == $quantity) {
            foreach ($request->addmore as $key => $value) {
                $coupon_range_from = $value['from'];
                $coupon_range_to = $value['to'];

                $from = (int)$coupon_range_from;
                $to = (int)$coupon_range_to;
                $diff = ($from - $to) - 1;
                // "Looping from $from to $to:\n";
                for ($j = $from; $j <= $to; $j++) {
                    $data_insert[] = ['coupon_number' => $j,'event_id' => $request->event_id,'user_id' => $request->user_id, 'created_at' => date('Y-m-d H:i:s')];
                }
            }
        
            DB::table('seller_coupons')->insert($data_insert);
            $order_status_Arr = Order::where('id', $request->oid)->first();

            $order_status_Arr->order_status = '1';
            $order_status_Arr->created_at = date('Y-m-d H:i:s');
            $order_status_Arr->save();

            // rewars points
            $rewarddata = ['points' => $rewdPoints,'event_id'=> $request->event_id, 'user_id' => $request->user_id,'detail' => 'Credited Points', 'created_at' => date('Y-m-d H:i:s')];
            DB::table('rewards')->insert($rewarddata);

            return redirect()->route('order.index')
            ->with('success', 'Order Approved successfully.');

        } else {
            return redirect()->route("order.create", 'oid='.base64_encode($request->oid).'/'.base64_encode($request->event_id).'/'.base64_encode($request->user_id).'/'.base64_encode($quantity))
            ->with('warning', 'Order Quantity Mismatch!. Please add proper Quantity.');
        }
    }

    public function checkPrevCoupons(Request $request) {
        $input = $request;
        $from = $input->_fromVal;
        $to = $input->_toVal;
        $user_id = $input->_user_id;
        $event_id = $input->_event_id;

        $coupon_range_from = explode(',', $from);
        $coupon_range_to = explode(',', $to);

        for ($i = 0; $i < count($coupon_range_from); $i++) {
            $froms = (int) $coupon_range_from[$i];
            $tos = (int) $coupon_range_to[$i];

            for ($j = $froms; $j <= $tos; $j++) {
                $coupon_number[$j] = $j;
            }
        }

        $couponListSeller = DB::table('seller_coupons')
        ->select('seller_coupons.coupon_number')
        // ->where('seller_coupons.user_id', '=', $user_id)
        ->where('seller_coupons.event_id', '=', $event_id)
        ->whereIn('seller_coupons.coupon_number', $coupon_number)
        ->get();
        // dd(count($couponListSeller));
        if(count($couponListSeller) == 0) {
            return response()->json(['status' => true,'message' => 'Coupon allowed']);
        } else {
            return response()->json(['status' => false,'message' => 'Coupon already exists for this event']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Order::find($id);
        return view('orders.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
       
        $order = Order::find($id);
        $order->order_status = '3';
        $order->save();

        $body = array('receiver_id' => $order->user_id,'title' => 'Order Status' ,'message' => 'Order Delivered Successfully','content_available' => true);

        $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);
        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     $sendNotification->getContent();
        // } else {
        //     $sendNotification->getContent();
        // }
        
        return response()->json(['success' => 'Order Delivered successfully']);
        return redirect()->route('order.index')
            ->with('success', 'Order Delivered successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {

        $book = Order::find($id);
        $book->order_status = '2';
        $book->reasons = $request->reasons;
        $book->save();

        $body = array('receiver_id' => $book->user_id,'title' => 'Order Status' ,'message' => 'Order Declined Successfully','content_available' => true);

        $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);
        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     $sendNotification->getContent();
        // } else {
        //     $sendNotification->getContent();
        // }

        return response()->json(['success' => 'Order declined Successfully!']);
        return redirect()->route('order.index')
            ->with('success', 'Order declined successfully');
    }
}
