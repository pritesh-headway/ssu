<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class ProfileController extends Controller
{
    public $base_url;
    public $profile_path;
    public function __construct()
    {
        $this->base_url = url('/'); 
        $this->profile_path = '/public/profile_images/';
    }

    public function getProfileDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object)[];
            return response()->json($result, 200);
        }
        $base_url =  $this->base_url; 
        $token = $request->header('token');
        $user = User::where('remember_token', $token)->first();
        if($user == '' || $user == null) {
            $result['status'] = false;
            $result['message'] = "Token given is invalid, Please login again.";
            $result['data'] = (object)[];
            return response()->json($result, 200);
        }

        $user_id = $request->user_id;
        $user = User::where('id', $user_id)->first();

        $userData = [
            'id'=>(string)$user->id,
            'user_id'=> (string)$user->id,
            'user_type' => (string)$user->user_type,
            'name' => (string)$user->name,
            'lname' => (string)$user->lname,
            'storename' => (string)$user->storename,
            'email' => (string)$user->email,
            'date_of_birth' => (string)$user->date_of_birth,
            'phone_number' => (string)$user->phone_number,
            'otp' => (string)$user->otp,
            'PAN' => (string)$user->PAN,
            'GST' => (string)$user->GST,
            'flatNo' => (string)$user->flatNo,
            'pincode' => (string)$user->pincode,
            'area' => (string)$user->area,
            'city' => (string)$user->city,
            'state' => (string)$user->state,
            'avatar' => ($user->avatar) ? $base_url.$this->profile_path.$user->avatar : '',
            'is_first_time' => $user->is_first_time,
            'token' => $token
        ];

        return response()->json(['status' => true,'message' => 'Get Profile details successfully', 'data' => $userData], 200);
    }


    public function updateProfile(Request $request)
    {

        $token = $request->header('token');
        $user_id = $request->user_id;
        $base_url =  $this->base_url; 
        $user = User::where('remember_token', $token)->first();
        if($user == '' || $user == null) {
            $result['status'] = false;
            $result['message'] = "Token given is invalid, Please login again.";
            $result['data'] = (object)[];
            return response()->json($result, 200);
        }

        // Get the currently authenticated user
        $user = User::where('id', $user_id)->first();
        // Define validation rules
        if($request->user_type == 2) {
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'email' => 'required|email',
                'lname' => 'required|string',
                'storename' => 'required|string',
                'mobile' => 'required|string',
                'PAN' => 'required|string',
                'GST' => 'required|string',
                'flatNo' => 'required|string',
                'area' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'required|string',
                'user_type' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'email' => 'required|email',
                'lname' => 'required|string',
                'mobile' => 'required|string',
                'flatNo' => 'required|string',
                'area' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'required|string',
                'user_type' => 'required'
            ]);
        }

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object)[];
            return response()->json($result, 200);
        }

        // Update user details
        $user->name = $request->input('fname');
        $user->email = $request->input('email');
        $user->lname = $request->input('lname');
        $user->storename = $request->input('storename');
        $user->PAN = $request->input('PAN');
        $user->GST = $request->input('GST');
        $user->flatNo = $request->input('flatNo');
        $user->pincode = $request->input('pincode');
        $user->password = Hash::make('123456');
        $user->area = $request->input('area');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->save();

        $users = User::where('id', $user_id)->first();

        $userData = [
            'id'=>(string)$users->id,
            'user_id'=> (string)$users->id,
            'user_type' => (string)$users->user_type,
            'name' => (string)$users->name,
            'lname' => (string)$users->lname,
            'storename' => (string)$users->storename,
            'email' => (string)$users->email,
            'date_of_birth' => (string)$users->date_of_birth,
            'phone_number' => (string)$users->phone_number,
            'otp' => (string)$users->otp,
            'PAN' => (string)$users->PAN,
            'GST' => (string)$users->GST,
            'flatNo' => (string)$users->flatNo,
            'pincode' => (string)$users->pincode,
            'area' => (string)$users->area,
            'city' => (string)$users->city,
            'state' => (string)$users->state,
            'avatar' =>  ($users->avatar) ? $base_url.$this->profile_path.$users->avatar : '',
            'is_first_time' => $users->is_first_time,
            'token' => $token
        ];

        // Return a response
        return response()->json(['status' => true,'message' => 'Profile updated successfully', 'data' => $userData], 200);
    }

    public function uploadProfileImage(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            $result['status'] = false;
            $result['message'] = $validator->errors()->first();
            $result['data'] = (object)[];
            return response()->json($result, 200);
        }
        $base_url =  $this->base_url; 
        $token = $request->header('token');
        $user = User::where('remember_token', $token)->first();
        if($user == '' || $user == null) {
            $result['status'] = false;
            $result['message'] = "Token given is invalid, Please login again.";
            $result['data'] = (object)[];
            return response()->json($result, 200);
        }

        $user_id = $request->user_id;
        $users = User::where('id', $user_id)->first();
        // Handle file upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            // $path = $image->store('profile_images', 'public');

            $destinationPath = 'public/profile_images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);

            $users->avatar = $profileImage;
            $users->save();
            $arr = array('image' => $base_url.'/'.$destinationPath.$profileImage);
            return response()->json(['status' => true,'message' => 'Image uploaded successfully', 'data' => $arr], 200);
        }

        return response()->json(['status' => true,'message' => 'Image upload failed'], 500);
    }
}
