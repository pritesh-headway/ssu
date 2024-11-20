<?php

namespace App\Imports;

use App\Models\AssignCoupon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;

class CouponsImport implements  ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        $store = User::where('storename', 'like', '%'.$row['store_name'].'%')->where('status', '1')->where('user_type', '2')->first();
        $customers = User::where('name', 'like', '%'.$row['customer_first_name'].'%')->where('lname', 'like', '%'.$row['customer_last_name'].'%')->where('user_type', '3')->where('status', '1')->first();

        if(isset($customers->id)) {
            $customerId = $customers->id;
        } else {
            $users = new User();
            $users->name = isset($row['customer_first_name']) ? $row['customer_first_name'] : '';
            $users->lname = isset($row['customer_last_name']) ? $row['customer_last_name'] : '';
            $users->city = $row['customer_city'];
            $users->phone_number = $row['customer_phone'];
            $users->user_type = '3';
            $users->password = Hash::make('123456');
            $users->save();
            $customerId = $users->id;
        }
        $assign_coupon = new AssignCoupon([
            'user_id'     => $store->id,
            'customer_id'     => $customerId,
            'event_id'     => 1,
            'assign_type'    => 1,
            'coupon_number'     => $row['coupon_number'],
        ]);

        DB::table('seller_coupons')->where('coupon_number', $row['coupon_number'])->update([
            'is_assign' => '1'
        ]);
        return $assign_coupon;
    }

    public function rules(): array
    {
        return [
            'store_name' => 'required',
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_phone' => 'required',
            'coupon_number' => 'required|numeric',
            'is_assign' => 'required|numeric',
        ];
    }
}

