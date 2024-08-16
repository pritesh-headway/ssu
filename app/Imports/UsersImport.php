<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name'     => $row['seller_first_name'],
            'lname'     => $row['seller_last_name'],
            'storename'     => $row['store_name'],
            'email'    => $row['seller_email'],
            'phone_number'     => $row['seller_phone_number'],
            'PAN'     => $row['seller_pan'],
            'GST'     => $row['seller_gst'],
            'flatNo'     => $row['flatnumber_building'],
            'area'     => $row['street_area'],
            'city'     => $row['city'],
            'state'     => $row['state'],
            'state'     => $row['pincode'],
            'user_type'     => 2,
            'is_first_time'     => 1,
            'password' => Hash::make(123456),
        ]);
    }
}

