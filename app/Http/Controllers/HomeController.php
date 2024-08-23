<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $base_url;

    public function __construct()
    {
        $this->middleware('auth');
         $this->base_url = url('/');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $eventList = Event::all()->where('status', 1);
        $orderList = DB::table('coupons_order')
            ->leftJoin('users', 'users.id', '=', 'coupons_order.user_id')
            ->leftJoin('events', 'events.id', '=', 'coupons_order.event_id')
            ->select('coupons_order.id','coupons_order.user_id','coupons_order.event_id','coupons_order.quantity','coupons_order.receipt_payment','users.storename',DB::raw("CONCAT(users.name, ' ',users.lname) AS seller_name"),DB::raw("CASE WHEN coupons_order.order_status = '0' THEN 'Pending' WHEN coupons_order.order_status = '1' THEN 'Approved' WHEN coupons_order.order_status = '2' THEN 'Declined' WHEN coupons_order.order_status = '3' THEN 'Delivered' ELSE 'Pending' END AS order_status"),'events.event_name', 'events.event_location',DB::raw("DATE_FORMAT(coupons_order.created_at, '%d-%m-%Y') AS order_date"),DB::raw("YEAR(events.start_date) AS event_year"),'coupons_order.reasons','coupons_order.created_at','users.avatar')
            ->orderBy('coupons_order.id', 'desc')
            ->get()->toArray();
        $todaysCoupons= [];
        $todaysRemainingCoupons= [];
        $soldCoupon= [];
        $pedingCoupon= [];
        foreach ($orderList as $key => $value) {
            if($value->order_status == 'Approved') {
                $soldCoupon[] = $value->quantity;
            }
            if($value->order_status == 'Pending') {
                $pedingCoupon[] = $value->quantity;
            }

            if(date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d')) {
                $todaysCoupons[] = $value->quantity;
            }

            if($value->order_status == 'Pending') {
                if(date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d')) {
                    $todaysRemainingCoupons[] = $value->quantity;
                }
            }
        }   
        $couponsArray = array_column($orderList, 'quantity');
        $totalCoupn = array_sum($couponsArray);
        $enquiryCoupn = count($couponsArray);
        $soldCoupon = array_sum($soldCoupon);
        $todaysCoupons = array_sum($todaysCoupons);
        $todaysRemainingCoupons = array_sum($todaysRemainingCoupons);
        $pedingCoupon = count($pedingCoupon);
        
        $customerCount = User::all()->whereNotIn('id', 1)
                        ->where('user_type', '3')
                        ->where('status', '1')->count();
        $customerTodayCount = User::all()->whereNotIn('id', 1)
                            ->where('user_type', '3')
                            ->where('status', '1')
                            ->where('created_at', '>', Carbon::now()->startOfDay())
                            ->count();
        $sellerCount = User::all()->whereNotIn('id', 1)->where('user_type', '2')->where('status', '1')->count();

        $sellerTodayCount = User::all()->whereNotIn('id', 1)
                            ->where('user_type', '2')
                            ->where('status', '1')
                            ->where('created_at', '>', Carbon::now()->startOfDay())
                            ->count();

        $base_url = $this->base_url;
        return view('home', compact('eventList','orderList','base_url','totalCoupn','soldCoupon','sellerCount','customerCount','todaysCoupons','todaysRemainingCoupons','customerTodayCount','sellerTodayCount','pedingCoupon','enquiryCoupn'));
    }
}
