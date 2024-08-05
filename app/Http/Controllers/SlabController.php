<?php

namespace App\Http\Controllers;

use App\Models\Slab;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\Datatables;
use Illuminate\Support\Facades\DB;

class SlabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Slab::leftJoin('events', 'events.id', '=', 'slabs.event_id')->where('slabs.status', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("slab.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <a href="' . URL("slab/destroy/" . $row->id) . '" 
              class="delete btn btn-danger btn-sm">Delete
              </a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $event = Event::all()->where('status', 1);
        return view('slabs.list', compact('event'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $event = Event::all()->where('status', 1);
        return view('slabs.add', compact('event'));
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

        return redirect()->route('slab.index')
            ->with('success', 'Slab created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slab $slab)
    {
        return view('slabs.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slab $slab)
    {
        $user = Slab::find($id);
        return view('slabs.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slab $slab)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'lname' => 'required|string|max:50',
            'storename' => 'required|string|max:100',
            'email' => 'required|string|email|max:255',
        ]);
        $input = $request->all();

        $user = Slab::find($request->id);
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

        return redirect()->route('slab.index')
            ->with('success', 'Slab updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Slab::find($id);
        $book->status = 0;
        $book->save();
        return response()->json(['success' => 'Slab deleted Successfully!']);

        return redirect()->route('slab.index')
            ->with('success', 'Slab deleted successfully');
    }
}
