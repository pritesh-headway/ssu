<?php

namespace App\Http\Controllers;

use App\Models\Chatmessage;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\FcmNotificationService;


class BroadcastController extends Controller
{
    protected $fcmNotificationService;

    public function __construct(FcmNotificationService $fcmNotificationService)
    {
        $this->fcmNotificationService = $fcmNotificationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            DB::enableQueryLog();
            $data = Chatmessage::where('status', 1)->where('is_admin', 1)->groupBy('message')->get();
            $query = DB::getQueryLog();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('broadcasts.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('broadcasts.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        // $input = $request->all();
        $seller = User::all()->whereNotIn('id', 1)->where('user_type', 2)->where('status', 1);
        
        $user_id = auth()->id();
        foreach ($seller as $key => $value) {
            $receiver_ids[] = $value->id;
            $input['sender_id'] = $user_id;
            $input['user_id'] = $user_id;
            $input['receiver_id'] = $value->id;
            $input['chat_id'] = 1;
            $input['message'] = $request->content;
            $input['created_at'] = date('Y-m-d H:i:s');
            $input['is_admin'] = 1;
            $data[] = $input;
        }
        Chatmessage::insert($data);

        $chatData = Chatmessage::select('chatmessages.id', 'chatmessages.user_id', 'chatmessages.receiver_id', 'chatmessages.sender_id', 'chatmessages.message AS message', DB::raw("DATE_FORMAT(chatmessages.created_at, '%d %M %Y %h:%i %p') AS date"), DB::raw("UNIX_TIMESTAMP(chatmessages.created_at) AS time"),'chatmessages.chat_id','users2.storename AS receiver_storename',DB::raw("'SSU' AS sender_storename"),'chatmessages.is_admin', DB::raw("'new_message' AS type"))
        ->leftJoin('users AS users2', 'users2.id', '=', 'chatmessages.receiver_id')
        ->leftJoin('users AS users', 'users.id', '=', 'chatmessages.sender_id')
        ->where('chatmessages.chat_id',1)->where('chatmessages.is_admin',1)->orderBy('chatmessages.id', 'DESC')->first();
        // dd($chatData);
        $newData  = json_encode($chatData);
        $body = array('user_id' => $user_id,'sender_id' => $user_id, 'receiver_id' => $receiver_ids,'title' => 'Admin Broadcast' ,'message' => $request->content, 'data' => $newData, 'content_available' => true);

        $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);

        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     return $sendNotification->getContent();
        // } else {
        //     return $sendNotification->getContent();
        // }

        return redirect()->route('broadcast.index')
            ->with('success', 'Broadcast created successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Chatmessage::find($id);
        $banner->status = 0;
        $banner->save();
        return response()->json(['success' => 'Cms deleted Successfully!']);

        return redirect()->route('broadcast.index')
            ->with('success', 'Cms deleted successfully');
    }
}
