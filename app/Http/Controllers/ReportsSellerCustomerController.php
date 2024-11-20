<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Hash;
use Illuminate\Support\Facades\DB;
use App\Models\AssignCoupon;

class ReportsSellerCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereNotIn('id', [1])->where('user_type', 2)->where('status', 1)->orderBy('storename','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("reportssellercustomer.show", $row->id) . '"
              class="edit btn btn-info btn-sm viewCoupo">Customer Coupons Reports</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('reports.sellersList');
    }

     /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user_id = $id;
        // $data = DB::select('SELECT acc.id,S.storename, CONCAT(C.name, " ", C.lname) as CustomerName, 
        //     acc.coupon_number,acc.coupon_number as rowId
        //     FROM seller_coupons sc
        //     JOIN assign_customer_coupons acc ON acc.user_id = sc.user_id AND sc.event_id = "1" AND sc.user_id = "'.$user_id.'" 
        //     JOIN users S ON S.id = acc.user_id
        //     JOIN users C ON C.id = acc.customer_id
        //     WHERE acc.user_id = "'.$user_id.'" AND sc.is_assign = "1" 
        //     GROUP BY acc.coupon_number 
        //     ORDER BY `S`.`storename` ASC');

         $data = DB::select('SELECT
                                acc.id,
                                S.storename,
                                CONCAT(C.name, " ", C.lname) AS CustomerName,
                                acc.coupon_number,
                                C.phone_number,
                                acc.coupon_number AS rowId
                            FROM assign_customer_coupons acc
                            JOIN users S ON
                                S.id = acc.user_id
                            JOIN users C ON
                                C.id = acc.customer_id
                            WHERE
                                acc.user_id = "'.$user_id.'" 
                            GROUP BY acc.coupon_number  
                            ORDER BY acc.coupon_number ASC');
 
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                 ->addColumn('action', function ($row) {
                    $actionBtn = '<button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.','.$row->rowId.')">Un-Assign Coupon</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('reports.customerCoupons', compact('data','id'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $rowId)
    {
        $assignCoupn = AssignCoupon::find($id);
        $assignCoupn->delete();

        DB::table('seller_coupons')->where('coupon_number', $rowId)->update([
            'is_assign' => '0'
        ]);
        return response()->json(['success' => 'Seller deleted Successfully!']);

        return redirect()->route('seller.index')
            ->with('success', 'Coupons Un-assigned successfully');
    }
 
}
