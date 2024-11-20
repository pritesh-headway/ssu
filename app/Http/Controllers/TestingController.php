<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Services\FcmNotificationService;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Reward;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TestingController extends Controller
{
    public function __construct()
    {
    }
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $imagePath = 'D:\xampp\htdocs\ssu-api\public\receipt_images\20241022180316.jpg';

        // Create a new instance of TesseractOCR
        $tesseract = new TesseractOCR($imagePath);

        // Set the language of the text in the image
        $tesseract->lang('eng');

        // Get the text from the image
        $text = $tesseract->run();

        // Print the extracted text
        // echo $text;

        if (preg_match('/INR\s+(\d+(?:,\d{3})*)/', $text, $matches)) {
            $debitedAmount = $matches[1]; // Extracted amount
            echo "Debited Amount: INR " . $debitedAmount;
        } else if (preg_match('/Amount\s+(\d{1,3}(?:,\d{3})*\.\d{2})/', $text, $matches)) {
            $amount = $matches[1]; // Extracted amount
            echo "Amount: " . $amount;
        }  else {
            echo "No amount found in the text.";
        }
        
    }

}
