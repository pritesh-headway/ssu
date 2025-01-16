<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Prize;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersWinnerCouponsExport;
use Illuminate\Support\Facades\DB;

class WinnerController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userList = User::where('user_type', 2)->where('id', '!=', '1')->where('status', '1')->orderBy('storename', 'ASC')->get();
        $prizeList = Prize::where('status', '1')->orderBy('prize_name', 'ASC')->get();
        return view('winners.add', compact('userList', 'prizeList'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $prize_id = $request->prize;
        $prize = Prize::find($prize_id);
        $prize_name = isset($prize->prize_name) ? $prize->prize_name : '';

        $dataExists =  DB::table('winners')
            ->select(
                'jw.storename',
                'winners.coupon_number',
                DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"),
                'users.city',
                'users.phone_number'
            )
            ->leftJoin('users', 'users.id', '=', 'winners.customer_id')
            ->leftJoin('users AS jw', 'jw.id', '=', 'winners.user_id')
            ->where('winners.status', 1)
            ->where('jw.status', 1)
            ->when($user_id, function ($query)  use ($user_id) {
                return $query->where('winners.user_id', $user_id);
            })
            ->where('winners.prize_id', $prize_id)
            ->exists();

        if (!$dataExists) {
            // Return an alert message if no data found
            return redirect()->back()->with('error', 'No data available for the selected criteria.');
        }

        return Excel::download(new UsersWinnerCouponsExport($user_id, $prize_id, $prize_name), $prize_name . ' winner lists.xlsx');
    }
}
