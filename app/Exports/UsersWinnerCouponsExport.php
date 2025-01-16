<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersWinnerCouponsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $user_id;
    protected $prize;
    protected $prize_name;

    public function __construct($user_id, $prize, $prize_name)
    {
        $this->user_id = $user_id;
        $this->prize = $prize;
        $this->prize_name = $prize_name;
    }

    public function collection()
    {
        $data =  DB::table('winners')
            ->select(
                'jw.storename',
                'winners.coupon_number',
                DB::raw("CONCAT(users.name, ' ', users.lname) AS customer_name"),
                'users.city',
                'users.phone_number',
                DB::raw("'" . $this->prize_name . "' AS prize")
            )
            ->leftJoin('users', 'users.id', '=', 'winners.customer_id')
            ->leftJoin('users AS jw', 'jw.id', '=', 'winners.user_id')
            ->where('winners.status', 1)
            ->where('jw.status', 1)
            ->when($this->user_id, function ($query) {
                return $query->where('winners.user_id', $this->user_id);
            })
            ->where('winners.prize_id', $this->prize)
            ->orderByRaw('CAST(winners.coupon_number AS UNSIGNED) ASC')
            ->get();

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
