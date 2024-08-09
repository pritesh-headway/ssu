<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\AssignCoupon;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Prize;
use App\Models\Bill;
use App\Models\Cms;
use App\Models\Social;
use App\Models\Notice;
use App\Models\Document;
use App\Models\Slab;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public $per_page_show;
    public $base_url;
    public $profile_path;
    public $banner_path;
    public $event_path;
    public $receipt_path;
    public $event_video_path;
    public $bill_path;
    public $doc_path;
    public $prize_path;
    public function __construct()
    {
        $this->per_page_show = 20;
        $this->base_url = url('/');
        $this->profile_path = '/public/profile_images/';
        $this->banner_path = '/public/banner_images/';
        $this->event_path = '/public/event_images/';
        $this->receipt_path = '/public/receipt_images/';
        $this->event_video_path = '/public/event_videos/';
        $this->bill_path = '/public/bills/';
        $this->doc_path = '/public/documents/';
        $this->prize_path = '/public/prize_images/';
    }
    /**
     * Display a listing of the resource.
     */
    public function logincheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:10|digits:10',
            'otp' => 'required|min:4|digits:4',
            'user_type' => 'required',
            'device_token' => 'required',
            'device_type' => 'required',
            'country_code' => 'required|digits:2',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $mobile = $request->mobile;
        $otp = $request->otp;
        $device_token = $request->device_token;
        $device_type = $request->device_type;
        $base_url = $this->base_url;
        $user = User::where('phone_number', $mobile)->first();

        if (!$user || $user->otp !== $otp || Carbon::now()->greaterThan($user->otp_expires_at)) {
            $result['status'] = false;
            $result['message'] = 'Invalid OTP or OTP expired';
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // Create token or session
        $token = $user->createToken('authToken')->plainTextToken;

        // OTP is valid, proceed to authenticate the user
        $userData = [
            'id' => (string) $user->id,
            'user_id' => (string) $user->id,
            'user_type' => (string) $user->user_type,
            'name' => (string) $user->name,
            'lname' => (string) $user->lname,
            'storename' => (string) $user->storename,
            'email' => (string) $user->email,
            'date_of_birth' => (string) $user->date_of_birth,
            'phone_number' => (string) $user->phone_number,
            'otp' => (string) $user->otp,
            'PAN' => (string) $user->PAN,
            'GST' => (string) $user->GST,
            'flatNo' => (string) $user->flatNo,
            'pincode' => (string) $user->pincode,
            'area' => (string) $user->area,
            'city' => (string) $user->city,
            'state' => (string) $user->state,
            'avatar' => ($user->avatar) ? $base_url . $this->profile_path . $user->avatar : '',
            'is_first_time' => $user->is_first_time,
            'token' => $token,
        ];

        $user->otp = null; // Clear the OTP
        $user->otp_expires_at = null; // Clear OTP expiration
        $user->is_first_time = 0;
        $user->remember_token = $token;
        $user->device_token = $device_token;
        $user->device_type = $device_type;
        $user->save();

        // add token devices login
        $arr = [
            'status' => 1,
            'device_token' => $device_token,
            'login_token' => $token,
            'device_type' => $request->device_type,
            'user_id' => $user->id,
        ];
        DB::table('user_devices')->insertGetId($arr);

        return response()->json(['status' => true, 'message' => 'Login successfully', 'data' => $userData]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:10|digits:10',
            'country_code' => 'required|digits:2',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $mobile = $request->mobile;
        $base_url = $this->base_url;
        $otp = '0096'; //rand(100000, 999999);
        $otpExpiresAt = Carbon::now()->addMinutes(60);
        DB::enableQueryLog();

        $chkUser = User::where('phone_number', $mobile)->first();
        if ($chkUser) {
            $chkUser->otp = $otp;
            $chkUser->otp_expires_at = $otpExpiresAt;
            $chkUser->save();
        } else {
            $result['status'] = false;
            $result['message'] = "This number does not exist. Please contact to administration";
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }
        // Send OTP via SMS

        // Implement your SMS sending logic here.
        $user = [
            'id' => (string) $chkUser->id,
            'user_id' => (string) $chkUser->id,
            'user_type' => (string) $chkUser->user_type,
            'name' => (string) $chkUser->name,
            'lname' => (string) $chkUser->lname,
            'storename' => (string) $chkUser->storename,
            'email' => (string) $chkUser->email,
            'date_of_birth' => (string) $chkUser->date_of_birth,
            'phone_number' => (string) $chkUser->phone_number,
            'otp' => (string) $chkUser->otp,
            'PAN' => (string) $chkUser->PAN,
            'GST' => (string) $chkUser->GST,
            'flatNo' => (string) $chkUser->flatNo,
            'pincode' => (string) $chkUser->pincode,
            'area' => (string) $chkUser->area,
            'city' => (string) $chkUser->city,
            'state' => (string) $chkUser->state,
            'avatar' => ($chkUser->avatar) ? $base_url . $this->profile_path . $chkUser->avatar : '',
        ];
        return response()->json(['status' => true, 'message' => 'OTP sent successfully.', 'data' => $user]);
    }

    /**
     * Logout functionality
     */
    public function logout(Request $request)
    {

        auth()->logout();
        $token = $request->header('token');
        $user = User::where('id', $request->user_id)->first();
        $user->status = 0;
        $user->save();

        DB::table('user_devices')
            ->join("users", "user_devices.user_id", "=", "users.id")
            ->where("user_devices.login_token", "=", $token)
            ->where("users.id", "=", $request->user_id)
            ->update(["user_devices.status" => 0, "user_devices.updated_at" => date("Y-m-d H:i:s"), 'user_devices.device_token' => '']);

        $result['status'] = true;
        $result['message'] = "Logout Successfully";
        $result['data'] = (object) [];

        return response()->json($result, 200);
    }

    /**
     * get banner list data.
     */
    public function getBanners(Request $request)
    {
        $user_id = $request->user_id;
        $page_number = $request->page;
        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }
        $banner = Banner::select(DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->banner_path . "', image),'') AS image"), 'id', 'banner_name', 'status', 'banner_type')->where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $banner->total(),
            'count' => $banner->count(),
            'per_page' => $banner->perPage(),
            'current_page' => $banner->currentPage(),
            'total_pages' => $banner->lastPage(),
        ];

        $dataBanners = [
            'pagination' => $pagination,
            'data' => $banner,
        ];

        return response()->json(['status' => true, 'message' => 'Get Banner list successfully', 'data' => $dataBanners], 200);
    }

    /**
     * get events list data.
     */
    public function getEvents(Request $request)
    {
        $user_id = $request->user_id;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        $events = Event::select('id', 'event_name', 'start_date', 'end_date', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->event_path . "', image),'') AS image"), 'prize', 'event_location', DB::raw("YEAR(start_date) as Year"))->where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $events->total(),
            'count' => $events->count(),
            'per_page' => $events->perPage(),
            'current_page' => $events->currentPage(),
            'total_pages' => $events->lastPage(),
        ];

        $dataEvents = [
            'pagination' => $pagination,
            'data' => $events,
        ];

        return response()->json(['status' => true, 'message' => 'Get Events list successfully', 'data' => $dataEvents], 200);
    }

    /**
     * get events list data.
     */
    public function getEventDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        $event_id = $request->event_id;
        $events = Event::leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
            ->select('events.*', 'event_details.image', 'event_details.video')
            ->where('events.id', '=', $event_id)
            ->where('events.status', '=', 1)
            ->get();

        $datas = [];
        foreach ($events as $key => $value) {
            $evnt['id'] = $value['id'];
            $evnt['event_name'] = $value['event_name'];
            $evnt['start_date'] = $value['start_date'];
            $evnt['end_date'] = $value['end_date'];
            $evnt['prize'] = $value['prize'];
            $evnt['event_location'] = $value['event_location'];
            $evnt['images'][] = ($value['image']) ? $base_url . $value['image'] : '';
            $evnt['videos'][] = ($value['video']) ? $base_url . $value['video'] : '';
            $datas = $evnt;
        }
        return response()->json(['status' => true, 'message' => 'Get Events details successfully', 'data' => $datas], 200);
    }

    /**
     * get ssu member list data.
     */
    public function getSellerList(Request $request)
    {
        $user_id = $request->user_id;
        $search = $request->search;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        if($search == "") {
            $seller = User::where('user_type', '2')->where('status', 1)->where('id', '!=', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);
        } else {
            $seller = User::where('user_type', '2')->where('storename', 'like', '%' . $search . '%')->orWhere('city','like', '%' . $search . '%')->where('status', 1)->where('id', '!=', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);
        }
        $userData = [];
        foreach ($seller as $key => $users) {
            $userData[] = [
                'id' => (string) $users->id,
                'user_id' => (string) $users->id,
                'user_type' => (string) $users->user_type,
                'name' => (string) $users->name,
                'lname' => (string) $users->lname,
                'storename' => (string) $users->storename,
                'email' => (string) $users->email,
                'date_of_birth' => (string) $users->date_of_birth,
                'phone_number' => (string) $users->phone_number,
                'otp' => (string) $users->otp,
                'PAN' => (string) $users->PAN,
                'GST' => (string) $users->GST,
                'flatNo' => (string) $users->flatNo,
                'pincode' => (string) $users->pincode,
                'area' => (string) $users->area,
                'city' => (string) $users->city,
                'state' => (string) $users->state,
                'avatar' => ($users->avatar) ? $base_url . $this->profile_path . $users->avatar : '',
                'is_first_time' => $users->is_first_time,
                'lat' => "",
                'long' => "",
            ];
        }

        $pagination = [
            'total' => $seller->total(),
            'count' => $seller->count(),
            'per_page' => $seller->perPage(),
            'current_page' => $seller->currentPage(),
            'total_pages' => $seller->lastPage(),
        ];

        $dataSellers = [
            'pagination' => $pagination,
            'data' => $userData,
        ];

        return response()->json(['status' => true, 'message' => 'Get Seller list successfully', 'data' => $dataSellers], 200);
    }

    /**
     * get seller Customer list data.
     */
    public function getCustomerList(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // assign coupons list for seller wise
        $listSellerCustomer = DB::table('assign_customer_coupons')
            ->select('assign_customer_coupons.customer_id', DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"), DB::raw("CONCAT(users2.name, ' ', users2.lname) AS seller_name"), 'users.city', 'users.phone_number', DB::raw("sum(assign_customer_coupons.coupon_number) AS totalCoupon"))
            ->leftJoin('users', 'users.id', '=', 'assign_customer_coupons.customer_id')
            ->leftJoin('users AS users2', 'users2.id', '=', 'assign_customer_coupons.user_id')
            ->where('assign_customer_coupons.user_id', '=', $user_id)
            ->where('assign_customer_coupons.event_id', '=', $event_id)
            ->groupBy('assign_customer_coupons.customer_id')
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $listSellerCustomer->total(),
            'count' => $listSellerCustomer->count(),
            'per_page' => $listSellerCustomer->perPage(),
            'current_page' => $listSellerCustomer->currentPage(),
            'total_pages' => $listSellerCustomer->lastPage(),
        ];

        $dataAssignedCouponList = [
            'pagination' => $pagination,
            'data' => $listSellerCustomer,
        ];
        return response()->json(['status' => true, 'message' => 'Get Customer list successfully', 'data' => $dataAssignedCouponList], 200);
    }

    /**
     * get assigned coupons list data.
     */
    public function getAssignedCouponslist(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // assign coupons list for seller wise
        $assigneCouponListSeller = DB::table('assign_customer_coupons')
            ->select('assign_customer_coupons.coupon_number', 'assign_customer_coupons.customer_id', DB::raw("DATE_FORMAT(assign_customer_coupons.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"), 'users.city', 'users.phone_number')
            ->leftJoin('users', 'users.id', '=', 'assign_customer_coupons.customer_id')
            ->leftJoin('events', 'events.id', '=', 'assign_customer_coupons.event_id')
            ->where('assign_customer_coupons.user_id', '=', $user_id)
            ->where('assign_customer_coupons.event_id', '=', $event_id)
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $assigneCouponListSeller->total(),
            'count' => $assigneCouponListSeller->count(),
            'per_page' => $assigneCouponListSeller->perPage(),
            'current_page' => $assigneCouponListSeller->currentPage(),
            'total_pages' => $assigneCouponListSeller->lastPage(),
        ];

        $dataAssignedCouponList = [
            'pagination' => $pagination,
            'data' => $assigneCouponListSeller,
        ];

        return response()->json(['status' => true, 'message' => 'Get Assigned Coupons list successfully', 'data' => $dataAssignedCouponList], 200);
    }

    /**
     * add seller register.
     */
    public function addSeller(Request $request)
    {
        $user = new User();
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'email' => 'email',
            'lname' => 'required|string',
            'storename' => 'required|string',
            'phone_number' => 'required|min:10|digits:10|unique:users',
            'flatNo' => 'required|string',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // add user details
        $user->name = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->storename = $request->input('storename');
        $user->PAN = $request->input('PAN');
        $user->GST = $request->input('GST');
        $user->flatNo = $request->input('flatNo');
        $user->pincode = $request->input('pincode');
        $user->password = Hash::make('123456');
        $user->area = $request->input('area');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->save();

        // Return a response
        return response()->json(['status' => true, 'message' => 'Seller created successfully', 'data' => $user], 200);
    }

    /**
     * add customer register.
     */
    public function addCustomer(Request $request)
    {
        $user = new User();
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'email' => 'email',
            'lname' => 'required|string',
            'storename' => 'required|string',
            'phone_number' => 'required|min:10|digits:10|unique:users',
            'flatNo' => 'required|string',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // add user details
        $user->name = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->storename = $request->input('storename');
        $user->PAN = $request->input('PAN');
        $user->GST = $request->input('GST');
        $user->flatNo = $request->input('flatNo');
        $user->pincode = $request->input('pincode');
        $user->password = Hash::make('123456');
        $user->area = $request->input('area');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->user_type = '3';
        $user->save();

        // Return a response
        return response()->json(['status' => true, 'message' => 'Customer created successfully', 'data' => $user], 200);
    }

    /**
     * assign coupon customer.
     */
    public function assignCoupon(Request $request)
    {
        $token = $request->header('token');
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'customer_name' => ($request->customer_id == '0') ? 'required|string' : '',
            'customer_city' => ($request->customer_id == '0') ? '' : '',
            'customer_phone' => 'required|min:10|digits:10',
            'assign_type' => 'required',
            'coupon_number' => ($request->assign_type == '1') ? 'required' : '',
            'coupon_range_from' => ($request->assign_type == '2') ? 'required' : '',
            'coupon_range_to' => ($request->assign_type == '2') ? 'required' : '',
            'multiple_coupon' => ($request->assign_type == '3') ? 'required' : '',
            'user_id' => 'required',
            'event_id' => 'required',
            'customer_id' => ($request->customer_id != '0') ? 'required' : '',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }
        $assign_coupons = new AssignCoupon();
        $user_id = $request->user_id;
        $event_id = $request->event_id;

        // check assign coupon counts for specific events
        $couponCountSeller = DB::table('coupons_order')
            ->select(DB::raw('SUM(coupons_order.quantity) as quantity'))
            ->where('coupons_order.user_id', '=', $user_id)
            ->where('coupons_order.event_id', '=', $event_id)
            ->where('coupons_order.order_status', '=', '3')
            ->groupBy('coupons_order.event_id')
            ->groupBy('coupons_order.user_id')
            ->get();

        // coupons deliverd check
        if(count($couponCountSeller) == 0) {
            $result['status'] = false;
            $result['message'] = 'Coupons not available. Please purchase the another.';
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // assign coupons count for seller
        $assigneCouponCountSeller = DB::table('assign_customer_coupons')
            ->select(DB::raw('COUNT(assign_customer_coupons.coupon_number) as totalAssignCoupon'))
            ->where('assign_customer_coupons.user_id', '=', $user_id)
            ->where('assign_customer_coupons.event_id', '=', $event_id)
            ->groupBy('assign_customer_coupons.event_id')
            ->groupBy('assign_customer_coupons.user_id')
            ->get();

        // Seller coupons exist with status 0
        $couponListSeller = DB::table('seller_coupons')
            // ->select(DB::raw('CAST(seller_coupons.coupon_number AS integer) as coupon_number'))
            ->select('seller_coupons.coupon_number')
            ->where('seller_coupons.user_id', '=', $user_id)
            ->where('seller_coupons.event_id', '=', $event_id)
            ->where('seller_coupons.is_assign', '=', 0)
            ->get();
        $arrSellerCoupons = [];
        foreach ($couponListSeller as $key => $selCoup) {
            $arrSellerCoupons[] = $selCoup->coupon_number;
        }

        $total_coupon_qty = isset($couponCountSeller[0]->quantity) ? (int)$couponCountSeller[0]->quantity : 0;
        $remaining_seller_coupons = bcsub((int) $total_coupon_qty , isset($assigneCouponCountSeller[0]->totalAssignCoupon) ? (int) $assigneCouponCountSeller[0]->totalAssignCoupon : 0);

        if ($request->input('assign_type') == 1) {
            if ($remaining_seller_coupons <= $request->input('coupon_number')) {
                $result['status'] = false;
                $result['message'] = 'You have finish the coupons. Please purchase the another.';
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            if (!in_array((string) $request->input('coupon_number'), $arrSellerCoupons)) {
                $result['status'] = false;
                $result['message'] = 'Coupon does not exist or may be coupons are assigned to another, Please contact to administration.';
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

        } else if ($request->input('assign_type') == 2) {
            $coupon_range_from = $request->coupon_range_from;
            $coupon_range_to = $request->coupon_range_to;

            $coupon_range_from = explode(',', $coupon_range_from);
            $coupon_range_to = explode(',', $coupon_range_to);

            for ($i = 0; $i < count($coupon_range_from); $i++) {
                $from = (int) $coupon_range_from[$i];
                $to = (int) $coupon_range_to[$i];
                $couponDiff = abs($from - $to);
                
                if ($remaining_seller_coupons <= $couponDiff) {
                    $result['status'] = false;
                    $result['message'] = 'You have finish the coupons. Please purchase the another.';
                    $result['data'] = (object) [];
                    return response()->json($result, 200);
                }
                // "Looping from $from to $to:\n";
                for ($j = $from; $j <= $to; $j++) {
                    if (!in_array((string)$j, $arrSellerCoupons)) {
                        $result['status'] = false;
                        $result['message'] = 'Coupon does not exist or may be coupons are assigned to another, Please contact to administration.';
                        $result['data'] = (object) [];
                        return response()->json($result, 200);
                    }
                }
            }
        } else if ($request->input('assign_type') == 3) {
            $multiple_coupon = $request->multiple_coupon;
            $multiple_coupon = explode(',', $multiple_coupon);
            // check coupons count
            $couponDiff = count($multiple_coupon);
            if ((int) $remaining_seller_coupons <= $couponDiff) {
                $result['status'] = false;
                $result['message'] = 'You have finish the coupons. Please purchase the another.';
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            foreach ($multiple_coupon as $key => $coupon) {
                if (!in_array((string) $coupon, $arrSellerCoupons)) {
                    $result['status'] = false;
                    $result['message'] = 'Coupon does not exist or may be coupons are assigned to another, Please contact to administration.';
                    $result['data'] = (object) [];
                    return response()->json($result, 200);
                }
            }

        }

        $last_insert_id = 0;
        if ($request->customer_id == "") {
            $users = new User();
            $name = explode(" ", $request->customer_name);
            // check user exist
            $userCheck = User::where('name', ($name[0]) ? $name[0] : '')->where('lname', ($name[1]) ? $name[1] : '')->count();
            if ($userCheck < 0) {
                $users->name = ($name[0]) ? $name[0] : '';
                $users->lname = ($name[1]) ? $name[1] : '';
                $users->city = $request->customer_city;
                $users->phone_number = $request->customer_phone;
                $users->user_type = '3';
                $users->password = Hash::make('123456');
                $users->save();
                $last_insert_id = $users->id;
            }
        }

        // single coupon
        if ($request->input('assign_type') == 1) {
            $data_insert = [
                'assign_type' => $request->input('assign_type'),
                'user_id' => $request->input('user_id'),
                'event_id' => $request->input('event_id'),
                'customer_id' => ($request->customer_id != '') ? $request->customer_id : $last_insert_id,
                'coupon_number' => $request->input('coupon_number'),
            ];

        } else if ($request->input('assign_type') == 2) {
            // range coupon
            $coupon_range_from = $request->coupon_range_from;
            $coupon_range_to = $request->coupon_range_to;

            $coupon_range_from = explode(',', $coupon_range_from);
            $coupon_range_to = explode(',', $coupon_range_to);

            for ($i = 0; $i < count($coupon_range_from); $i++) {
                $from = (int) $coupon_range_from[$i];
                $to = (int) $coupon_range_to[$i];
                // "Looping from $from to $to:\n";
                for ($j = $from; $j <= $to; $j++) {
                    $data_insert[] = [
                        'assign_type' => $request->input('assign_type'),
                        'event_id' => $request->input('event_id'),
                        'user_id' => $request->input('user_id'),
                        'customer_id' => ($request->customer_id != '') ? $request->customer_id : $last_insert_id,
                        'coupon_number' => $j,
                    ];
                }
            }

        } else if ($request->input('assign_type') == 3) {
            $multiple_coupon = $request->multiple_coupon;
            $multiple_coupon = explode(',', $multiple_coupon);
            foreach ($multiple_coupon as $value) {
                $data_insert[] = [
                    'assign_type' => $request->input('assign_type'),
                    'user_id' => $request->input('user_id'),
                    'event_id' => $request->input('event_id'),
                    'customer_id' => ($request->customer_id != '') ? $request->customer_id : $last_insert_id,
                    'coupon_number' => $value,
                ];
            }
        }

        $coupons_check_assigned = array_column($data_insert, 'coupon_number');
        $check_coupons = DB::table('seller_coupons')
            ->select('seller_coupons.id', 'seller_coupons.coupon_number')
            ->where('seller_coupons.user_id', '=', $user_id)
            ->where('seller_coupons.event_id', '=', $event_id)
            ->whereIn('seller_coupons.coupon_number', $coupons_check_assigned)
            ->where('seller_coupons.is_assign', '=', 1)->count();

        if ($check_coupons > 0) {
            $result['status'] = false;
            $result['message'] = 'Coupon al-ready assigned to another. Please contact to administration';
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // flag change to assign
         DB::table('seller_coupons')
            ->where("seller_coupons.user_id", "=", $user_id)
            ->where("seller_coupons.event_id", "=", $event_id)
            ->whereIn('coupon_number', $coupons_check_assigned)
            ->update(["seller_coupons.is_assign" => 1,'seller_coupons.updated_at' => date('Y-m-d H:i:s')]);

        // assign coupons entry
        AssignCoupon::insert($data_insert);

        // Return a response
        $assign_coupons = (object) [];
        return response()->json(['status' => true, 'message' => 'Coupons Assign successfully', 'data' => $assign_coupons], 200);
    }

    /**
     * get dashboard data data.
     */
    public function getDashboardData(Request $request)
    {
        $base_url = $this->base_url;
        $user_id = $request->user_id;
        $token = $request->header('token');
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        $events = Event::select('id', 'event_name', 'start_date', 'end_date', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->event_path . "', image),'') AS image"), 'prize', 'event_location')->where('status', 1)->where('status', 1)->whereDate('start_date','<=', DB::raw('CURDATE()'))->orderBy('start_date', 'DESC')->take(1)->get();

        $Oldevents = Event::select('id', 'event_name', 'start_date', 'end_date', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->event_path . "', image),'') AS image"), 'prize', 'event_location')->where('status', 1)->where('status', 1)->whereYear('start_date','<', date('Y'))->orderBy('start_date', 'DESC')->take(1)->first();

        $banner = Banner::select('id', 'banner_name', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->banner_path . "', image),'') AS image"))->where('status', 1)->get();
        $event_id = isset($events[0]['id']) ? $events[0]['id'] : 0;

        $couponCountTotal = DB::table('seller_coupons')
            ->select(DB::raw('COUNT(seller_coupons.coupon_number) as quantity'))
            ->where('seller_coupons.user_id', '=', $user_id)
            ->where('seller_coupons.event_id', '=', $event_id)
            ->groupBy('seller_coupons.event_id')
            ->groupBy('seller_coupons.user_id')
            ->get();
        $totalQty = isset($couponCountTotal[0]) ? $couponCountTotal[0]->quantity : 0;

        $couponCountSold = DB::table('seller_coupons')
            ->select(DB::raw('COUNT(seller_coupons.coupon_number) as quantity'))
            ->where('seller_coupons.user_id', '=', $user_id)
            ->where('seller_coupons.event_id', '=', $event_id)
            ->where('seller_coupons.is_assign', '=', '1')
            ->groupBy('seller_coupons.event_id')
            ->groupBy('seller_coupons.user_id')
            ->get();
        $totalQtySold = isset($couponCountSold[0]) ? $couponCountSold[0]->quantity : 0;

        $countCustomerCoupon = DB::table('assign_customer_coupons')
                            ->select(DB::raw("COUNT(assign_customer_coupons.coupon_number) AS totalCoupon"))
                            ->where('assign_customer_coupons.user_id', '=', $user_id)
                            ->where('assign_customer_coupons.event_id', '=', $event_id)
                            ->groupBy('assign_customer_coupons.customer_id')->get();
        $customer_coupons_total_count = isset($countCustomerCoupon[0]) ? $countCustomerCoupon[0]->totalCoupon : 0;

        $remainigCoupon = $totalQty - $totalQtySold;
        $current_event_name = isset($events[0]->event_name) ? $events[0]->event_name: '';
        $current_event_id = isset($events[0]->id) ? $events[0]->id: '';
        $current_event_year = isset($events[0]->event_name) ? date('Y', strtotime($events[0]->start_date)) : '';
        $current_event_banner_image = isset($events[0]->event_name) ? $events[0]->image : '';
        $slabArr = Slab::select('slabs.id', 'slabs.min_coupons', 'slabs.max_coupons', 'slabs.prize', 'slabs.event_id', 'slabs.status','events.event_name')->leftJoin('events', 'events.id', '=', 'slabs.event_id')->where('slabs.status', 1)->get();
        $cmsData = Cms::all()->where('status', 1);

        $socialData = Social::all()->where('status', 1);
        $noticeData = Notice::all()->where('status', 1);

        $generalData = [
             'current_event_id' => $current_event_id,
             'winner_info_html' => $noticeData[0]->content,
             'prize_info_html' => $noticeData[1]->content,
             'min_coupon_order' => 1000,
             'max_coupon_order' => 2000,
             'ssu_email_support' => 'ssu@suport.com',
             'ssu_phone_support' => '+91 9865457821',
             'fb' => $socialData[0]->link,
             'youtube' => $socialData[3]->link,
             'insta' => $socialData[1]->link,
             'twitter' => $socialData[2]->link,
             'privacy_policy' => $cmsData[1]->content,
             'terms_condition' => $cmsData[2]->content,
             'faq' => $cmsData[0]->content,
             'slab' => $slabArr
        ];
        $all_data = array(
            'total_coupons' => $totalQty,
            'sold_coupons' => $totalQtySold,
            'remaining_coupons' => $remainigCoupon,
            'current_event_name' => $current_event_name,
            'current_event_year' => $current_event_year,
            'current_event_banner_image' => $current_event_banner_image,
            'last_event_name' => $Oldevents->event_name,
            'last_event_year' => '',
            'last_event_id' => $Oldevents->id,
            'customer_coupons_total_count' => $customer_coupons_total_count,
            'generalData' => (object)$generalData,
            'recent_event' => $Oldevents,
            'bannerList' => $banner,
        );

        return response()->json(['status' => true, 'message' => 'Get Dashboard data successfully', 'data' => $all_data], 200);
    }

    /**
     * get coupon order list data.
     */
    public function getCouponsOrderList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $page_number = $request->page;

        $couponData = DB::table('coupons_order')
            ->leftJoin('users', 'users.id', '=', 'coupons_order.user_id')
            ->leftJoin('events', 'events.id', '=', 'coupons_order.event_id')
            ->select('coupons_order.id', 'coupons_order.quantity', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->receipt_path . "', coupons_order.receipt_payment),'') AS receipt_payment"), 'users.storename', DB::raw("CONCAT(users.name, ' ',users.lname) AS seller_name"), DB::raw("CASE WHEN coupons_order.order_status = '0' THEN 'Pending' WHEN coupons_order.order_status = '1' THEN 'Approved' WHEN coupons_order.order_status = '2' THEN 'Declined' WHEN coupons_order.order_status = '3' THEN 'Delivered' ELSE 'Pending' END AS order_status"), 'events.event_location', DB::raw("DATE_FORMAT(coupons_order.created_at, '%d %M %Y %h:%i %p') AS order_date"),'events.event_name')
            ->where('coupons_order.user_id', '=', $user_id)
            ->where('coupons_order.event_id', '=', $event_id)
            ->orderBy('coupons_order.created_at', 'desc')
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $couponData->total(),
            'count' => $couponData->count(),
            'per_page' => $couponData->perPage(),
            'current_page' => $couponData->currentPage(),
            'total_pages' => $couponData->lastPage(),
        ];

        $data_coupon = [
            'pagination' => $pagination,
            'data' => $couponData,
        ];
        return response()->json(['status' => true, 'message' => 'Get Coupons Order list successfully', 'data' => $data_coupon], 200);
    }

    /**
     * Order Coupon Data.
     */
    public function orderCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric',
            'receipt_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $user_id = $request->user_id;
        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        if ($request->hasFile('receipt_payment')) {
            $image = $request->file('receipt_payment');
            $destinationPath = 'public/receipt_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $arr = [
                'receipt_payment' => $profileImage,
                'status' => 1,
                'user_id' => $request->user_id,
                'quantity' => $request->quantity,
                'event_id' => $request->event_id,
            ];
            $order_id = DB::table('coupons_order')->insertGetId($arr);

            return response()->json(['status' => true, 'message' => 'Order sent successfully', 'data' => ['order_id' => $order_id]], 200);
        }
    }

    /**
     * get events video list data.
     */
    public function getEventVideoList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'page' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }
        $event_id = $request->event_id;
        $events = Event::leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
            ->select('events.*', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->event_video_path . "', event_details.video),'') AS video"), 'event_details.title')
            ->where('events.id', '=', $event_id)
            ->where('events.status', '=', 1)
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $datas = [];
        $pagination = [
            'total' => $events->total(),
            'count' => $events->count(),
            'per_page' => $events->perPage(),
            'current_page' => $events->currentPage(),
            'total_pages' => $events->lastPage(),
        ];
        foreach ($events as $key => $value) {
            $evnt['id'] = $value['id'];
            $evnt['event_name'] = $value['event_name'];
            $evnt['event_title'] = $value['title'];
            $evnt['event_year'] = date('Y', strtotime($value['start_date']));
            $evnt['start_date'] = date('d/m/Y', strtotime($value['start_date']));
            $evnt['end_date'] = date('d/m/Y', strtotime($value['end_date']));
            $evnt['prize'] = $value['prize'];
            $evnt['event_location'] = $value['event_location'];
            $evnt['videos'] = ($value['video']) ? $value['video'] : '';
            $datas[] = $evnt;
        }

        $data_event = [
            'pagination' => $pagination,
            'data' => $datas,
        ];
        return response()->json(['status' => true, 'message' => 'Get Events videos successfully', 'data' => $data_event], 200);
    }

    /**
     * get events Image list data.
     */
    public function getEventImagesList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'page' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }
        $event_id = $request->event_id;
        $events = Event::leftJoin('event_details', 'events.id', '=', 'event_details.event_id')
            ->select('events.*', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->event_path . "', event_details.image),'') AS image"), 'event_details.title')
            ->where('events.id', '=', $event_id)
            ->where('events.status', '=', 1)
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $datas = [];
        $pagination = [
            'total' => $events->total(),
            'count' => $events->count(),
            'per_page' => $events->perPage(),
            'current_page' => $events->currentPage(),
            'total_pages' => $events->lastPage(),
        ];
        foreach ($events as $key => $value) {
            $evnt['id'] = $value['id'];
            $evnt['event_name'] = $value['event_name'];
            $evnt['start_date'] = $value['start_date'];
            $evnt['end_date'] = $value['end_date'];
            $evnt['prize'] = $value['prize'];
            $evnt['event_location'] = $value['event_location'];
            $evnt['images'] = ($value['image']) ? $base_url . $value['image'] : '';
            $datas[] = $evnt;
        }
        $data_event = [
            'pagination' => $pagination,
            'data' => $datas,
        ];
        return response()->json(['status' => true, 'message' => 'Get Events images successfully', 'data' => $data_event], 200);
    }

    /**
     * get customer coupon list data.
     */
    public function getCustomerCouponsList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'event_id' => 'required',
            'page' => 'required'
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        $customer_id = $request->customer_id;
        $event_id = $request->event_id;
        $page_number = $request->page;

        $customerData = DB::table('assign_customer_coupons')
           ->leftJoin('users AS users', 'users.id', '=', 'assign_customer_coupons.user_id')
          ->select('assign_customer_coupons.id','assign_customer_coupons.customer_id', 'assign_customer_coupons.coupon_number', DB::raw("DATE_FORMAT(assign_customer_coupons.created_at, '%Y-%m-%d') AS date"), DB::raw("CONCAT(users.name, ' ', users.lname) AS seller_name"))
            ->where('assign_customer_coupons.customer_id', '=', $customer_id)
            ->where('assign_customer_coupons.event_id', '=', $event_id)
            ->orderBy('assign_customer_coupons.created_at', 'desc')
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $customerData->total(),
            'count' => $customerData->count(),
            'per_page' => $customerData->perPage(),
            'current_page' => $customerData->currentPage(),
            'total_pages' => $customerData->lastPage(),
        ];

        $data_coupon = [
            'pagination' => $pagination,
            'data' => $customerData,
        ];
        return response()->json(['status' => true, 'message' => 'Get Customer Coupons list successfully', 'data' => $data_coupon], 200);
    }

    /**
     * get Prize list data.
     */
    public function getPrizeList(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // assign coupons list for seller wise
        $prizeLists = Prize::select('events.event_name','prizes.prize_name','prizes.prize_qty', 'prizes.prize_amount', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->prize_path . "', prizes.image),'') AS image"))->leftJoin('events', 'events.id', '=', 'prizes.event_id')
            ->where('events.id', '=', $event_id)
            ->where('events.status', '=', 1)
            ->where('prizes.status', '=', 1)
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $prizeLists->total(),
            'count' => $prizeLists->count(),
            'per_page' => $prizeLists->perPage(),
            'current_page' => $prizeLists->currentPage(),
            'total_pages' => $prizeLists->lastPage(),
        ];

        $prizeListData = [
            'pagination' => $pagination,
            'data' => $prizeLists,
        ];
        return response()->json(['status' => true, 'message' => 'Get Prize list successfully', 'data' => $prizeListData], 200);
    }

    /**
     * Contact Us form Data.
     */
    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|digits:10',
            'user_id' => 'required',
            'event_id' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $user_id = $request->user_id;
        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        $arr = [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $request->user_id,
                'event_id' => $request->event_id,
                'message' => $request->message,
            ];
        DB::table('contactus')->insertGetId($arr);

        return response()->json(['status' => true, 'message' => 'We will contact you soon. Thank You!', 'data' => []], 200);
    }

    /**
     * download document list data.
     */
    public function downlodDocumentList(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // assign coupons list for seller wise
        $prizeLists = Document::select('id','user_id', 'event_id', 'status', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->doc_path . "', file),'') AS file"))->where('status', '=', 1)
            ->where('user_id', '=', $user_id)
            ->where('event_id', '=', $event_id)
            ->get();

        return response()->json(['status' => true, 'message' => 'Get Document list successfully', 'data' => $prizeLists], 200);
    }

    /**
     * bills list data.
     */
    public function billsList(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
        $token = $request->header('token');
        $page_number = $request->page;
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $billsList = Bill::where('status', '=', 1)
        ->select('id', 'user_id', 'event_id', 'title', 'amount', 'detail', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->bill_path . "', file),'') AS file"), DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->bill_path . "', receipt),'') AS receipt"), DB::raw("CASE WHEN bill_status = '0' THEN 'Pending' WHEN bill_status = '1' THEN 'Approved' WHEN bill_status = '2' THEN 'Declined' WHEN bill_status = '3' THEN 'Completed' ELSE '' END bill_status"), DB::raw("DATE_FORMAT(created_at, '%d %M %Y %h:%i %p') AS date"))
            ->where('user_id', '=', $user_id)
            ->where('event_id', '=', $event_id)
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $billsList->total(),
            'count' => $billsList->count(),
            'per_page' => $billsList->perPage(),
            'current_page' => $billsList->currentPage(),
            'total_pages' => $billsList->lastPage(),
        ];
        $billListData = [
            'pagination' => $pagination,
            'data' => $billsList,
        ];

        return response()->json(['status' => true, 'message' => 'Get Document list successfully', 'data' => $billListData], 200);
    }

    /**
     * bill add form Data.
     */
    public function addBills(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required',
            'detail' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $user_id = $request->user_id;
        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }
        $bill = new Bill;
        $bill->title = $request->title;
        $bill->amount = $request->amount;
        $bill->detail = $request->detail;
        $bill->user_id = $request->user_id;
        $bill->event_id = $request->event_id;
        
        if ($image = $request->file('file')) {
            $destinationPath = 'public/bills/';
            $billFile = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $billFile);
            $bill->file = "$billFile";
        }
        $bill->save();

        return response()->json(['status' => true, 'message' => 'Bill Added successfully', 'data' => []], 200);
    }

    /**
     * bill add form Data.
     */
    public function getGeneralData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $user_id = $request->user_id;
        $token = $request->header('token');
        $base_url = $this->base_url;
        $checkToken = $this->tokenVerify($token);
        // Decode the JSON response
        $userData = json_decode($checkToken->getContent(), true);
        if ($userData['status'] == false) {
            return $checkToken->getContent();
        }
        
        $cmsList = Cms::where('status', '=', 1)->get();
        $socialList = Social::where('status', '=', 1)->get();
        $noticeList = Notice::where('status', '=', 1)->get();
        
        $arr_data = ['pages' => $cmsList, 'social_link' => $socialList, 'notice' => $noticeList];

        return response()->json(['status' => true, 'message' => 'Get General data successfully', 'data' => $arr_data], 200);
    }

    public function tokenVerify($token)
    {
        $base_url = $this->base_url;
        $user = DB::table('user_devices')
            ->where('user_devices.login_token', '=', $token)
            ->where('user_devices.status', '=', 1)
            ->count();
        // dd($user);
        if ($user == '' || $user == null || $user == 0) {
            $result['status'] = false;
            $result['message'] = "Token given is invalid, Please login again.";
            $result['data'] = [];
            return response()->json($result, 200);
        } else {
            $result['status'] = true;
            return response()->json($result, 200);
        }
    }

}
