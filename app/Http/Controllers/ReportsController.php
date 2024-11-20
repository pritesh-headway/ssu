<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Hash;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select('SELECT co.user_id, COUNT(co.id) AS order_count, u.storename, u.city, u.area,SUM(co.quantity) coupons
            FROM coupons_order co
            LEFT JOIN users u ON u.id = co.user_id
            WHERE co.order_status !="2"
            GROUP BY co.user_id
            HAVING COUNT(co.id) > 0
            ORDER BY COUNT(co.id) DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('reports.list');
    }

    public function show($id)
    {
        $sellers = User::whereNotIn('id', [1])->where('user_type', 2)->where('status', 1)->orderBy('storename', 'ASC')->get();
        // $sellers = $sellers->orderBy('storename','asc');

        return view('reports.sellersList', compact('sellers'));
    }

   
}
