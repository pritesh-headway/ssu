<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrator');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all()->whereNotIn('id', 1)->where('user_type', 2)->where('status', 1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . URL("details/". $row->id) . '"
              class="store btn btn-warning btn-sm approve">View</a> <a href="' . route("seller.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>
              &nbsp;<a href="' . route("seller.show", $row->id) . '"
              class="edit btn btn-info btn-sm viewCoupo">View Coupons</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('sellers.list');
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
      
        $request->validate([
            'name' => 'required|string|max:50',
            'lname' => 'required|string|max:50',
            'storename' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('users')
                        ->where('phone_number', $value)
                        ->where('status', 1)
                        ->exists();
                    if ($exists) {
                        $fail('The phone number has already been taken by an active user.');
                    }
                },
            ],
            'PAN' => 'required',
            'GST' => 'required',
            'flatNo' => 'required',
            'area' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5042',
        ]);
        $input = $request->all();

        if ($image = $request->file('profile_image')) {
            $destinationPath = 'public/profile_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $input['avatar'] = "$profileImage";
        }
        $input['user_type'] = '2';
        $input['lname'] = $request->lname;
        $input['storename'] = $request->storename;
        $input['PAN'] = $request->PAN;
        $input['GST'] = $request->GST;
        $input['flatNo'] = $request->flatNo;
        $input['area'] = $request->area;
        $input['city'] = $request->city;
        $input['state'] = $request->state;
        $input['pincode'] = $request->pincode;
        $input['password'] = Hash::make('123456');
        User::create($input);

        return redirect()->route('seller.index')
            ->with('success', 'Seller created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
       
        $couponsList = DB::table('seller_coupons')
            ->select(DB::raw("CONCAT('#', seller_coupons.coupon_number) AS coupon_number"),'seller_coupons.is_assign', DB::raw("CASE WHEN seller_coupons.is_assign = 0 THEN 'Available' WHEN seller_coupons.is_assign = 1 THEN 'Assigned'  ELSE 'Available' END is_assign"))
            ->leftJoin('events', 'events.id', '=', 'seller_coupons.event_id')
            ->where('seller_coupons.user_id', '=', $id)->get();
 
        if ($request->ajax()) {
            return Datatables::of($couponsList)
                ->addIndexColumn()
                ->make(true);
        }

        return view('sellers.show', compact('couponsList','id'));
    }

    /**
     * Display the specified resource.
     */
    public function details($id)
    {
        $user = User::find($id);
        return view('sellers.details', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('sellers.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // dd($request->id);
        $request->validate([
            'name' => 'required|string|max:50',
            'lname' => 'required|string|max:50',
            'storename' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
            'PAN' => 'required',
            'GST' => 'required',
            'flatNo' => 'required',
            'area' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
        ]);
        $input = $request->all();

        $user = User::find($request->id);
        if ($image = $request->file('profile_image')) {
            $destinationPath = 'public/profile_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['avatar'] = "$profileImage";
        } else {
            unset($input['avatar']);
        }
        $input['lname'] = $request->lname;
        $input['storename'] = $request->storename;
        $input['PAN'] = $request->PAN;
        $input['GST'] = $request->GST;
        $input['flatNo'] = $request->flatNo;
        $input['area'] = $request->area;
        $input['city'] = $request->city;
        $input['state'] = $request->state;
        $input['pincode'] = $request->pincode;
        $user->update($input);

        return redirect()->route('seller.index')
            ->with('success', 'Seller updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = User::find($id);
        $book->status = 0;
        $book->save();
        return response()->json(['success' => 'Seller deleted Successfully!']);

        return redirect()->route('seller.index')
            ->with('success', 'Seller deleted successfully');
    }

    public function importUsers(Request $request) {
        // Get the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        try {
            // dd($request->file('file'));
            Excel::import(new UsersImport, $request->file('file'));

            return back()->with('success', 'Users imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
           $failures = $e->failures();

            return back()->with('failures', $failures);
        }
    }
}
