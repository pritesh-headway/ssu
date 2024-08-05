<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\Datatables;
use Hash;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all()->whereNotIn('id', 1)->where('user_type', 2);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("seller.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <a href="' . URL("seller/destroy/" . $row->id) . '" 
              class="delete btn btn-danger btn-sm">Delete
              </a>';
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
            'phone_number' => 'required|numeric|unique:users',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();

        if ($image = $request->file('profile_image')) {
            $destinationPath = 'profile_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $input['avatar'] = "$profileImage";
        }
        $input['user_type'] = '2';
        $input['password'] = Hash::make('123456');
        User::create($input);

        return redirect()->route('seller.index')
            ->with('success', 'Seller created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('sellers.show', compact('user'));
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
        ]);
        $input = $request->all();

        $user = User::find($request->id);
        if ($image = $request->file('profile_image')) {
            $destinationPath = 'profile_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['avatar'] = "$profileImage";
        } else {
            unset($input['avatar']);
        }
        $input['user_type'] = '2';
        $input['password'] = Hash::make('123456');
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
}