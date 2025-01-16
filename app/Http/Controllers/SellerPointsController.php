<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Customercoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Imports\CouponsImport;
use Maatwebsite\Excel\Facades\Excel;

class SellerPointsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrator,Accountant,Verifier');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $sellerData = User::all()->whereNotIn('id', 1)->where('user_type', 2)->where('status', 1);
        $sellerData = User::query()
            ->whereNotIn('id', [1])
            ->where('user_type', 2)
            ->where('status', 1)
            ->get();

        if ($request->ajax()) {
            $data = Asset::select(DB::raw("CONCAT(users.storename, ' (', users.name, ' ',users.lname,' )') AS seller_name"))
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
                ->selectRaw("(
                    (SELECT SUM(CASE WHEN transaction_type = '1' THEN points ELSE 0 END) 
                    FROM rewards 
                    WHERE rewards.user_id = asset_orders.user_id)
                    -
                    (SELECT SUM(CASE WHEN transaction_type = '1' THEN points ELSE 0 END) 
                            - SUM(CASE WHEN transaction_type = '2' THEN points ELSE 0 END) 
                    FROM rewards 
                    WHERE rewards.user_id = asset_orders.user_id)
                ) AS difference_points,
                (
                    SELECT SUM(co.quantity)
                    FROM coupons_order co
                    WHERE co.order_status != '2' AND co.user_id = asset_orders.user_id
                    GROUP BY co.user_id
                    HAVING COUNT(co.id) > 0
                    ORDER BY COUNT(co.id) DESC
                ) AS totalCoupons_bk, (
                    SELECT count(id) as totalCnt FROM `seller_coupons` WHERE `user_id` = asset_orders.user_id AND status = 1 and event_id = asset_orders.event_id
                ) AS totalCoupons")
                ->leftJoin('users', 'users.id', '=', 'asset_orders.user_id')
                ->where('asset_orders.status', 1)
                ->orderBy('asset_orders.id', 'desc')
                ->groupBy('users.id')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('reports.sellers-points', compact('sellerData'));
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
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $couponsList = DB::table('assign_customer_coupons')
            ->select(DB::raw("CONCAT('#', assign_customer_coupons.coupon_number) AS coupon_number"))
            ->leftJoin('events', 'events.id', '=', 'assign_customer_coupons.event_id')
            ->where('assign_customer_coupons.customer_id', '=', $id)->get();

        if ($request->ajax()) {
            return Datatables::of($couponsList)
                ->addIndexColumn()
                ->make(true);
        }
        return view('customer-coupon.show', compact('couponsList', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function importCoupons(Request $request)
    {
        // Get the uploaded file
        $request->validate([
            'seller_name' => 'required',
            'import_file' => 'required|file|mimes:xlsx',
        ]);
        try {
            $seller_name = $request->seller_name;
            Excel::import(new CouponsImport($seller_name), $request->file('import_file'));

            return back()->with('success', 'Coupons imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            return back()->with('failures', $failures);
        }
    }
}
