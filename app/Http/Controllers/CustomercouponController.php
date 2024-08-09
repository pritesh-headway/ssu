<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customercoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use Hash;

class CustomercouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customercoupon::select('assign_customer_coupons.customer_id', DB::raw("YEAR(assign_customer_coupons.created_at) AS event_date"), DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"), DB::raw("CONCAT(users2.name, ' ', users2.lname) AS assigned_name"), 'users.city', DB::raw("CASE WHEN assign_customer_coupons.assign_type = 1 THEN 'Single' WHEN assign_customer_coupons.assign_type = 2 THEN 'Range' WHEN assign_customer_coupons.assign_type = 3 THEN 'Multiple' ELSE '' END assign_type"), DB::raw("sum(assign_customer_coupons.coupon_number) AS totalCoupon"))
            ->leftJoin('users', 'users.id', '=', 'assign_customer_coupons.customer_id')
            ->leftJoin('users AS users2', 'users2.id', '=', 'assign_customer_coupons.user_id')
            ->leftJoin('events', 'events.id', '=', 'assign_customer_coupons.event_id')
            ->where('assign_customer_coupons.status', '=', 1)
            ->groupBy('assign_customer_coupons.customer_id')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('customer-coupon.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sellers.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       
    }
}
