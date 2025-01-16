<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrator,Accountant');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Document::select('documents.id', DB::raw("CONCAT(users.name, ' ', users.lname) AS seller_name"), 'documents.doc_name', 'documents.file')
                ->leftJoin('users', 'users.id', '=', 'documents.user_id')
                ->leftJoin('events', 'events.id', '=', 'documents.event_id')->where('documents.status', 1)->get();
            return Datatables::of($data)
                // ->editColumn('documents.start_date', function ($data) {
                //     if ($data->start_date) {
                //         $formatedDate = Carbon::createFromFormat('Y-m-d', $data->start_date)->format('Y');
                //         return $formatedDate;
                //     }
                // })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("document.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem(' . $row->id . ')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // $eventList = Event::all()->where('status', 1);
        // $userList = User::all()->where('user_type', 2)->where('id', '!=', 1);
        $eventList = Event::where('status', 1)->get();
        $userList = User::where('user_type', 2)->where('id', '!=', 1)->get();

        return view('documents.list', compact('eventList', 'userList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eventList = Event::all()->where('status', 1);
        $userList = User::all()->where('user_type', 2)->where('id', '!=', 1);
        return view('documents.add', compact('eventList', 'userList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|not_in:null',
            'user_id' => 'required|not_in:null',
            'doc_name' => 'required',
            'docFile' => 'required|max:5042',
        ], [
            'event_id' => 'The event name is required',
            'user_id' => 'The seller name is required',
        ]);
        $input = $request->all();
        if ($image = $request->file('docFile')) {
            $destinationPath = 'public/documents/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $input['file'] = "$profileImage";
        }

        Document::create($input);

        return redirect()->route('document.index')
            ->with('success', 'Document created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $event)
    {
        //return view('documents.show', compact('event'));
        return response()->json(['message' => 'Document show view is not yet implemented.'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $eventList = Event::all()->where('status', 1);
        $userList = User::all()->where('user_type', 2)->where('id', '!=', 1);
        return view('documents.edit', compact('document', 'eventList', 'userList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'event_id' => 'required|not_in:null',
            'user_id' => 'required|not_in:null',
            'doc_name' => 'required',
        ], [
            'event_id' => 'The event name is required',
            'user_id' => 'The seller name is required',
        ]);
        $input = $request->all();

        if ($image = $request->file('docFile')) {
            $destinationPath = 'public/documents/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['file'] = "$profileImage";
        } else {
            unset($input['file']);
        }
        $document->update($input);
        return redirect()->route('document.index')
            ->with('success', 'Document updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Document::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Document deleted Successfully!']);
        //return redirect()->route('document.index')->with('success', 'Event deleted successfully');
    }
}
