<?php

namespace App\Exports;

use App\Models\Customercoupon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;


class CouponsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $userId;
    protected $event_id;
    public function __construct($userId, $event_id)
    {
        $this->userId = $userId;
        $this->event_id = $event_id;
    }

    public function collection(): Collection
    {
        $data =  DB::select('SELECT
                                acc.id,
                                S.storename,
                                CONCAT(C.name, " ", C.lname) AS CustomerName,
                                acc.coupon_number,
                                acc.created_at,
                                C.phone_number
                            FROM assign_customer_coupons acc
                            JOIN users S ON
                                S.id = acc.user_id
                            JOIN users C ON
                                C.id = acc.customer_id
                            WHERE
                                acc.user_id = "' . $this->userId . '" 
                            GROUP BY acc.coupon_number  
                            CAST(acc.coupon_number AS UNSIGNED) ASC');
        return collect($data);
    }

    public function headings(): array
    {
        return ['ID', 'Store Name', 'Customer Name', 'Coupon Number', 'Assign Date', 'Phone Number']; // Custom headings
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
