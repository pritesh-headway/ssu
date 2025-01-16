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
use App\Models\Winner;
use App\Models\Chatmessage;
use App\Models\UserDevices;
use App\Models\Asset;
use App\Models\Meeting;
use App\Models\Reward;
use App\Models\User;
use App\Models\Graphic;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use App\Exports\CouponsExport;
use Maatwebsite\Excel\Facades\Excel;

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
    public $graphic_path;
    public $winner_path;
    public $doc_path;
    public $prize_path;
    protected $curlApiService;
    protected $fcmNotificationService;
    public function __construct(CurlApiService $curlApiService, FcmNotificationService $fcmNotificationService)
    {
        $this->per_page_show = 50;
        $this->base_url = url('/');
        $this->profile_path = '/public/profile_images/';
        $this->banner_path = '/public/banner_images/';
        $this->event_path = '/public/event_images/';
        $this->receipt_path = '/public/receipt_images/';
        $this->event_video_path = '/public/event_videos/';
        $this->bill_path = '/public/bills/';
        $this->graphic_path = '/public/graphics/';
        $this->doc_path = '/public/documents/';
        $this->prize_path = '/public/prize_images/';
        $this->curlApiService = $curlApiService;
        $this->fcmNotificationService = $fcmNotificationService;
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
            // 'device_token' => 'required',
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
        $device_token = isset($request->device_token) ? $request->device_token : '';
        $device_type = $request->device_type;
        $base_url = $this->base_url;
        $user = User::where('phone_number', $mobile)->where('status', 1)->first();
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
        $otp = rand(1000, 9999); //'0096';  
        $otpExpiresAt = Carbon::now()->addMinutes(1);
        DB::enableQueryLog();

         // Send OTP via SMS
        $phoneNumber = $mobile;
        $optionalKey = $request->hashKey;
        $chkUser = User::where('phone_number', $mobile)->where('status', 1)->first();

        $message = 'Dear Customer,
Your SSU App Login OTP is '.$otp.'
Do not share this code with anyone.
www.headway.guru
'.$optionalKey.'';
        $data['SenderID'] = 'HBSSSU';
        $data['SMSType'] = 4;
        $data['Mobile'] = $phoneNumber;
        $data['EntityID'] = env('API_ENTITY_ID');
        $data['TemplateID'] = env('API_Template_ID');
        $data['MsgText'] = $message;
        if ($chkUser) {
            if($mobile != '9879879879' && $mobile != '7874600096' && $mobile != '7567300096' && $mobile != '9970831750') { // remove once live apk
                $chkUser->otp = $otp;
                $chkUser->otp_expires_at = $otpExpiresAt;
                $chkUser->save();
                $response = $this->curlApiService->postRequest(env('API_KEY'), $data);
                if(strpos($response, "ok") !== false){
                    $result['status'] = true;
                    $result['message'] = "OTP SEND";
                    $result['data'] = (object) [];
                    // return response()->json($result, 200);
                } else {
                    $result['status'] = false;
                    $result['message'] = "OTP NOT SEND".$response;
                    $result['data'] = (object) [];
                    // return response()->json($result, 200);
                }
            } else {
                $data= [];
                $chkUser->otp = '0096';
                $chkUser->otp_expires_at = $otpExpiresAt;
                $chkUser->save();
            }
            
        } else {

            $response = $this->curlApiService->postRequest(env('API_KEY'), $data);
            if(strpos($response, "ok") !== false){
                $result['status'] = true;
                $result['message'] = "OTP SEND";
                $result['data'] = (object) [];
                // return response()->json($result, 200);
            } else {
                $result['status'] = false;
                $result['message'] = "OTP NOT SEND".$response;
                $result['data'] = (object) [];
                // return response()->json($result, 200);
            }
            $chkUser = new User();
            $chkUser->otp = $otp;
            $chkUser->phone_number = $phoneNumber;
            $chkUser->otp_expires_at = $otpExpiresAt;
            $chkUser->user_type = '4';
            $chkUser->password = Hash::make('123456');
            $chkUser->save();
            // $result['status'] = false;
            // $result['message'] = "This number does not exist. Please contact to administration";
            // $result['data'] = (object) [];
            // return response()->json($result, 200);
        }
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
            'otp' => (string) $otp,
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
        $user = User::where('id', $request->user_id)->where('status', '1')->first();

        $userDevice = UserDevices::where('user_id', $request->user_id)->where('login_token', $token)->where('status', '1')->first();
        if($userDevice) {
            $userDevice->device_token = '';
            $userDevice->status = '0';
            $userDevice->updated_at = date("Y-m-d H:i:s");
            $userDevice->save();
        }

        DB::table('user_devices')
            ->join("users", "user_devices.user_id", "=", "users.id")
            ->where("user_devices.login_token", "=", $token)
            ->where("user_devices.user_id", "=", $request->user_id)
            ->update(["user_devices.status" => '0', "user_devices.updated_at" => date("Y-m-d H:i:s"), 'user_devices.device_token' => '']);

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
        $offset = ($page_number - 1) * $this->per_page_show;
        if($search == "") {
            // DB::enableQueryLog();
            $sellers = User::select('users.id', 'users.user_id', 'users.user_type', 'users.name', 'users.lname', 'users.storename', 'users.email', 'users.phone_number', 'users.city', 'users.avatar', 'users.PAN', 'users.GST', 'users.flatNo', 'users.pincode', 'users.area', 'users.state', 'users.is_first_time')
            ->where('users.user_type', '2')->where('users.status', 1)->where('users.id', '!=', 1)->where('users.id', '!=', $user_id)->groupBy('users.id')->paginate($this->per_page_show, ['*'], 'page', $page_number);
            // $query = DB::getQueryLog();
           
            $seller = DB::select("SELECT users.id,users.user_id,users.user_type, users.name, users.lname,users.storename ,users.email,users.phone_number,users.area,users.state,users.is_first_time,users.date_of_birth,users.otp,users.PAN,users.GST,users.flatNo,users.pincode,users.city,users.avatar ,chat_list.id as chat_id
                FROM `users` 
                LEFT JOIN `chat_list` on (users.id = `chat_list`.`sender_id` OR users.id = `chat_list`.`receiver_id`) AND (`chat_list`.`receiver_id` = $user_id OR `chat_list`.`sender_id` = $user_id)
                WHERE `users`.`user_type` = '2' and `users`.`status` = '1' and `users`.`id` != '1' and `users`.`id` != '155'  and `users`.`id` != $user_id 
                GROUP BY users.id LIMIT $this->per_page_show OFFSET $offset");

        } else {
           $sellers = User::select('users.id', 'users.user_id', 'users.user_type', 'users.name', 'users.lname', 'users.storename', 'users.email', 'users.phone_number', 'users.city', 'users.avatar', 'users.PAN', 'users.GST', 'users.flatNo', 'users.pincode', 'users.area', 'users.state', 'users.is_first_time',DB::raw("0 as chat_id"))
            ->where('users.user_type', '2')->where('users.storename', 'like', '%' . $search . '%')->orWhere('users.city','like', '%' . $search . '%')->where('users.status', 1)->where('users.id', '!=', 1)->where('users.id', '!=', $user_id)->groupBy('users.id')->paginate($this->per_page_show, ['*'], 'page', $page_number);
            
            $seller = DB::select("SELECT users.id,users.user_id,users.user_type, users.name, users.lname,users.storename ,users.email,users.phone_number,users.area,users.state,users.is_first_time,users.date_of_birth,users.otp,users.PAN,users.GST,users.flatNo,users.pincode,users.city,users.avatar ,chat_list.id as chat_id
                FROM `users` 
                LEFT JOIN `chat_list` on (users.id = `chat_list`.`sender_id` OR users.id = `chat_list`.`receiver_id`) AND (`chat_list`.`receiver_id` = $user_id OR `chat_list`.`sender_id` = $user_id)
                WHERE `users`.`user_type` = '2' AND (`users`.`city` LIKE '%$search%' OR `users`.`storename` LIKE '%$search%' ) and `users`.`status` = '1' and `users`.`id` != '1' and `users`.`id` != '155' and `users`.`id` != $user_id 
                GROUP BY users.id LIMIT $this->per_page_show OFFSET $offset");
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
                'chat_id' => $users->chat_id,
                'lat' => "",
                'long' => "",
            ];
        }

        $pagination = [
            'total' => $sellers->total(),
            'count' => $sellers->count(),
            'per_page' => $sellers->perPage(),
            'current_page' => $sellers->currentPage(),
            'total_pages' => $sellers->lastPage(),
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
        $search = $request->search;
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
            ->select('assign_customer_coupons.customer_id', DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"), DB::raw("users2.storename AS seller_name"), 'users.city', 'users.phone_number', DB::raw("COUNT(assign_customer_coupons.coupon_number) AS totalCoupon"))
            ->leftJoin('users', 'users.id', '=', 'assign_customer_coupons.customer_id')
            ->leftJoin('users AS users2', 'users2.id', '=', 'assign_customer_coupons.user_id')
            ->where('assign_customer_coupons.user_id', '=', $user_id)
            ->where('assign_customer_coupons.event_id', '=', $event_id)
            ->where(function($query) use ($search) {
                if($search) {
                    $query->where(DB::raw("CONCAT(users.name, ' ', users.lname)"), 'LIKE', "%{$search}%")
                    ->orWhere('users.phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('assign_customer_coupons.coupon_number', 'LIKE', "%{$search}%");
                }
             })
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
        $search = $request->search;
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
            ->where(function($query) use ($search) {
                if($search) {
                    $query->where(DB::raw("CONCAT(users.name, ' ', users.lname)"), 'LIKE', "%{$search}%")
                    ->orWhere('users.phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('assign_customer_coupons.coupon_number', 'LIKE', "%{$search}%");
                }
             })
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

        if (true) {
            $result['status'] = false;
            $result['message'] = 'You cannot assign the coupon now as the allotted time has expired.';
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $customerData = User::where('phone_number', $request->phone_number)->where(['status'=> 1,'user_type' => 4])->first();
        $isCustomer = 0;
        if(isset($customerData)) {
            $isCustomer = 1;
        }
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'customer_name' => ($request->customer_id == '0') ? 'required|string' : '',
            'customer_city' => ($request->customer_id == '0') ? '' : '',
            'phone_number' => ($request->customer_id == '0' && $isCustomer == 0) ? 'nullable|numeric|digits:10' : '',
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
            ->where('coupons_order.status', '=', '1')
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
            ->where('seller_coupons.is_assign', '=', '0')
            ->where('seller_coupons.status', '=', '1')
            ->get();
        $arrSellerCoupons = [];
        foreach ($couponListSeller as $key => $selCoup) {
            $arrSellerCoupons[] = $selCoup->coupon_number;
        }

        $total_coupon_qty = isset($couponCountSeller[0]->quantity) ? (int)$couponCountSeller[0]->quantity : 0;
        $remaining_seller_coupons = bcsub((int) $total_coupon_qty , isset($assigneCouponCountSeller[0]->totalAssignCoupon) ? (int) $assigneCouponCountSeller[0]->totalAssignCoupon : 0);
     
        if ($request->input('assign_type') == 1) {
            if ((int)$remaining_seller_coupons == 0) {
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
                
                if ((int)$remaining_seller_coupons == 0) { // <= $couponDiff
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
            if ((int)$remaining_seller_coupons == 0) { // <= $couponDiff
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
      
        if ($request->customer_id == "0") {
            
            $users = new User();
            $name = explode(" ", $request->customer_name);
        
            // check user exist
            $userCheck = User::where('phone_number', ($request->phone_number) ? $request->phone_number : '')->count();
            $userGetData = User::where('phone_number', ($request->phone_number) ? $request->phone_number : '')->first();
            if ($userCheck == 0) {
                $mname = '';
                $lname = '';
                $llname = '';
                if(isset($name[1])) {
                    $mname = $name[1];
                }
                if(isset($name[2])) {
                    $lname = $name[2];
                }
                if(isset($name[3])) {
                    $llname = $name[3];
                }
                $users->name = isset($name[0]) ? $name[0] : '';
                $users->lname = $mname.' '.$lname. ' '.$llname;
                $users->city = $request->customer_city;
                $users->phone_number = $request->phone_number;
                $users->user_type = '3';
                $users->password = Hash::make('123456');
                $users->save();
                $last_insert_id = $users->id;
            }
            if($userGetData) {
                $userType = User::where(['user_type'=>'4','id'=>$userGetData->id])->first();
                if($userType) {
                    $userType->update([
                        'user_type' => '3',
                        'name' => isset($name[0]) ? $name[0] : '',
                        'lname' => isset($name[1]) ? $name[1] : '',
                        'city' => $request->customer_city,
                    ]);
                }
                $last_insert_id = $userGetData->id;
            }
        }
        // single coupon
        if ($request->input('assign_type') == 1) {
            $data_insert = [
                'assign_type' => $request->input('assign_type'),
                'user_id' => $request->input('user_id'),
                'event_id' => $request->input('event_id'),
                'customer_id' => ($request->customer_id != '0') ? $request->customer_id : $last_insert_id,
                'coupon_number' => ltrim($request->input('coupon_number'), '0'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $coupons_check_assigned = (array)$data_insert['coupon_number'];
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
                        'customer_id' => ($request->customer_id != '0') ? $request->customer_id : $last_insert_id,
                        'coupon_number' =>  ltrim($j, '0'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
            $coupons_check_assigned = array_column($data_insert, 'coupon_number');
        } else if ($request->input('assign_type') == 3) {
            $multiple_coupon = $request->multiple_coupon;
            $multiple_coupon = explode(',', $multiple_coupon);
            foreach ($multiple_coupon as $value) {
                $data_insert[] = [
                    'assign_type' => $request->input('assign_type'),
                    'user_id' => $request->input('user_id'),
                    'event_id' => $request->input('event_id'),
                    'customer_id' => ($request->customer_id != '0') ? $request->customer_id : $last_insert_id,
                    'coupon_number' => ltrim($value,'0'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            $coupons_check_assigned = array_column($data_insert, 'coupon_number');
        }
        
        $check_coupons = DB::table('seller_coupons')
            ->select('seller_coupons.id', 'seller_coupons.coupon_number')
            ->where('seller_coupons.user_id', '=', $user_id)
            ->where('seller_coupons.event_id', '=', $event_id)
            ->whereIn('seller_coupons.coupon_number', $coupons_check_assigned)
            ->where('seller_coupons.is_assign', '=', '1')->where('seller_coupons.status', '=', '1')->count();

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
            ->where("seller_coupons.status", "=", '1')
            ->whereIn('coupon_number', $coupons_check_assigned)
            ->update(["seller_coupons.is_assign" => 1,'seller_coupons.updated_at' => date('Y-m-d H:i:s')]);

        // assign coupons entry
        // dd($data_insert);
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
        $loginType = $request->user_type;
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
            ->where('seller_coupons.status', '=', '1')
            ->groupBy('seller_coupons.event_id')
            ->groupBy('seller_coupons.user_id')
            ->get();
        $totalQty = isset($couponCountTotal[0]) ? $couponCountTotal[0]->quantity : 0;

        $couponCountSold = DB::table('seller_coupons')
            ->select(DB::raw('COUNT(seller_coupons.coupon_number) as quantity'))
            ->where('seller_coupons.user_id', '=', $user_id)
            ->where('seller_coupons.event_id', '=', $event_id)
            ->where('seller_coupons.status', '=', '1')
            ->where('seller_coupons.is_assign', '=', '1')
            ->groupBy('seller_coupons.event_id')
            ->groupBy('seller_coupons.user_id')
            ->get();
        $totalQtySold = isset($couponCountSold[0]) ? $couponCountSold[0]->quantity : 0;
        if($loginType == '1') { //seller
            $countCustomerCoupon = DB::table('assign_customer_coupons')
                            ->select(DB::raw("COUNT(assign_customer_coupons.coupon_number) AS totalCoupon"))
                            ->where('assign_customer_coupons.user_id', '=', $user_id)
                            ->where('assign_customer_coupons.event_id', '=', $event_id)
                            ->groupBy('assign_customer_coupons.customer_id')->get();
        } else {
            $countCustomerCoupon = DB::table('assign_customer_coupons')
                            ->select(DB::raw("COUNT(assign_customer_coupons.coupon_number) AS totalCoupon"))
                            ->where('assign_customer_coupons.customer_id', '=', $user_id)
                            ->where('assign_customer_coupons.event_id', '=', $event_id)
                            ->groupBy('assign_customer_coupons.customer_id')->get();
        }
        $customer_coupons_total_count = isset($countCustomerCoupon[0]) ? $countCustomerCoupon[0]->totalCoupon : 0;

        $remainigCoupon = $totalQty - $totalQtySold;
        $current_event_name = isset($events[0]->event_name) ? $events[0]->event_name: '';
        $current_event_id = $event_id;
        $current_event_year = isset($events[0]->event_name) ? date('Y', strtotime($events[0]->start_date)) : '';
        $current_event_banner_image = isset($events[0]->event_name) ? $events[0]->image : '';
        $slabArr = Slab::select('slabs.id', 'slabs.min_coupons', 'slabs.max_coupons', 'slabs.prize', 'slabs.event_id', 'slabs.status','events.event_name')->leftJoin('events', 'events.id', '=', 'slabs.event_id')->where('slabs.status', 1)->get();
        $cmsData = Cms::all()->where('status', 1);
        $meetingData = Meeting::first();

        $socialData = Social::all()->where('status', 1);
        $noticeData = Notice::all()->where('status', 1);

        $generalData = [
             'current_event_id' => $current_event_id,
             'winner_info_html' => $noticeData[0]->content,
             'prize_info_html' => $noticeData[1]->content,
             'min_coupon_order' => 100,
             'max_coupon_order' => 1000,
             'ssu_email_support' => 'info@headway.org.in',
             'ssu_phone_support' => '+91 9081241916',
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
            'last_event_name' => isset($Oldevents) ? $Oldevents->event_name : '',
            'last_event_year' => '',
            'last_event_id' => isset($Oldevents) ? $Oldevents->id : 0,
            'customer_coupons_total_count' => $customer_coupons_total_count,
            'generalData' => (object)$generalData,
            'recent_event' => (object)isset($Oldevents) ? $Oldevents : array(),
            'bannerList' => $banner,
            'zoom_meeting_title' => $meetingData['meeting_title'],
            'zoom_meeting_link' => ($meetingData['link']) ? $meetingData['link'] : '',
            'is_zoom_meeting_today' => (string)$meetingData['is_today']
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
            ->select('coupons_order.id', 'coupons_order.quantity', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->receipt_path . "', coupons_order.receipt_payment),'') AS receipt_payment"), 'users.storename', DB::raw("CONCAT(users.name, ' ',users.lname) AS seller_name"), DB::raw("CASE WHEN coupons_order.order_status = '0' THEN 'Pending' WHEN coupons_order.order_status = '1' THEN 'Approved' WHEN coupons_order.order_status = '2' THEN 'Declined' WHEN coupons_order.order_status = '3' THEN 'Delivered' ELSE 'Pending' END AS order_status"), 'events.event_location', DB::raw("DATE_FORMAT(coupons_order.created_at, '%d %M %Y %h:%i %p') AS order_date"),'events.event_name','coupons_order.reasons')
            ->where('coupons_order.user_id', '=', $user_id)
            ->where('coupons_order.status', '=', '1')
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
            'receipt_payment' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
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
                'created_at' => date('Y-m-d H:i:s')
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
        $events = Event::leftJoin('event_details', function($join)  use ($event_id) {
                $join->on('events.id', '=', 'event_details.event_id')
                    ->where('event_details.status', '=', '1')
                    ->where('event_details.event_id', '=', $event_id);
            })
            ->select('events.*','event_details.id AS dID','event_details.video', 'event_details.title')
            ->where('events.id', '=', $event_id)
            ->where('event_details.event_id', '=', $event_id)
            ->where('event_details.status', '1')
            ->where('event_details.type', '=', 2)
            ->orWhere('event_details.type', '=', 3)
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
            $evnt['id'] = $value['dID'];
            $evnt['event_name'] = $value['event_name'];
            $evnt['event_title'] = $value['title'];
            $evnt['event_year'] = date('Y', strtotime($value['start_date']));
            $evnt['start_date'] = date('d/m/Y', strtotime($value['start_date']));
            $evnt['end_date'] = date('d/m/Y', strtotime($value['end_date']));
            $evnt['prize'] = $value['prize'];
            $evnt['event_location'] = $value['event_location'];
            if(preg_match('/https:\/\/(www\.)*youtube\.com\/.*/',$value['video'])){
                $evnt['videos'] = ($value['video']) ? $value['video'] : '';
            } else {
                $evnt['videos'] = ($value['video']) ? $base_url.$this->event_video_path. $value['video'] : '';
            }
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
            ->where('event_details.status', '=', '1')
            ->where('event_details.type', '=', 1)
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
            $evnt['images'] = ($value['image']) ? $value['image'] : '';
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
        $search = $request->search;
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
          ->select('assign_customer_coupons.id','assign_customer_coupons.customer_id', 'assign_customer_coupons.coupon_number', DB::raw("DATE_FORMAT(assign_customer_coupons.created_at, '%Y-%m-%d') AS date"), DB::raw("users.storename AS seller_name"),'users.storename')
            ->where('assign_customer_coupons.customer_id', '=', $customer_id)
            ->where('assign_customer_coupons.event_id', '=', $event_id)
            ->where(function($query) use ($search) {
                if($search) {
                    $query->where(DB::raw("CONCAT(users.name, ' ', users.lname)"), 'LIKE', "%{$search}%")
                    ->orWhere('users.phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('assign_customer_coupons.coupon_number', 'LIKE', "%{$search}%");
                }
             })
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
            ->where('prizes.status', '=', 1)->orderBy('prizes.prize_amount', 'DESC')
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
        $prizeLists = Document::select('id','user_id', 'event_id', 'status', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->doc_path . "', file),'') AS file"),'doc_name')->where('status', '=', 1)
            ->where('user_id', '=', $user_id)
            ->orWhere('user_id', '=', 0)
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
        ->select('id', 'user_id', 'event_id', 'title', 'amount', 'detail', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->bill_path . "', file),'') AS file"), DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->bill_path . "', receipt),'') AS receipt"), DB::raw("CASE WHEN bill_status = '0' THEN 'Pending' WHEN bill_status = '1' THEN 'Approved' WHEN bill_status = '2' THEN 'Declined' WHEN bill_status = '3' THEN 'Completed' ELSE '' END bill_status"), DB::raw("DATE_FORMAT(created_at, '%d %M %Y %h:%i %p') AS date"),'reasons')
            ->where('user_id', '=', $user_id)
            ->where('event_id', '=', $event_id)
            ->orderBy('id','DESC')
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
            'file' => 'required|mimes:jpeg,png,jpg,gif,webp,pdf|max:2048',
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
        $cmsData = Cms::all()->where('status', 1);
        $generalData = [
             'privacy_policy' => $cmsData[1]->content,
             'terms_condition' => $cmsData[2]->content,
        ];
        return response()->json(['status' => true, 'message' => 'Get general data successfully', 'data' => $generalData], 200);
    }

    /**
     * Winner list data.
     */
    public function winnerList(Request $request)
    {
        $user_id = $request->user_id;
        $event_id = $request->event_id;
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

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

    
        $winnersList = Winner::where('winners.status', '=', 1)->where('prizes.status', '=', 1)->where('prizes.status', '=', 1)
            ->select('winners.id', 'winners.user_id', 'winners.event_id', 'winners.coupon_number', 'prizes.prize_name', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->winner_path . "', prizes.image),'') AS image"), DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"), 'users2.storename AS seller_name', 'users.city AS customer_city')
            ->leftJoin('prizes', 'prizes.id', '=', 'winners.prize_id')
            ->leftJoin('users', 'users.id', '=', 'winners.customer_id')
            ->leftJoin('users AS users2', 'users2.id', '=', 'winners.user_id')
            ->leftJoin('events', 'events.id', '=', 'winners.event_id')
            ->where(function ($query) use ($search) {
                $query->where('prizes.prize_name', 'like', '%' . $search . '%')
                      ->orWhere('winners.coupon_number', 'like', '%' . $search . '%')
                      ->orWhere('users2.storename', 'like', '%' . $search . '%')
                      ->orWhere('users.name', 'like', '%' . $search . '%');
            })
            ->where('winners.event_id', '=', $event_id)
            // ->where('winners.user_id', $user_id)
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $winnersList->total(),
            'count' => $winnersList->count(),
            'per_page' => $winnersList->perPage(),
            'current_page' => $winnersList->currentPage(),
            'total_pages' => $winnersList->lastPage(),
        ];
        $winnListData = [
            'pagination' => $pagination,
            'data' => $winnersList,
        ];

        return response()->json(['status' => true, 'message' => 'Get Winners list successfully', 'data' => $winnListData], 200);
    }

    /**
     * Rewards list data.
     */
    public function rewardList(Request $request)
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
            'event_id' => 'required'
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        $rewardsList = Reward::where('rewards.status', '=', 1)
        ->select('rewards.id', 'rewards.user_id', 'rewards.event_id', 'rewards.points', DB::raw("CONCAT(users2.name, ' ', users2.lname) AS seller_names"), DB::raw("CASE WHEN rewards.transaction_type = 1 THEN 'Credited' WHEN rewards.transaction_type = 2 THEN 'Debited' ELSE '' END transaction_type"), DB::raw("DATE_FORMAT(rewards.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("rewards.detail AS details"), DB::raw("users2.storename AS seller_name"))
        ->addSelect(DB::raw("(SELECT SUM(points) FROM rewards WHERE user_id = '$user_id' AND event_id = '$event_id' AND transaction_type = 1 GROUP BY user_id, event_id) as totalPoints"))
        ->addSelect(DB::raw("((SELECT SUM(points) FROM rewards WHERE user_id = '$user_id' AND event_id = '$event_id' AND transaction_type = 1 GROUP BY user_id, event_id) - (SELECT SUM(points) FROM rewards WHERE user_id = '$user_id' AND event_id = '$event_id' AND transaction_type = 2 GROUP BY user_id, event_id)) as leftPoints"), DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->profile_path . "', avatar),'') AS avatar"))
        ->leftJoin('users AS users2', 'users2.id', '=', 'rewards.user_id')
        ->leftJoin('events', 'events.id', '=', 'rewards.event_id')
        ->where('rewards.user_id', '=', $user_id)
        ->where('rewards.event_id', '=', $event_id)
        ->groupBy('rewards.user_id')
        ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $rewardsHistory = Reward::where('rewards.status', '=', 1)
        ->select('rewards.id', 'rewards.user_id', 'rewards.event_id', 'rewards.points', DB::raw("CONCAT(users2.name, ' ', users2.lname) AS seller_names"), DB::raw("CASE WHEN rewards.transaction_type = 1 THEN 'Credited' WHEN rewards.transaction_type = 2 THEN 'Debited' ELSE '' END transaction_type"), DB::raw("DATE_FORMAT(rewards.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("rewards.detail AS details"), DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->profile_path . "', avatar),'') AS avatar"), DB::raw("0 AS totalPoints"), DB::raw("0 AS leftPoints"), DB::raw("users2.storename AS seller_name"))
        ->leftJoin('users AS users2', 'users2.id', '=', 'rewards.user_id')
        ->leftJoin('events', 'events.id', '=', 'rewards.event_id')
        ->where('rewards.user_id', '=', $user_id)
        ->where('rewards.event_id', '=', $event_id)
        ->paginate($this->per_page_show, ['*'], 'page', $page_number);
        if($rewardsList) {
            $leftPoints =  ($rewardsList[0]->leftPoints)??$rewardsList[0]->totalPoints;
        }
       
        $rewardData = ['total_points' => isset($rewardsList[0]) ? $rewardsList[0]->totalPoints : 0, 'left_points' =>  isset($rewardsList[0]) ? $leftPoints : 0];
        $finalData = $rewardsList->merge($rewardsHistory); 
        // dd($finalData);
        $pagination = [
            'total' => $rewardsList->total(),
            'count' => $rewardsList->count(),
            'per_page' => $rewardsList->perPage(),
            'current_page' => $rewardsList->currentPage(),
            'total_pages' => $rewardsList->lastPage(),
        ];
        $rwdListData = [
            'pagination' => $pagination,
            'reward_data' => $rewardData,
            'data' => $finalData,
        ];

        return response()->json(['status' => true, 'message' => 'Get Rewards list successfully', 'data' => $rwdListData], 200);
    }

    /**
     * chat sender message.
     */
    public function sendMessageChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'receiver_id' => 'required',
            'sender_id' => 'required',
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
        $chat_id =  $request->chat_id;
        if($chat_id == "0" || $chat_id == 0) {
            $arr = [
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id
            ];
            $chat_id = DB::table('chat_list')->insertGetId($arr);
        }
        $chat = new Chatmessage();
        $chat->user_id = $request->user_id;
        $chat->sender_id = $request->sender_id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->chat_id = $chat_id;
        $chat->save();
        $last_insert_id = $chat->id;

        $chatData = Chatmessage::select('chatmessages.id', 'chatmessages.user_id', 'chatmessages.receiver_id', 'chatmessages.sender_id', 'chatmessages.message AS message', DB::raw("DATE_FORMAT(chatmessages.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("UNIX_TIMESTAMP(chatmessages.created_at) AS time"),'chatmessages.chat_id','users2.storename AS receiver_storename','users.storename AS sender_storename', DB::raw("'new_message' AS type"))
        ->leftJoin('users AS users2', 'users2.id', '=', 'chatmessages.receiver_id')
        ->leftJoin('users AS users', 'users.id', '=', 'chatmessages.sender_id')
        ->where('chatmessages.id', $last_insert_id)->first();
        // dd($chatData);
        $newData  = json_encode($chatData);
        $body = array('user_id' => $user_id,'sender_id' => $request->sender_id, 'receiver_id' => $request->receiver_id,'title' => $request->storename ,'message' => $request->message, 'data' => $newData, 'content_available' => true);

        $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);

        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     return $sendNotification->getContent();
        // } else {
        //     return $sendNotification->getContent();
        // }
        return response()->json(['status' => true, 'message' => 'Message send successfully', 'data' => ['message' => $chatData]], 200);
    }

    /**
     * Chat list data.
     */
    public function chatList(Request $request)
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
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }

        // $chatList = DB::select("SELECT chatmessages.id,chatmessages.user_id,chatmessages.receiver_id,chatmessages.sender_id, chatmessages.message AS lastMessage, CONCAT(conversations.name, ' ', conversations.lname) AS seller_name ,DATE_FORMAT(chatmessages.created_at, '%d %M %Y %h:%i %p') AS date,IFNULL(CONCAT('" . $base_url . "','" . $this->profile_path . "', avatar),'') AS avatar
        // FROM chatmessages, 
        // (SELECT MAX(chatmessages.id) as lastid, name,lname,avatar
        //                 FROM chatmessages
        //                  LEFT JOIN users AS users2 ON users2.id = chatmessages.user_id
        //                 WHERE (chatmessages.sender_id = '7'  OR chatmessages.receiver_id = 7 )
        //                 GROUP BY CONCAT(LEAST(chatmessages.receiver_id,chatmessages.sender_id),'.',
        //                 GREATEST(chatmessages.receiver_id, chatmessages.sender_id))
        // ) as conversations
        // WHERE chatmessages.id = conversations.lastid
        // ORDER BY chatmessages.created_at DESC");
        // dd($chatList);

        $chatList = Chatmessage::select('chatmessages.id', 'chatmessages.user_id', 'chatmessages.receiver_id', 'chatmessages.sender_id', 'chatmessages.message AS lastMessage','users2.storename AS receiver_storename', DB::raw("DATE_FORMAT(chatmessages.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->profile_path . "', users2.avatar),'') AS receiver_avatar"), DB::raw("UNIX_TIMESTAMP(chatmessages.created_at) AS time"),'chatmessages.chat_id','users.storename AS sender_storename', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->profile_path . "', users.avatar),'') AS sender_avatar"),'chatmessages.is_admin',DB::raw("CASE WHEN chatmessages.is_admin = 1 THEN 'SSU' ELSE '' END AS sender_admin"))
        ->where(function($query) use ($user_id) {
            $query->where('chatmessages.user_id', $user_id)
                ->orWhere('chatmessages.receiver_id', $user_id);
        })
        ->whereIn('chatmessages.id', function($query) use ($user_id) {
            $query->select(DB::raw('MAX(id)'))
                ->from('chatmessages')
                ->where(function($query) use ($user_id) {
                    $query->where('chatmessages.user_id', $user_id)
                        ->orWhere('chatmessages.receiver_id', $user_id);
                })
                ->groupBy('chatmessages.chat_id');
        })
        ->leftJoin('users AS users2', 'users2.id', '=', 'chatmessages.receiver_id')
        ->leftJoin('users AS users', 'users.id', '=', 'chatmessages.sender_id')
        // ->where('chatmessages.user_id', $user_id)
        // ->orWhere('chatmessages.receiver_id', $user_id)
        ->groupBy('chatmessages.chat_id')
        ->orderBy('id','DESC')
        ->paginate($this->per_page_show, ['*'], 'page', $page_number);
       
        $pagination = [
            'total' => $chatList->total(),
            'count' => $chatList->count(),
            'per_page' => $chatList->perPage(),
            'current_page' => $chatList->currentPage(),
            'total_pages' => $chatList->lastPage(),
        ];
        $rwdListData = [
            'pagination' => $pagination,
            'data' => $chatList,
        ];

        return response()->json(['status' => true, 'message' => 'Chat list successfully', 'data' => $rwdListData], 200);
    }

    /**
     * Messages Chat list data.
     */
    public function messageChatList(Request $request)
    {
        $user_id = $request->user_id;
        $receiver_id = $request->receiver_id;
        $chat_id = $request->chat_id;
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
            'receiver_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object) [];
            return response()->json($result, 200);
        }
        if($chat_id != 1) {
            $chatList = Chatmessage::select('chatmessages.id','chatmessages.message','chatmessages.user_id','chatmessages.receiver_id', DB::raw("DATE_FORMAT(chatmessages.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("UNIX_TIMESTAMP(chatmessages.created_at) AS time"),'users.storename','chatmessages.chat_id')
            ->leftJoin('users','users.id','=','chatmessages.user_id')
            ->where('chatmessages.chat_id',$chat_id)
            ->orderBy('chatmessages.id','DESC')
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);
        } else {
            $chatList = Chatmessage::select('chatmessages.id','chatmessages.message','chatmessages.user_id','chatmessages.receiver_id', DB::raw("DATE_FORMAT(chatmessages.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("UNIX_TIMESTAMP(chatmessages.created_at) AS time"),'users.storename','chatmessages.chat_id')
            ->leftJoin('users','users.id','=','chatmessages.user_id')
            ->where('chatmessages.chat_id',$chat_id)
            ->groupBy('chatmessages.message')
            ->orderBy('chatmessages.id','DESC')
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);
        }
        // dd($chatList);
        $pagination = [
            'total' => $chatList->total(),
            'count' => $chatList->count(),
            'per_page' => $chatList->perPage(),
            'current_page' => $chatList->currentPage(),
            'total_pages' => $chatList->lastPage(),
        ];
        $rwdListData = [
            'pagination' => $pagination,
            'data' => $chatList,
        ];

        return response()->json(['status' => true, 'message' => 'Chat list successfully', 'data' => $rwdListData], 200);
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

    public function sendNotification(Request $request) {
        $validator = Validator::make($request->all(), [
            'fcmtoken' => 'required',
            'user_id' => 'required',
            'receiver_id' => 'required',
            'title' => 'required',
            'message' => 'required',
        ]);
        $body = $request;
         $newData  = json_encode(array());
        $body = array('user_id' => $request->user_id,'sender_id' => $request->user_id, 'receiver_id' => $request->receiver_id,'title' => $request->title ,'message' => $request->message, 'data' => $newData, 'content_available' => true);
       $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
       $notifData = json_decode($sendNotification->getContent(), true);

        if (isset($notifData['status']) && $notifData['status'] == true) {
            return $sendNotification->getContent();
        } else {
            return $sendNotification->getContent();
        }
    }

    /**
     * Assets list data.
     */
    public function assetsList(Request $request)
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

        $billsList = Asset::where('status', '=', 1)
        ->select('id', 'user_id', 'event_id', 'title', 'detail', DB::raw("CASE WHEN order_status = '0' THEN 'Pending' WHEN order_status = '1' THEN 'Approved' WHEN order_status = '2' THEN 'Declined' ELSE '' END order_status"), DB::raw("DATE_FORMAT(created_at, '%d %M %Y %h:%i %p') AS date"),"quantity",'reasons')
            ->where('user_id', '=', $user_id)
            ->where('event_id', '=', $event_id)
            ->orderBy('id','DESC')
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

        return response()->json(['status' => true, 'message' => 'Get Assets list successfully', 'data' => $billListData], 200);
    }

    /**
     * Assets add form Data.
     */
    public function addAssets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'detail' => 'required',
            'quantity' => 'required',
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
        $bill = new Asset;
        $bill->title = $request->title;
        $bill->detail = $request->detail;
        $bill->quantity = $request->quantity;
        $bill->user_id = $request->user_id;
        $bill->event_id = $request->event_id;
        
        $bill->save();

        return response()->json(['status' => true, 'message' => 'Asset Added successfully', 'data' => []], 200);
    }

     /**
     * graphics add form Data.
     */
    public function addGraphics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'detail' => 'required',
            'file' => 'required|max:10048',
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
        $graphic = new Graphic;
        $graphic->title = $request->title;
        $graphic->detail = $request->detail;
        $graphic->user_id = $request->user_id;
        $graphic->event_id = $request->event_id;
        
        if ($image = $request->file('file')) {
            $destinationPath = 'public/graphics/';
            $graphicFile = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $graphicFile);
            $graphic->file = "$graphicFile";
        }
        $graphic->save();

        return response()->json(['status' => true, 'message' => 'Graphic Added successfully', 'data' => []], 200);
    }

    /**
     * graphics list data.
     */
    public function graphicsList(Request $request)
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

        $graphicsList = Graphic::where('status', '=', 1)
        ->select('id', 'user_id', 'event_id', 'title', 'detail', DB::raw("IFNULL(CONCAT('" . $base_url . "','" . $this->graphic_path . "', file),'') AS file"), DB::raw("CASE WHEN graphic_status = '0' THEN 'Pending' WHEN graphic_status = '1' THEN 'Approved' WHEN graphic_status = '2' THEN 'Declined' WHEN graphic_status = '3' THEN 'Completed' ELSE '' END graphic_status"), DB::raw("DATE_FORMAT(created_at, '%d %M %Y %h:%i %p') AS date"),'reasons')
            ->where('user_id', '=', $user_id)
            ->where('event_id', '=', $event_id)
            ->orderBy('id','DESC')
            ->paginate($this->per_page_show, ['*'], 'page', $page_number);

        $pagination = [
            'total' => $graphicsList->total(),
            'count' => $graphicsList->count(),
            'per_page' => $graphicsList->perPage(),
            'current_page' => $graphicsList->currentPage(),
            'total_pages' => $graphicsList->lastPage(),
        ];
        $graphicListData = [
            'pagination' => $pagination,
            'data' => $graphicsList,
        ];

        return response()->json(['status' => true, 'message' => 'Get Graphics list successfully', 'data' => $graphicListData], 200);
    }

    /**
     * graphics list data.
     */
    public function reportCouponDownload(Request $request)
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

        $fileName = 'customer_coupons_'.time().'.xlsx';
        $export = new CouponsExport($user_id, $event_id);

        // Use Storage to store the file temporarily
        Excel::store($export, $fileName, 'public');  // Store in the 'public' disk

        // Generate the download link for the stored file
        $fileUrl = $base_url.Storage::url($fileName);  // Get the URL for the stored file


        // return Excel::download(new CouponsExport($user_id, $event_id), $fileName);

        return response()->json(['status' => true, 'message' => 'Report Download Successfully', 'data' => $fileUrl], 200);
    }

}
