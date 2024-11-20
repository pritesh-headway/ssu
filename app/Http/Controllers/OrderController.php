<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\FcmNotificationService;
use Hash;
use Illuminate\Support\Facades\Session;
use Auth;

class OrderController extends Controller
{
    protected $fcmNotificationService;
    public $base_url;
    public $receipt_path;
    public $profile_path;
    public function __construct(FcmNotificationService $fcmNotificationService)
    {
        $this->fcmNotificationService = $fcmNotificationService;
        $this->base_url = url('/');
        $this->receipt_path = '/public/receipt_images/';
        $this->profile_path = '/public/profile_images/';
        $this->middleware('role:Administrator,Verifier,Accountant');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orderSeller = DB::table('coupons_order')
            ->leftJoin('users', 'users.id', '=', 'coupons_order.user_id')
            ->leftJoin('events', 'events.id', '=', 'coupons_order.event_id')
            ->select('coupons_order.id','coupons_order.user_id','coupons_order.event_id','coupons_order.quantity','coupons_order.receipt_payment','users.storename',DB::raw("CONCAT(users.storename, ' (', users.name, ' ',users.lname,' )') AS seller_name"),DB::raw("CASE WHEN coupons_order.order_status = '0' THEN 'Pending' WHEN coupons_order.order_status = '1' THEN 'Approved' WHEN coupons_order.order_status = '2' THEN 'Declined' WHEN coupons_order.order_status = '3' THEN 'Delivered' ELSE 'Pending' END AS order_status"),'events.event_name', 'events.event_location',DB::raw("DATE_FORMAT(coupons_order.created_at, '%d-%m-%Y') AS order_date"),DB::raw("YEAR(events.start_date) AS event_year"),'coupons_order.reasons')
            ->orderBy('coupons_order.id', 'desc')
            ->get();

            return Datatables::of($orderSeller)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $role = myRolesFunction(Session::get('role_name'));
                    $actionBtn = '';
                    if($row->order_status == 'Approved') {
                        if ($role == "1" || $role == "2" || $role == "3" || $role == "4" || $role == "5") {
                            $actionBtn = '<a href="' . route("order.show", $row->id) . '"
                            class="store btn btn-warning btn-sm approve">Details</a>';
                        }
                        if($role == "3" || $role == "1") {
                            $actionBtn .= '<button class="store btn btn-deliver btn-sm" onclick="deleveryItem('.$row->id.')">Deliver</button>';
                        }
                        return $actionBtn;
                    } else if($row->order_status == 'Delivered') {
                        $actionBtn = '<a href="' . route("order.show", $row->id) . '"
                            class="store btn btn-warning btn-sm approve">Details</a>';
                    } else if($row->order_status == 'Declined') {
                        $actionBtn = '-';
                    } else{
                       
                        if ($role == "1" || $role == "2") {
                            $actionBtn = '<a href="' . route("order.create", 'oid='.base64_encode($row->id).'/'.base64_encode($row->event_id).'/'.base64_encode($row->user_id).'/'.base64_encode($row->quantity)) . '"
                            class="store btn btn-success btn-sm approve">Approve</a> 
                            <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$row->id.')">Decline</button>';
                        }
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

    public function processCouponOrder($userId, $event_id , $couponOrder) {
        // Fetch the user's data
        $user = DB::table('points_history')->where('user_id', $userId)->first();
        
        if(isset($user)) {
            $totalCoupons = $user->total_coupons;
            $points = $user->points;
            $pending500 = $user->pending_500;
        } else {
            // $rewarddata = ['points' => 20000,'event_id'=> $event_id, 'user_id' => $userId,'detail' => 'Credited Points', 'created_at' => date('Y-m-d H:i:s')];
            // DB::table('rewards')->insert($rewarddata);
            $totalCoupons = 0;
            $points = 0;
            $pending500 = 0;
        }
        
        // Assign points for every 2000 coupons
        if ($couponOrder == 2000 && $points == 0) {
            // Calculate how many sets of 2000 coupons there are
            $setsOf2000 = intdiv($couponOrder, 2000); // Number of complete sets of 2000
            $pointsToAssign = $couponOrder * 10; // 20 points for each set of 2000

            // Update user's points
            $points += $pointsToAssign;
            // Remove the processed coupons from total
            $totalCoupons = $couponOrder;
            // echo "$pointsToAssign points assigned for $setsOf2000 sets of 2000 coupons.<br>"; die;
              // rewars points
            $rewarddata = ['points' => $pointsToAssign,'event_id'=> $event_id, 'user_id' => $userId,'detail' => 'Credited Points', 'created_at' => date('Y-m-d H:i:s')];
            DB::table('rewards')->insert($rewarddata);

        } else {
            $temp = $totalCoupons + $couponOrder;
            $temp-= 2000;
            $setsOf1000 = intdiv($temp, 1000); // Number of complete sets of 2000
            if ($pending500 == 0) {
                $pending500 += $setsOf1000;
                $pointsToAssign = $setsOf1000 * 20000; // 20 points for each set of 2000
                // Update user's points
                $points += $pointsToAssign;
                if($setsOf1000 != 0) {
                    // rewars points
                    $rewarddata = ['points' => $pointsToAssign,'event_id'=> $event_id, 'user_id' => $userId,'detail' => 'Credited Points', 'created_at' => date('Y-m-d H:i:s')];
                    DB::table('rewards')->insert($rewarddata);
                }
            } else {
                $tempSetsOf1000 = $setsOf1000 - $pending500;
                $pending500 += $tempSetsOf1000;
                $pointsToAssign = $tempSetsOf1000 * 20000; // 20 points for each set of 2000
                // Update user's points
                $points += $pointsToAssign;
                if($tempSetsOf1000 != 0) {
                    // rewars points
                    $rewarddata = ['points' => $pointsToAssign,'event_id'=> $event_id, 'user_id' => $userId,'detail' => 'Credited Points', 'created_at' => date('Y-m-d H:i:s')];
                    DB::table('rewards')->insert($rewarddata);
                }
            }
            // Remove the processed coupons from total
            $totalCoupons += $couponOrder;
        }
        // Update the user's data in the database
        if(isset($user)) {
            DB::table('points_history')
            ->where("user_id", "=", $userId)
            ->update(["total_coupons" => $totalCoupons, "points" => $points, 'pending_500' => $pending500]);
        } else {
            DB::table('points_history')->insertGetId(['user_id' => $userId, 'total_coupons' => $totalCoupons, 'points' => $points, 'pending_500' => $pending500]);
        }
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
        //dd($request);
        foreach ($request->addmore as $key => $value) {
            $coupon_range_from = $value['from'];
            $coupon_range_to = $value['to'];

            $from[] = (int)$coupon_range_from;
            $to[] = (int)$coupon_range_to;
            $slotBooked[] = $coupon_range_from .' - '. $coupon_range_to;
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
                    $data_insert[] = ['coupon_number' => $j,'order_id' => $request->oid,'event_id' => $request->event_id,'user_id' => $request->user_id, 'created_at' => date('Y-m-d H:i:s')];
                }
            }
        
            DB::table('seller_coupons')->insert($data_insert);
            $order_status_Arr = Order::where('id', $request->oid)->first();

            $order_status_Arr->order_status = '1';
            $order_status_Arr->approval_date = date("Y-m-d H:i:s");
            $order_status_Arr->save();

            //slot data by Seller
            if($slotBooked) {
                foreach ($slotBooked as $key => $value) {
                    $data_slot[] = ['user_id' => $request->user_id, 'order_id' => $request->oid, 'slot'=> $value];
                }
            }
            DB::table('coupon_slot_booked_sellers')->insert($data_slot);

            $newData  = json_encode(array('type'=> 'coupon_order_status'));
            $body = array('receiver_id' => $request->user_id,'title' => 'Order Status Changed' ,'message' => 'Order Approved Successfully','content_available' => true,'type' => 'coupon_order_status', 'data' => $newData);

            $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
            // $notifData = json_decode($sendNotification->getContent(), true);
            // if (isset($notifData['status']) && $notifData['status'] == true) {
            //     $sendNotification->getContent();
            // } else {
            //     $sendNotification->getContent();
            // }

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
        ->where('seller_coupons.status', '=', '1')
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
        $base_url = $this->base_url;
        $couponData = DB::table('coupons_order')
            ->leftJoin('users', 'users.id', '=', 'coupons_order.user_id')
            ->leftJoin('events', 'events.id', '=', 'coupons_order.event_id')
            ->leftJoin('seller_coupons', 'seller_coupons.order_id', '=', 'coupons_order.id')
            ->select('coupons_order.id', 'coupons_order.quantity', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->receipt_path . "', coupons_order.receipt_payment),'') AS receipt_payment"), 'users.storename', DB::raw("CONCAT(users.name, ' ',users.lname) AS seller_name"), DB::raw("CASE WHEN coupons_order.order_status = '0' THEN 'Pending' WHEN coupons_order.order_status = '1' THEN 'Approved' WHEN coupons_order.order_status = '2' THEN 'Declined' WHEN coupons_order.order_status = '3' THEN 'Delivered' ELSE 'Pending' END AS order_status"), 'events.event_location', DB::raw("DATE_FORMAT(coupons_order.created_at, '%d %M %Y %h:%i %p') AS order_date"),'events.event_name','coupons_order.reasons', DB::raw("MIN(seller_coupons.coupon_number) AS minnum , MAX(seller_coupons.coupon_number) AS maxnum"), 'users.storename', DB::raw("CONCAT('" . $base_url . "','" . $this->profile_path . "',users.avatar) AS avatar"), DB::raw("CONCAT(users.flatNo,' ',users.area,' ',users.city, ' ', users.state, '-' ,users.pincode) AS address"),'users.phone_number','users.email')
            ->where('coupons_order.status', '=', '1')
            ->where('coupons_order.id', '=', $order->id)
            ->where('seller_coupons.order_id', '=', $order->id)
            ->where('seller_coupons.status', '=', '1')
            ->orderBy('coupons_order.created_at', 'desc')
            ->first();

        $slotdata =  DB::table('coupons_order')
          ->leftJoin('coupon_slot_booked_sellers', 'coupon_slot_booked_sellers.order_id', '=', 'coupons_order.id')
           ->select('coupon_slot_booked_sellers.slot')
              ->where('coupons_order.status', '=', '1')
            ->where('coupon_slot_booked_sellers.order_id', '=', $order->id)
            ->orderBy('coupons_order.created_at', 'desc')
            ->get();

        // dd($slotdata);
        return view('orders.show', compact('order','couponData','slotdata'));
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
        $order->delivered_date = date("Y-m-d H:i:s");
        $order->save();

        $this->processCouponOrder($order['user_id'], $order['event_id'] , $order['quantity']);

        $newData  = json_encode(array('type'=> 'coupon_order_status'));
        $body = array('receiver_id' => $order->user_id,'title' => 'Order Status Changed' ,'message' => 'Order Delivered Successfully','content_available' => true,'type' => 'coupon_order_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
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

        $newData  = json_encode(array('type'=> 'coupon_order_status'));
        $body = array('receiver_id' => $book->user_id,'title' => 'Order Status Declined' ,'message' => 'Order Declined','content_available' => true,'type' => 'coupon_order_status', 'data' => $newData);

        $sendNotification = $this->fcmNotificationService->sendFcmAdminNotification($body);
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

    public function slots($id) {
        $slotdata =  DB::table('coupons_order')
            ->leftJoin('coupon_slot_booked_sellers', 'coupon_slot_booked_sellers.order_id', '=', 'coupons_order.id')
            ->select('coupon_slot_booked_sellers.slot', DB::raw("coupons_order.quantity AS quantity"), DB::raw("coupons_order.user_id AS user_id"), DB::raw("coupons_order.event_id AS event_id"), DB::raw("coupon_slot_booked_sellers.id AS slot_id"))
              ->where('coupons_order.status', '=', '1')
            ->where('coupon_slot_booked_sellers.order_id', '=', $id)
            ->orderBy('coupons_order.created_at', 'desc')
            ->get();
        return view('orders.edit', compact('slotdata','id'));
    }

    public function updateslot(Request $request, $id) {

        $request->validate([
            'addmore.*.from' => 'required',
            'addmore.*.to' => 'required',
        ]);
        $quantity = (int)$request->quantity;
        $slotCal = $quantity / 100;

        DB::table('seller_coupons')
        ->where('order_id', $id)
        ->update([
            'status' => '0'
        ]);

        foreach ($request->addmore as $key => $value) {
            $coupon_range_from = $value['from'];
            $coupon_range_to = $value['to'];

            $from[] = (int)$coupon_range_from;
            $to[] = (int)$coupon_range_to;
            $slotBooked[$key] = $coupon_range_from .' - '. $coupon_range_to;
        }
        $arr_unique_from = array_unique($from);
        
        $check_from = count($from) !== count($arr_unique_from);
        $arr_unique_to = array_unique($to);
        $check_to = count($from) !== count($arr_unique_to);
        if($check_from == 1 || $check_to == 1) {
            return redirect()->route("order.create", 'oid='.base64_encode($id).'/'.base64_encode($request->event_id).'/'.base64_encode($request->user_id).'/'.base64_encode($quantity))
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
                    $data_insert[] = ['coupon_number' => $j,'order_id' => $id,'event_id' => $request->event_id,'user_id' => $request->user_id, 'created_at' => date('Y-m-d H:i:s')];
                }
            }
        
            DB::table('seller_coupons')->insert($data_insert);

            //slot data by Seller
            if($slotBooked) {
                foreach ($slotBooked as $key => $value) {
                    DB::table('coupon_slot_booked_sellers')->where('order_id', $id)->where('id', $key)
                    ->update([
                       'user_id' => $request->user_id, 'order_id' => $id, 'slot'=> $value
                    ]);
                }
            }
           
            return redirect()->route('order.index')
            ->with('success', 'Order Approved successfully.');

        } else {
            return redirect()->route("order.create", 'oid='.base64_encode($id).'/'.base64_encode($request->event_id).'/'.base64_encode($request->user_id).'/'.base64_encode($quantity))
            ->with('warning', 'Order Quantity Mismatch!. Please add proper Quantity.');
        }
    }
}
