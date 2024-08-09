<?php

namespace App\Http\Controllers;

use App\Models\cms;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cms::all()->where('status', 1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("cms.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('cmss.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cmss.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_name' => 'required',
            'content' => 'required',
        ]);
        $input = $request->all();
        Cms::create($input);

        return redirect()->route('cms.index')
            ->with('success', 'Cms created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cms $cms)
    {
        return view('cmss.show', compact('cms'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cms = Cms::where('id', $id)->first();
        return view('cmss.edit', compact('cms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cms $cms)
    {
        $request->validate([
            'page_name' => 'required',
            'content' => 'required',
        ]);
        $input = $request->all();

        $cms->update($input);

        return redirect()->route('cms.index')
            ->with('success', 'Cms updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Cms::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Cms deleted Successfully!']);

        return redirect()->route('cms.index')
            ->with('success', 'Cms deleted successfully');
    }
}
