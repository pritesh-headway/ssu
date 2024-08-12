<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::all()->where('status', 1);
            return Datatables::of($data)
                ->editColumn('start_date', function($data){ 
                    if($data->start_date) {
                        $formatedDate = Carbon::createFromFormat('Y-m-d', $data->start_date)->format('Y'); 
                        return $formatedDate; 
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("event.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('events.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'event_description' => 'required',
            'prize' => 'required|numeric',
            'start_date' => 'required|unique:events,start_date',
            'end_date' => 'required|unique:events,end_date',
            'event_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5042',
        ]);
        $input = $request->all();
        if ($image = $request->file('event_image')) {
            $destinationPath = 'public/event_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $input['image'] = "$profileImage";
        }

        Event::create($input);

        return redirect()->route('event.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required',
            'event_description' => 'required',
            'prize' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            // 'event_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
        if ($image = $request->file('event_image')) {
            $destinationPath = 'public/event_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }

        $event->update($input);

        return redirect()->route('event.index')
            ->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Event::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Event deleted Successfully!']);
        return redirect()->route('event.index')
            ->with('success', 'Event deleted successfully');
    }
}
