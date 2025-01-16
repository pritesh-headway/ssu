<?php

namespace App\Imports;

use App\Models\AssignCoupon;
use App\Models\Coupon;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class CouponsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, WithChunkReading
{
    protected $sellerName;
    // Constructor to accept additional parameters
    public function __construct($sellerName)
    {
        $this->sellerName = $sellerName;
        ini_set('memory_limit', '3024M');
        set_time_limit(300);
    }

    public function model(array $row)
    {
        static $defaultPassword;
        static $existingCoupons = [];
        static $users = [];
        static $assignCoupons = [];

        if (!$defaultPassword) {
            $defaultPassword = Hash::make('123456');
        }
        if (empty($existingCoupons)) {
            $existingCoupons  = Coupon::select('coupon_number')->where('user_id', $this->sellerName)->where('status', '1')->pluck('coupon_number')
                ->toArray();
        }

        if (in_array($row['coupon_number'], $existingCoupons)) {
            $customer_name = explode(' ', $row['customer_name']);
            $first_name = $customer_name[0];
            $middle_name = isset($customer_name[1]) ? $customer_name[1] : '';
            $last_name =  (isset($customer_name[2]) ? $customer_name[2] : '') . ' ' . (isset($customer_name[3]) ? $customer_name[3] : '');

            $last_names = $middle_name . ' ' . $last_name;

            $customers = User::where('name', 'like', '%' . $first_name . '%')->where('lname', 'like', '%' . $last_names . '%')->where('city', 'like', '%' . $row['customer_city'] . '%')->where('user_type', '3')->where('status', '1')->first();

            if (isset($customers->id)) {
                $customerId = $customers->id;
            } else {
                $customer = User::create([
                    'name' => $first_name,
                    'lname' => $last_names,
                    'city' => $row['customer_city'],
                    'phone_number' => $row['customer_phone'],
                    'user_type' => '3',
                    'password' => $defaultPassword,
                ]);
                $customerId = $customer->id;
            }
            $chkAssignCouponExist = AssignCoupon::where('coupon_number', $row['coupon_number'])->where('user_id', $this->sellerName)->count();

            $chkAssignC = AssignCoupon::where('coupon_number', $row['coupon_number'])->where('user_id', $this->sellerName)->where('customer_id', $customerId)->count();

            if ($chkAssignC == 0) {
                $assignCoupons[] = [
                    'user_id' => $this->sellerName,
                    'customer_id' => $customerId,
                    'event_id' => '1',
                    'assign_type' => '1',
                    'coupon_number' => $row['coupon_number']
                ];

                DB::table('seller_coupons')->where('coupon_number', $row['coupon_number'])->where('user_id', $this->sellerName)->update([
                    'is_assign' => '1'
                ]);
            }

            // Insert all the collected AssignCoupon data in bulk after processing all rows
            // if (isset($assignCoupons)) {
            //     DB::beginTransaction();
            //     DB::enableQueryLog();
            //     AssignCoupon::insert($assignCoupons);
            //     DB::commit();
            // }
        }

        // Bulk insert after processing
        if (isset($assignCoupons)) {
            AssignCoupon::insert($assignCoupons);
            $assignCoupons = [];
        }
    }

    /**
     * After processing all rows, insert the data in bulk.
     */
    public function chunkSize(): int
    {
        return 1000; // Process in chunks of 1000 records
    }

    public function model_bk(array $row)
    {
        $checkCoupons = Coupon::select('coupon_number')->where('user_id', $this->sellerName)->where('status', '1')->get()->toArray();
        $couponNumbers = array_column($checkCoupons, 'coupon_number');
        if (in_array($row['coupon_number'], $couponNumbers)) {
            $customer_name = explode(' ', $row['customer_name']);
            $first_name = $customer_name[0];
            $middle_name = ($customer_name[1]) ? $customer_name[1] : '';
            $last_name =  (isset($customer_name[2]) ? $customer_name[2] : '') . ' ' . (isset($customer_name[3]) ? $customer_name[3] : '');

            $last_names = $middle_name . ' ' . $last_name;

            $customers = User::where('name', 'like', '%' . $first_name . '%')->where('lname', 'like', '%' . $last_names . '%')->where('city', 'like', '%' . $row['customer_city'] . '%')->where('user_type', '3')->where('status', '1')->first();

            if (isset($customers->id)) {
                $customerId = $customers->id;
            } else {
                $users = new User();
                $users->name = $first_name;
                $users->lname = $last_names;
                $users->city = $row['customer_city'];
                $users->phone_number = $row['customer_phone'];
                $users->user_type = '3';
                $users->password = Hash::make('123456');
                $isSaved = $users->save();
                if (!$isSaved) {
                    dd("Failed to save user");
                }
                $customerId = $users->id;
            }

            $chkAssignC = AssignCoupon::where('coupon_number', $row['coupon_number'])->where('user_id', $this->sellerName)->where('customer_id', $customerId)->count();
            if ($chkAssignC == 0) {
                $assign_coupon = new AssignCoupon([
                    'user_id'     => $this->sellerName,
                    'customer_id'     => $customerId,
                    'event_id'     => '1',
                    'assign_type'    => '1',
                    'coupon_number'     => $row['coupon_number'],
                    'updated_at' => date('Y-m-d h:i:s')
                ]);

                DB::table('seller_coupons')->where('coupon_number', $row['coupon_number'])->where('user_id', $this->sellerName)->update([
                    'is_assign' => '1'
                ]);
                return $assign_coupon;
            }
        }
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required',
            // 'customer_last_name' => 'required',
            'customer_city' => 'required',
            // 'customer_phone' => 'required|numeric',
            'coupon_number' => 'required|numeric',
        ];
    }
}
