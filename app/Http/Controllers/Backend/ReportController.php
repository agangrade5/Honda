<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    protected string $apiUrl  = 'https://honda.kickstartuser.com/API/';
    protected string $apiKey  = '193baa84819bbb16cba9e70d443bcb6c';
    protected string $adminUrl = 'https://honda.kickstartuser.com/API/APIAdmin.php';

    /* ── Generic API helper ── */
    private function callReportApi(string $endpoint, array $extra = []): mixed
    {
        $payload = array_merge([
            'ConnectionData' => [
                'APIKey'     => $this->apiKey,
                'ClientTime' => '01-05-2015 12:00:00',
            ],
        ], $extra);

        try {
            $response = Http::timeout(20)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->withoutVerifying()
                ->post($this->apiUrl . $endpoint, $payload);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // silent fail
        }
        return null;
    }

    /* ── Admin API (method-based) helper ── */
    private function callAdminApi(int $method, array $data = []): mixed
    {
        $payload = [
            'ConnectionData' => [
                'APIKey'     => $this->apiKey,
                'ClientTime' => '01-05-2015 12:00:00',
                'Method'     => $method,
            ],
            'Data' => $data,
        ];

        try {
            $response = Http::timeout(20)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->withoutVerifying()
                ->post($this->adminUrl, $payload);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // silent fail
        }
        return null;
    }

    /**
     * Display the reporting dashboard for a specific event.
     */
    public function show(string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            abort(404, 'Invalid Event ID.');
        }

        $event = DB::table('events')->where('eventid', $eventId)->first();

        if (!$event) {
            abort(404, 'Event not found.');
        }

        // method=108 → report1 (LeadGen, Dyno, TotalLeads, EmailOptIn …)
        $report1Raw = $this->callReportApi('APIReports2.php?method=108', ['EventID' => $eventId]);
        // method=109 → report2 (stateData, stateTotalCount …)
        $report2Raw = $this->callReportApi('APIReports2.php?method=109', ['EventID' => $eventId]);
        // method=110 → report3 (MaleLeads, FemaleLeads, AveragAge …)
        $report3Raw = $this->callReportApi('APIReports2.php?method=110', ['EventID' => $eventId]);

        // method=2002 → fetch_all_report_data2 (dynamic_survey_data, CustTrans)
        $reportData2Raw = $this->callAdminApi(2002, ['EventID' => $eventId]);

        // Build GraphTrans (Demo rides by model) from CustTrans
        $graphTrans = [];
        if (!empty($reportData2Raw->CustTrans)) {
            foreach ($reportData2Raw->CustTrans as $row) {
                if (!empty($row->transdescriptionblob)) {
                    $decoded = json_decode($row->transdescriptionblob);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded->Model)) {
                        $graphTrans[$decoded->Model][] = $decoded->Model;
                    }
                }
            }
        }

        // Generate share link (base64 encode of eventId as simple encryption)
        $shareLink = url('/ReportingDashboardView/' . base64_encode($eventId));

        return view('backend.events.report', [
            'title'        => 'Event Report – ' . $event->eventname,
            'event'        => $event,
            'report1'      => (array)($report1Raw ?? []),
            'report2'      => (array)($report2Raw ?? []),
            'report3'      => (array)($report3Raw ?? []),
            'reportData2'  => $reportData2Raw,
            'graphTrans'   => $graphTrans,
            'shareLink'    => $shareLink,
            'encodedId'    => $encodedId,
        ]);
    }

    /**
     * AJAX: Demo Reports with date range (method=106).
     */
    public function demoReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        if ($startDate === null && $endDate === null) {
            $formattedStart = date('Y-m-d');
            $formattedEnd   = date('Y-m-d');
        } else {
            $formattedStart = $startDate ? date('Y-m-d H:i:s', strtotime($startDate)) : '';
            $formattedEnd   = $endDate ? date('Y-m-d H:i:s', strtotime($endDate)) : '';
        }

        $data = $this->callReportApi('APIReports2.php?method=106', [
            'EventID'   => $eventId,
            'startDate' => $formattedStart,
            'endDate'   => $formattedEnd,
        ]);

        return response()->json($data);
    }

    /**
     * AJAX: Stats (report3) with date range (method=110).
     */
    public function statsReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        if ($startDate === null && $endDate === null) {
            $formattedStart = date('Y-m-d');
            $formattedEnd   = date('Y-m-d');
        } else {
            $formattedStart = $startDate ? date('Y-m-d H:i:s', strtotime($startDate)) : '';
            $formattedEnd   = $endDate ? date('Y-m-d H:i:s', strtotime($endDate)) : '';
        }

        $data = $this->callReportApi('APIReports2.php?method=110', [
            'EventID'   => $eventId,
            'startDate' => $formattedStart,
            'endDate'   => $formattedEnd,
        ]);

        return response()->json($data);
    }

    /**
     * Helper to decode encoded or encrypted Event ID.
     */
    private function decodeEventId(string $encodedId): ?int
    {
        // Try simple base64 first
        $decoded = base64_decode($encodedId, true);
        if ($decoded !== false && is_numeric($decoded)) {
            return (int)$decoded;
        }

        // Try legacy AES-256-ECB decryption
        try {
            $skey = "SuPerEncKey2010";
            $cipher = 'AES-256-ECB';
            $data = str_replace(['-', '_'], ['+', '/'], $encodedId);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }
            $crypttext = base64_decode($data);
            $decrypted = openssl_decrypt(
                $crypttext,
                $cipher,
                $skey,
                OPENSSL_RAW_DATA
            );
            if ($decrypted !== false && is_numeric(trim($decrypted))) {
                return (int)trim($decrypted);
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return null;
    }

    /**
     * Public page view for share links (openable without login).
     */
    public function publicReport(string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            abort(404, 'Invalid Report ID.');
        }

        // Fetch Event Info
        $event = DB::table('events')->where('eventid', $eventId)->first();
        if (!$event) {
            abort(404, 'Event not found.');
        }

        // Fetch Report Data (API Calls)
        $report1Raw = $this->callReportApi('APIReports2.php?method=108', ['EventID' => $eventId]);
        $report2Raw = $this->callReportApi('APIReports2.php?method=109', ['EventID' => $eventId]);
        $report3Raw = $this->callReportApi('APIReports2.php?method=110', ['EventID' => $eventId]);
        $reportData2Raw = $this->callAdminApi(2002, ['EventID' => $eventId]);

        // Process Graph Trans from CustomerTrans
        $graphTrans = [];
        if (isset($reportData2Raw->CustTrans->CustomerTrans1)) {
            $custTrans = is_array($reportData2Raw->CustTrans->CustomerTrans1) 
                ? $reportData2Raw->CustTrans->CustomerTrans1 
                : [$reportData2Raw->CustTrans->CustomerTrans1];

            foreach ($custTrans as $tran) {
                if (!empty($tran->ModelName)) {
                    $graphTrans[$tran->ModelName][] = $tran;
                }
            }
        }

        return view('backend.events.public-report', [
            'title'        => 'Public Event Report – ' . $event->eventname,
            'event'        => $event,
            'report1'      => (array)($report1Raw ?? []),
            'report2'      => (array)($report2Raw ?? []),
            'report3'      => (array)($report3Raw ?? []),
            'reportData2'  => $reportData2Raw,
            'graphTrans'   => $graphTrans,
            'encodedId'    => $encodedId,
        ]);
    }

    /**
     * AJAX public demo reports.
     */
    public function publicDemoReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        if ($startDate === null && $endDate === null) {
            $formattedStart = date('Y-m-d');
            $formattedEnd   = date('Y-m-d');
        } else {
            $formattedStart = $startDate ? date('Y-m-d H:i:s', strtotime($startDate)) : '';
            $formattedEnd   = $endDate ? date('Y-m-d H:i:s', strtotime($endDate)) : '';
        }

        $data = $this->callReportApi('APIReports2.php?method=106', [
            'EventID'   => $eventId,
            'startDate' => $formattedStart,
            'endDate'   => $formattedEnd,
        ]);

        return response()->json($data);
    }

    /**
     * AJAX public stats reports.
     */
    public function publicStatsReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        if ($startDate === null && $endDate === null) {
            $formattedStart = date('Y-m-d');
            $formattedEnd   = date('Y-m-d');
        } else {
            $formattedStart = $startDate ? date('Y-m-d H:i:s', strtotime($startDate)) : '';
            $formattedEnd   = $endDate ? date('Y-m-d H:i:s', strtotime($endDate)) : '';
        }

        $data = $this->callReportApi('APIReports2.php?method=110', [
            'EventID'   => $eventId,
            'startDate' => $formattedStart,
            'endDate'   => $formattedEnd,
        ]);

        return response()->json($data);
    }

    /**
     * Public AJAX NHRA reports.
     */
    public function publicNHRAReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        if ($startDate === null && $endDate === null) {
            $formattedStart = date('Y-m-d');
            $formattedEnd   = date('Y-m-d');
        } else {
            $formattedStart = $startDate ? date('Y-m-d H:i:s', strtotime($startDate)) : '';
            $formattedEnd   = $endDate ? date('Y-m-d H:i:s', strtotime($endDate)) : '';
        }

        $data = $this->callReportApi('APIReports2.php?method=115', [
            'EventID'   => $eventId,
            'startDate' => $formattedStart,
            'endDate'   => $formattedEnd,
        ]);

        return response()->json($data);
    }

    /**
     * Handle legacy public share URL without redirection.
     */
    public function legacyPublicReport(Request $request)
    {
        $id = $request->query('id');
        if (!$id) {
            abort(404, 'Report ID not found.');
        }

        return $this->publicReport($id);
    }
}
