<?php

namespace App\Http\Controllers;

use App\Models\Slab;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;

class SlabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Slab::select('slabs.id', 'slabs.min_coupons', 'slabs.max_coupons', 'slabs.prize', 'slabs.event_id', 'slabs.status','events.event_name')->leftJoin('events', 'events.id', '=', 'slabs.event_id')->where('slabs.status', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("slab.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
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
        // dd($request->event_id);
        $request->validate([
            'event_id' => 'required|not_in:null',
            'min_coupons' => 'required',
            'max_coupons' => 'required',
            'prize' => 'required|numeric',
        ], [
            'event_id' => 'The event name is required'
        ]);
        $input = $request->all();

        $input['event_id'] = $request->event_id;
        $input['min_coupons'] = $request->min_coupons;
        $input['max_coupons'] = $request->max_coupons;
        $input['prize'] = $request->prize;
        Slab::create($input);

        return redirect()->route('slab.index')->with('success', 'Slab created successfully.');
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
    public function edit($id)
    {
        $slab = Slab::find($id);
        $event = Event::all()->where('status', 1);
        return view('slabs.edit', compact('slab','event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slab $slab)
    {
        $request->validate([
            'event_id' => 'required|not_in:null',
            'min_coupons' => 'required',
            'max_coupons' => 'required',
            'prize' => 'required|numeric',
        ], [
            'event_id' => 'The event name is required'
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
        $slab->update($input);

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
