<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\FileHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GenerateCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = FileHistory::orderBy('historyid', 'desc')->get();

        return view('backend.generate-cards.index', [
            'title' => 'Manage Generate Cards',
            'histories' => $histories
        ]);
    }

    /**
     * Store new card numbers batch, write to Excel, and log history.
     */
    public function store(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1',
        ]);

        $count = (int)$request->input('count');
        $maxBatch = Card::max('cardbatch');
        $updated_cardbatch = ($maxBatch ?: 0) + 1;

        $cards = [];
        for ($i = 0; $i < $count; $i++) {
            $tempCardNumber = $this->generateNewCardNumber();
            
            // Check if card number already exists
            while (Card::where('cardnumber', $tempCardNumber)->exists()) {
                $tempCardNumber = $this->generateNewCardNumber();
            }

            Card::create([
                'cardnumber' => $tempCardNumber,
                'cardtype' => 'Virtual',
                'cardbatch' => $updated_cardbatch,
                'clientid' => 1,
                'recordstatus' => 0
            ]);

            $cards[] = $tempCardNumber;
        }

        // Generate Excel file
        $fileName = time() . '.xlsx';
        $relativeFolder = 'history';
        $publicFolder = public_path($relativeFolder);
        
        if (!File::exists($publicFolder)) {
            File::makeDirectory($publicFolder, 0755, true);
        }

        $filePath = $publicFolder . '/' . $fileName;
        $dbFilePath = $relativeFolder . '/' . $fileName;

        $cardSuffixData = $request->input('card', []);
        $cardNoData = $request->input('card1', []);

        if (!empty($cardNoData)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Card');

            $excel_count = 1;
            foreach ($cardNoData as $key => $gcard) {
                // $gcard contains the count of cards for this specific suffix
                $numCards = isset($gcard['card_no']) ? (int)$gcard['card_no'] : 0;
                $suffix = isset($cardSuffixData[$key]['card_suffix']) ? $cardSuffixData[$key]['card_suffix'] : '';

                for ($i = 0; $i < $numCards; $i++) {
                    if (isset($cards[$i])) {
                        $card_no = $cards[$i] . $suffix;
                        $sheet->setCellValue('A' . $excel_count, $card_no);
                        $excel_count++;
                    }
                }
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        // Add File History
        FileHistory::create([
            'historyfilepath' => $dbFilePath,
            'historycardbatch' => $updated_cardbatch,
            'historyfiledate' => now()
        ]);

        return redirect()->back()->with(['msg' => 'The File has been created successfully', 'status' => 'success']);
    }

    /**
     * Generate unique 15-digit card number avoiding consecutive duplicates.
     */
    private function generateNewCardNumber()
    {
        $fullCardNumber = '';
        $previousDigit = '';

        for ($x = 1; $x <= 15; $x++) {
            $tmpDigit = rand(0, 9);
            while ($tmpDigit == $previousDigit) {
                $tmpDigit = rand(0, 9);
            }
            $fullCardNumber .= $tmpDigit;
            $previousDigit = $tmpDigit;
        }

        return $fullCardNumber;
    }
}
