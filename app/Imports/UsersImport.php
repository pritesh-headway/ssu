<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements  ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        return new User([
            'name'     => $row['seller_first_name'],
            'lname'     => $row['seller_last_name'],
            'storename'     => $row['store_name'],
            'email'    => $row['email'],
            'phone_number'     => $row['phone_number'],
            'PAN'     => $row['seller_pan'],
            'GST'     => $row['seller_gst'],
            'flatNo'     => $row['flatnumber_building'],
            'area'     => $row['street_area'],
            'city'     => $row['city'],
            'state'     => $row['state'],
            'pincode'     => $row['pincode'],
            'user_type'     => 2,
            'is_first_time'     => 1,
            'password' => Hash::make(123456),
        ]);
    }

    public function rules(): array
    {
        return [
            'seller_first_name' => 'required',
            'seller_last_name' => 'required',
            'store_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric|digits:10|unique:users,phone_number,NULL,id,status,1',
            'pincode' => 'required',
        ];
    }
}

