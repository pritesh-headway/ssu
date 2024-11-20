<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class NoticeController extends Controller
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
            $data = Notice::all()->where('status', 1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("notice.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('notices.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notices.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'notice_name' => 'required',
            'content' => 'required',
        ]);
        $input = $request->all();
        Notice::create($input);

        return redirect()->route('notice.index')
            ->with('success', 'Notice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notice $notice)
    {
        return view('notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $notice = Notice::where('id', $id)->first();
        return view('notices.edit', compact('notice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'notice_name' => 'required',
            'content' => 'required',
        ]);
        $input = $request->all();

        $notice->update($input);

        return redirect()->route('notice.index')
            ->with('success', 'Notice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Notice::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Notice deleted Successfully!']);

        return redirect()->route('notice.index')
            ->with('success', 'Notice deleted successfully');
    }
}
