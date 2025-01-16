<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersCouponsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $numberCountDigit;
    protected $number;
    protected $prize;

    public function __construct($numberCountDigit, $number, $prize)
    {
        $this->numberCountDigit = $numberCountDigit;
        $this->number = $number;
        $this->prize = $prize;
    }

    public function collection()
    {
        $data =  DB::table('assign_customer_coupons')
            ->select(
                'jw.storename',
                'assign_customer_coupons.coupon_number',
                DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"),
                'users.city',
                'users.phone_number',
                DB::raw("'" . $this->prize . "' AS prize")
            )
            ->leftJoin('users', 'users.id', '=', 'assign_customer_coupons.customer_id')
            ->leftJoin('users AS jw', 'jw.id', '=', 'assign_customer_coupons.user_id')
            ->where(DB::raw('RIGHT(coupon_number, ' . $this->numberCountDigit . ')'), $this->number)
            ->where('is_winner', 0)
            ->get();
        foreach ($data as $key => $value) {
            DB::table('assign_customer_coupons')
                ->where(
                    'coupon_number',
                    $value->coupon_number
                )
                ->update([
                    'is_winner' => '1',
                ]);
        }
        return $data;
    }

    public function headings(): array
    {
        return ['Store Name', 'Coupon Number', 'Customer Name', 'Customer City', 'Phone Number', 'Prize Name']; // Custom headings
    }

    /**
     * Apply styles to the Excel sheet.
     *
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // Make the font bold
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center'); // Center-align the headers

        $sheet->getStyle('A2:F' . (count($this->collection()) + 1))->getAlignment()->setHorizontal('center'); // Center-align the data
    }
}
