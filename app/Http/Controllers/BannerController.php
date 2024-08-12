<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Banner::all()->where('status', 1);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("banner.edit", $row->id) . '"
              class="edit btn btn-success btn-sm">Edit</a> 
              <button class="delete btn btn-danger btn-sm" onclick="deleteItem('.$row->id.')">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('banners.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banners.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'banner_name' => 'required',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5042',
        ]);
        $input = $request->all();
        if ($image = $request->file('banner_image')) {
            $destinationPath = 'public/banner_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $input['image'] = "$profileImage";
        }

        Banner::create($input);

        return redirect()->route('banner.index')
            ->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'banner_name' => 'required',
            // 'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5042',
        ]);
        $input = $request->all();
        if ($image = $request->file('banner_image')) {
            $destinationPath = 'public/banner_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }

        $banner->update($input);

        return redirect()->route('banner.index')
            ->with('success', 'Banner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Banner deleted Successfully!']);

        return redirect()->route('banner.index')
            ->with('success', 'Banner deleted successfully');
    }
}
