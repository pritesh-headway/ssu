<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class PrizeController extends Controller
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
            $data = Prize::select('prizes.id','prizes.prize_name','prizes.prize_qty','prizes.prize_amount','prizes.image','events.event_name')
            ->leftJoin('events', 'events.id', '=', 'prizes.event_id')->where('prizes.status', 1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("prize.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('prizes.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $event = Event::all()->where('status', 1);
        return view('prizes.add', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|not_in:null',
            'prize_name' => 'required',
            'prize_qty' => 'required',
            // 'prize_amount' => 'required|numeric',
            'prize_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5042',
        ], [
            'event_id' => 'The event name is required'
        ]);
        $input = $request->all();
        if ($image = $request->file('prize_image')) {
            $destinationPath = 'public/prize_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $input['image'] = "$profileImage";
        }

        Prize::create($input);

        return redirect()->route('prize.index')
            ->with('success', 'Prize created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prize $prize)
    {
        return view('prizes.show', compact('prize'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prize $prize)
    {
        $event = Event::all()->where('status', 1);
        return view('prizes.edit', compact('prize','event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prize $prize)
    {
         $request->validate([
            'event_id' => 'required|not_in:null',
            'prize_name' => 'required',
            'prize_qty' => 'required',
            // 'prize_amount' => 'required|numeric',
        ], [
            'event_id' => 'The event name is required'
        ]);
        $input = $request->all();
        if ($image = $request->file('prize_image')) {
            $destinationPath = 'public/prize_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }

        $prize->update($input);

        return redirect()->route('prize.index')
            ->with('success', 'Prize updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prize = Prize::find($id);
        $prize->status = 0;
        $prize->save();
        return response()->json(['success' => 'Prize deleted Successfully!']);

        return redirect()->route('prize.index')
            ->with('success', 'Prize deleted successfully');
    }
}
