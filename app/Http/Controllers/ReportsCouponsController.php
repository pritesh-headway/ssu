<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Hash;
use Illuminate\Support\Facades\DB;
use App\Models\AssignCoupon;

class ReportsCouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select('SELECT UCASE(u.storename) as storename, CONCAT(UCASE(u.name)," ", UCASE(u.lname)) as sellerName, u.area, u.phone_number, u.city, (SELECT count(id) as couponsCount  FROM seller_coupons WHERE user_id=u.id AND is_assign="1" AND status = "1" GROUP BY user_id) as SoldCoupons, (SELECT count(id) as couponsCount  FROM seller_coupons WHERE user_id=u.id AND is_assign="0" AND status = "1" GROUP BY user_id) as RemainingCoupons, (SELECT count(id) as couponsCount FROM seller_coupons WHERE user_id=u.id AND status = "1" GROUP BY user_id) as TotalCoupons
                FROM users u 
                WHERE u.user_type = "2" AND u.status="1" ORDER BY u.storename ASC');

            return Datatables::of($data)
                ->addIndexColumn()
            //     ->addColumn('action', function ($row) {
            //         $actionBtn = '<a href="' . route("reportssellercustomer.show", $row->id) . '"
            //   class="edit btn btn-info btn-sm viewCoupo">Customer Coupons Reports</a>';
            //         return $actionBtn;
            //     })
            //     ->rawColumns(['action'])
                ->make(true);
        }
        return view('reports.sellersCoupons');
    }

    
 
}
