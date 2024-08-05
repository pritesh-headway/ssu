<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function getBanners(Request $request)
    {
        $user_id = $request->user_id;
        $banner = Banner::where('status', 1)->get();

        return response()->json(['status' => true,'message' => 'Get Profile details successfully', 'data' => $banner], 200);
    }
}
