<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("event.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <a href="' . URL("event/destroy/" . $row->id) . '" 
              class="delete btn btn-danger btn-sm">Delete
              </a>';
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
            'prize' => 'required|numeric',
            'event_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'prize' => 'required|numeric',
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
