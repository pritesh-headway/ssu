<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class CouponController extends Controller
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
        if ($request->ajax()) {
            $data = Coupon::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("coupon.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('coupons.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coupons.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'coupon_name' => 'required',
            'coupon_number' => 'required'
        ]);
        $input = $request->all();
        Coupon::create($input);

        return redirect()->route('coupon.index')
            ->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        return view('coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'coupon_name' => 'required',
            'coupon_number' => 'required'
            // 'event_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();

        $coupon->update($input);

        return redirect()->route('coupon.index')
            ->with('success', 'Coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $banner)
    {
        $banner->delete();

        return redirect()->route('coupon.index')
            ->with('success', 'Coupon deleted successfully');
    }
}
