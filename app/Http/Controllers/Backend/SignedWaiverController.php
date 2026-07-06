<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LegalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;

class SignedWaiverController extends Controller
{
    /**
     * Display a listing.
     */
    public function index()
    {
        $count = (object)[
            'Count' => LegalData::count(),
        ];

        return view('backend.signed-waivers.index', [
            'title' => 'Manage Signed Waiver',
            'count' => $count,
        ]);
    }

    /**
     * Get DataTable server-side data.
     */
    public function getData(Request $request)
    {
        $query = LegalData::query()
            ->select([
                'legaldata.legaldataid',
                'customers.custfname',
                'customers.custlname',
                'legal.legalname',
                'legaldata.legaldoclocation',
                'legal.legalhtml',
                'legaldata.custid',
            ])
            ->leftJoin('customers', 'legaldata.custid', '=', 'customers.custid')
            ->leftJoin('legal', 'legaldata.legalid', '=', 'legal.legalid');

        // Total records before filtering
        $totalRecords = $query->count();

        // Apply yadcf column filters
        if ($request->filled('columns')) {
            $columns = $request->input('columns');
            
            // Column 0: Waiver Data ID / #
            if (!empty($columns[0]['search']['value'])) {
                $query->where('legaldata.legaldataid', $columns[0]['search']['value']);
            }
            // Column 1: First Name
            if (!empty($columns[1]['search']['value'])) {
                $query->where('customers.custfname', 'LIKE', '%' . $columns[1]['search']['value'] . '%');
            }
            // Column 2: Last Name
            if (!empty($columns[2]['search']['value'])) {
                $query->where('customers.custlname', 'LIKE', '%' . $columns[2]['search']['value'] . '%');
            }
            // Column 3: Legal Name
            if (!empty($columns[3]['search']['value'])) {
                $query->where('legal.legalname', 'LIKE', '%' . $columns[3]['search']['value'] . '%');
            }
        }

        // Apply global search if filled
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('legaldata.legaldataid', 'LIKE', '%' . $searchValue . '%')
                  ->orWhere('customers.custfname', 'LIKE', '%' . $searchValue . '%')
                  ->orWhere('customers.custlname', 'LIKE', '%' . $searchValue . '%')
                  ->orWhere('legal.legalname', 'LIKE', '%' . $searchValue . '%');
            });
        }

        // Filtered count
        $filteredRecords = $query->count();

        // Order
        if ($request->filled('order')) {
            $order = $request->input('order');
            $orderColumnIndex = $order[0]['column'];
            $orderDir = $order[0]['dir'];

            $columnsMapping = [
                0 => 'legaldata.legaldataid',
                1 => 'customers.custfname',
                2 => 'customers.custlname',
                3 => 'legal.legalname',
            ];

            if (isset($columnsMapping[$orderColumnIndex])) {
                $query->orderBy($columnsMapping[$orderColumnIndex], $orderDir);
            }
        } else {
            $query->orderBy('legaldata.legaldataid', 'desc');
        }

        // Paginate
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $results = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($results as $row) {
            // Output actions exactly as expected by the legacy JS
            $actions = '<input type="hidden" id="WaiverDOCLocation' . $row->legaldataid . '" value="' . e($row->legaldoclocation) . '">';
            $actions .= '<div id="WaiverHTML' . $row->legaldataid . '" style="display:none;">' . $row->legalhtml . '</div>';
            $actions .= '<a href="javascript:;" id="' . $row->legaldataid . '" class="btn btn-secondary btn-sm btn-icon icon-left btn-view-signature">View Signature</a>';

            $data[] = [
                $row->legaldataid,
                $row->custfname,
                $row->custlname,
                $row->legalname,
                $actions,
            ];
        }

        return response()->json([
            'draw' => (int)$request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    /**
     * Download waiver document as PDF.
     */
    public function downloadPdf($id)
    {
        $row = LegalData::select([
                'legaldata.legaldataid',
                'customers.custfname',
                'customers.custlname',
                'customers.custphone',
                'customers.custdriverslicense',
                'legal.legalname',
                'legaldata.legaldoclocation',
                'legal.legalhtml',
                'legaldata.custid',
                'legaldata.legalsignaturetime',
            ])
            ->leftJoin('customers', 'legaldata.custid', '=', 'customers.custid')
            ->leftJoin('legal', 'legaldata.legalid', '=', 'legal.legalid')
            ->where('legaldata.legaldataid', $id)
            ->firstOrFail();

        $html = "<h1>" . e($row->legalname) . "  -  " . e($row->legalsignaturetime) . "</h1>";
        $html .= $row->legalhtml;

        $signImgPath = public_path('API/assets/legal/sigs/');
        $imageName = basename($row->legaldoclocation);
        $fullSignImgPath = $signImgPath . $imageName;

        if (file_exists($fullSignImgPath)) {
            $type = pathinfo($fullSignImgPath, PATHINFO_EXTENSION);
            $data = file_get_contents($fullSignImgPath);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $html .= ' <img width="565" id="WaiverSignedImg" src="' . $base64 . '" />';
        }

        $html .= '<p> ' . e($row->custfname) . ' ' . e($row->custlname) . ' - ' . e($row->custphone) . '  </p>';
        $html .= '<p> ' . e($row->custdriverslicense) . '   </p>';

        // Fetch photo location from regphotos table
        $photoLocation = DB::table('regphotos')
            ->where('custid', $row->custid)
            ->orderBy('photoid', 'desc')
            ->value('photolocation');

        if ($photoLocation) {
            $cleanedPhotoPath = ltrim($photoLocation, '/');
            $photoFullPath = public_path($cleanedPhotoPath);
            
            if (!file_exists($photoFullPath)) {
                $photoFullPath = public_path('API/' . $cleanedPhotoPath);
            }

            if (file_exists($photoFullPath)) {
                $type = pathinfo($photoFullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($photoFullPath);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $html .= ' <img width="350" id="WaiverSignedImg" src="' . $base64 . '" />';
            }
        }

        $html .= "<p> [X] IMPORTANT: THIS IS A WAIVER & RELEASE OF LIABILITY AGREEMENT. PLEASE READ THIS AGREEMENT CAREFULLY BEFORE CLICKING “I AGREE.” I AGREE: I " . e($row->custfname) . " " . e($row->custlname) . ", agree to and accept the Agreement set forth above and agree to be bound by all of its releases, assumptions of risk, statements, acknowledgements, certifications and other provisions.</p>";

        $html .= "<p> [X] I AGREE: I " . e($row->custfname) . " " . e($row->custlname) . ", agree to and accept the terms of the Registration, Acknowledgements and Agreement as set forth above and agree to be bound by all of its terms and conditions.</p>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="waiver.pdf"');
    }
}
