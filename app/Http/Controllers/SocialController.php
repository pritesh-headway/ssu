<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Social::all()->where('status', 1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("social.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('social.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('social.add');
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
        Social::create($input);

        return redirect()->route('social.index')
            ->with('success', 'Social Link created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Social $social)
    {
        return view('social.show', compact('social'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $social = Social::where('id', $id)->first();
        return view('social.edit', compact('social'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Social $social)
    {
        $request->validate([
            'notice_name' => 'required',
            'content' => 'required',
        ]);
        $input = $request->all();

        $social->update($input);

        return redirect()->route('social.index')
            ->with('success', 'Social Link updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Social::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Social Link deleted Successfully!']);

        return redirect()->route('social.index')
            ->with('success', 'Notice deleted successfully');
    }
}
