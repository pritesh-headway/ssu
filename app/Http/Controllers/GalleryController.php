<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Event;
use App\Models\User;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Gallery::select('*',DB::raw("CASE WHEN type = 1 THEN 'Image' WHEN type = 2 THEN 'Video' ELSE '' END type"))->where('status', 1)->get();
            return Datatables::of($data)
                ->editColumn('start_date', function($data){ 
                    if($data->start_date) {
                        $formatedDate = Carbon::createFromFormat('Y-m-d', $data->start_date)->format('Y'); 
                        return $formatedDate; 
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("gallery.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $eventList = Event::all()->where('status', 1);
        return view('gallery.list', compact('eventList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eventList = Event::all()->where('status', 1);
        return view('gallery.add', compact('eventList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|not_in:null',
            'type' =>'required|not_in:null',
            'title' => 'required',
            'image' => ($request->type == 1) ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5042' : '',
            'video' => ($request->type == 2) ? 'required|mimes:mp4,ogx,oga,ogv,ogg,webm|max:515024' : '',
        ], [
            'event_id' => 'The event name is required',
            'user_id' => 'The gallery type is required',
        ]);
        $input = $request->all();
        if($request->type == 1) {
            if ($image = $request->file('image')) {
                $destinationPath = 'public/event_images/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);

                $input['image'] = "$profileImage";
            }
        } else {
            if ($image = $request->file('video')) {
                $destinationPath = 'public/event_videos/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);

                $input['video'] = "$profileImage";
            }
        }

        Gallery::create($input);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $event)
    {
        return view('gallery.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        $eventList = Event::all()->where('status', 1);
        return view('gallery.edit', compact('gallery','eventList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'event_id' => 'required|not_in:null',
            'type' =>'required|not_in:null',
            'title' => 'required',
            'image' => ($request->type == 1) ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5042' : '',
            'video' => ($request->type == 2) ? 'required|mimes:mp4,ogx,oga,ogv,ogg,webm|max:515024' : '',
        ], [
            'event_id' => 'The event name is required',
            'user_id' => 'The gallery type is required',
        ]);
        $input = $request->all();

        if($request->type == 1) {
            if ($image = $request->file('image')) {
                $destinationPath = 'public/event_images/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);

                $input['image'] = "$profileImage";
            } else {
                unset($input['image']);
            }
        } else {
            if ($image = $request->file('docFile')) {
                $destinationPath = 'public/event_videos/';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);

                $input['video'] = "$profileImage";
            }else {
                unset($input['video']);
            }
        }
        $gallery->update($input);
        return redirect()->route('gallery.index')
            ->with('success', 'Gallery updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Gallery::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Gallery deleted Successfully!']);
        return redirect()->route('gallery.index')
            ->with('success', 'Gallery deleted successfully');
    }
}
