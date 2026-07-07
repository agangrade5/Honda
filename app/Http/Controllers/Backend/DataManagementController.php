<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DataManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::select('eventid', 'eventname')->orderBy('eventname', 'asc')->get();
        $surveys = Survey::select('surveyid', 'surveyname')->orderBy('surveyname', 'asc')->get();

        return view('backend.data-management.index', [
            'title' => 'Manage Data Management',
            'events' => $events,
            'surveys' => $surveys
        ]);
    }

    /**
     * Export data according to action (cust, survey, honda) into Excel spreadsheet.
     */
    public function export(Request $request)
    {
        $request->validate([
            'EventID' => 'required|integer',
            'action' => 'required|string',
        ]);

        $eventID = $request->query('EventID');
        $action = $request->query('action');
        $rows = [];
        $sheetTitle = 'Data';

        if ($action == 'cust') {
            $sheetTitle = 'Customer';
            $custIds = \DB::table('customertrans')
                ->where('eventid', $eventID)
                ->pluck('custid')
                ->filter()
                ->unique()
                ->toArray();

            $rows[] = ["FIRST_NAME", "LAST_NAME", "MOBILE_PHONE", "EMAIL_ADDRESS", "ADDR_LINE_1", "ADDR_CITY", "ADDR_STATE_PROVINCE_CODE", "ADDR_COUNTRY_CODE", "POSTAL_CODE"];

            if (!empty($custIds)) {
                $customers = \DB::table('customers as c')
                    ->leftJoin('countries as co', 'c.custcountry', '=', 'co.countryid')
                    ->whereIn('c.custid', $custIds)
                    ->select('c.*', 'co.countrycode')
                    ->get();

                foreach ($customers as $customer) {
                    $custaddress = $customer->custaddress ?? '';
                    $ADDR_LINE = explode(',', $custaddress);
                    $custAddrLine1 = $ADDR_LINE[0] ?? '';
                    $custAddrCity = $ADDR_LINE[1] ?? '';
                    $custAddrStateProvinceCode = $ADDR_LINE[2] ?? '';
                    $custPostalCode = $ADDR_LINE[3] ?? '';

                    $rows[] = [
                        rtrim(trim($customer->custfname ?? ''), ","),
                        rtrim(trim($customer->custlname ?? ''), ","),
                        $customer->custphone ?? '',
                        $customer->custemail ?? '',
                        rtrim(trim($custAddrLine1), ","),
                        rtrim(trim($custAddrCity), ","),
                        rtrim(trim($custAddrStateProvinceCode), ","),
                        rtrim(trim($customer->countrycode ?? ''), ","),
                        rtrim(trim($custPostalCode), ","),
                    ];
                }
            }
        } elseif ($action == 'survey') {
            $sheetTitle = 'Survey';
            $surveyID = $request->query('SurveyID');

            $survey = \DB::table('surveys')->where('surveyid', $surveyID)->first();
            $surveyBlob = json_decode($survey->surveyblob ?? '{}');

            $questionIds = [];
            $questionHeaders = [];
            if (isset($surveyBlob->SurveyData) && is_array($surveyBlob->SurveyData)) {
                foreach ($surveyBlob->SurveyData as $q) {
                    $questionIds[] = $q->QuestionID;
                    $questionHeaders[$q->QuestionID] = $q->QuestionText[0]->LanguageText ?? '';
                }
            }

            $header = array_merge(
                ["FIRST_NAME", "LAST_NAME", "MOBILE_PHONE", "EMAIL_ADDRESS", "ADDR_LINE_1", "ADDR_CITY", "ADDR_STATE_PROVINCE_CODE", "ADDR_COUNTRY_CODE", "POSTAL_CODE"],
                array_values($questionHeaders)
            );
            $rows[] = $header;

            $custIds = \DB::table('customertrans')
                ->where('eventid', $eventID)
                ->pluck('custid')
                ->filter()
                ->unique()
                ->toArray();

            if (!empty($custIds)) {
                $surveyDatas = \DB::table('surveydata as sd')
                    ->leftJoin('customers as c', 'sd.custid', '=', 'c.custid')
                    ->leftJoin('countries as co', 'c.custcountry', '=', 'co.countryid')
                    ->where('sd.surveyid', $surveyID)
                    ->whereIn('sd.custid', $custIds)
                    ->select('sd.*', 'c.custfname', 'c.custlname', 'c.custphone', 'c.custemail', 'c.custaddress', 'co.countrycode')
                    ->get();

                foreach ($surveyDatas as $sd) {
                    $custaddress = $sd->custaddress ?? '';
                    $ADDR_LINE = explode(',', $custaddress);
                    $custAddrLine1 = $ADDR_LINE[0] ?? '';
                    $custAddrCity = $ADDR_LINE[1] ?? '';
                    $custAddrStateProvinceCode = $ADDR_LINE[2] ?? '';
                    $custPostalCode = $ADDR_LINE[3] ?? '';

                    $baseData = [
                        rtrim(trim($sd->custfname ?? ''), ","),
                        rtrim(trim($sd->custlname ?? ''), ","),
                        $sd->custphone ?? '',
                        $sd->custemail ?? '',
                        rtrim(trim($custAddrLine1), ","),
                        rtrim(trim($custAddrCity), ","),
                        rtrim(trim($custAddrStateProvinceCode), ","),
                        rtrim(trim($sd->countrycode ?? ''), ","),
                        rtrim(trim($custPostalCode), ","),
                    ];

                    $answersMap = [];
                    if (!empty($sd->surveydatablob)) {
                        $blob = json_decode($sd->surveydatablob);
                        if (is_array($blob)) {
                            foreach ($blob as $item) {
                                if (isset($item->QuestionID) && isset($item->SelectedAnswers[0]->AnswerValue)) {
                                    $answersMap[$item->QuestionID] = $item->SelectedAnswers[0]->AnswerValue;
                                }
                            }
                        }
                    }

                    foreach ($questionIds as $qId) {
                        $baseData[] = $answersMap[$qId] ?? '';
                    }

                    $rows[] = $baseData;
                }
            }
        } elseif ($action == 'honda') {
            $sheetTitle = 'Honda';
            $event = \DB::table('events')->where('eventid', $eventID)->first();

            if ($event) {
                $resultArray = \DB::table('customertrans as ct')
                    ->join('events as e', 'ct.eventid', '=', 'e.eventid')
                    ->leftJoin('customers as c', 'ct.custid', '=', 'c.custid')
                    ->leftJoin('ethnicity as eth', 'c.custethnicity', '=', 'eth.ethnicityid')
                    ->where('ct.eventid', $eventID)
                    ->whereNotNull('ct.custid')
                    ->where('ct.custid', '>', 0)
                    ->whereIn('ct.transtype', [39, 5, 0])
                    ->select(
                        'ct.*',
                        'e.demosurvey', 'e.postridesurvey', 'e.eventdealers', 'e.eventname', 'e.democc', 'e.leadgencc', 'e.photoappcc',
                        'c.cardnumber', 'c.custfname', 'c.custlname', 'c.custaddress', 'c.custcountry', 'c.custemail', 'c.custphone',
                        'c.custgender', 'c.custbirthday', 'c.custmotorcyclelic', 'c.custlicexpire', 'c.custdriverslicense',
                        'eth.ethnicityname'
                    )
                    ->get();

                $filteredResults = [];
                $seenTranstype0 = [];
                foreach ($resultArray as $row) {
                    if ($row->transtype == 0) {
                        $key = $row->transtype . '_' . $row->custid;
                        if (isset($seenTranstype0[$key])) {
                            continue;
                        }
                        $seenTranstype0[$key] = true;
                    }
                    $filteredResults[] = $row;
                }

                $countryCodes = \DB::table('countries')->pluck('countrycode', 'countryid')->toArray();

                $surveys = \DB::table('surveys')
                    ->whereIn('surveyid', array_filter([$event->demosurvey, $event->postridesurvey]))
                    ->get()
                    ->keyBy('surveyid');

                $surveyQuestions = [];
                $allQuestionIds = [];
                $allQuestionHeaders = [];
                foreach ($surveys as $sId => $s) {
                    $blob = json_decode($s->surveyblob ?? '{}');
                    if (isset($blob->SurveyData) && is_array($blob->SurveyData)) {
                        foreach ($blob->SurveyData as $q) {
                            $surveyQuestions[$sId][$q->QuestionID] = $q->QuestionText[0]->LanguageText ?? '';
                            if (!in_array($q->QuestionID, $allQuestionIds)) {
                                $allQuestionIds[] = $q->QuestionID;
                                $allQuestionHeaders[$q->QuestionID] = $q->QuestionText[0]->LanguageText ?? '';
                            }
                        }
                    }
                }

                $baseHeaders = ["CampaignCode","EventID","DealerID","AppType","EventName","FirstName","LastName","CardNumber","Address","City","State","PostalCode","Country","EmailAddress","PhoneNumber","Gender","DOB","MotorcycleLic","DriversLicense","DEMO_BIKE","ActivityDate","LicExpiration","CustomerOptin","SurveyID"];
                
                $header = array_merge($baseHeaders, array_values($allQuestionHeaders));
                $rows[] = $header;

                foreach ($filteredResults as $row) {
                    $campaigncode = "";
                    if ($row->transtype == 0) {
                        $campaigncode = $row->democc;
                    } elseif ($row->transtype == 1) {
                        $campaigncode = $row->leadgencc;
                    } elseif ($row->transtype == 17) {
                        $campaigncode = $row->photoappcc;
                    }

                    $custaddress = $row->custaddress ?? '';
                    $ADDR_LINE = explode(',', $custaddress);
                    $custAddrLine1 = $ADDR_LINE[0] ?? '';
                    $custAddrCity = $ADDR_LINE[1] ?? '';
                    $custAddrStateProvinceCode = $ADDR_LINE[2] ?? '';
                    $custPostalCode = $ADDR_LINE[3] ?? '';

                    $countryId = $row->custcountry ?? '';
                    $custAddrCountryCode = $countryCodes[$countryId] ?? 'USA';

                    $dob = $row->custbirthday ? date("m-d-Y", strtotime($row->custbirthday)) : '';
                    $licExp = $row->custlicexpire ? date("m-d-Y", strtotime($row->custlicexpire)) : '';

                    $demoBike = "";
                    if ($row->transtype == 39) {
                        $transDesc = json_decode($row->transdescriptionblob ?? '{}');
                        $demoBike = $transDesc->Model ?? '';
                    }

                    $targetSurveyId = null;
                    if ($row->transtype == 39 || $row->transtype == 0) {
                        $targetSurveyId = $row->demosurvey;
                    } elseif ($row->transtype == 5) {
                        $targetSurveyId = $row->postridesurvey;
                    }

                    $surveyAnswersMap = [];
                    if ($targetSurveyId) {
                        $surveyDataRows = \DB::table('surveydata')
                            ->where('custid', $row->custid)
                            ->whereIn('surveyid', array_filter([$row->demosurvey, $row->postridesurvey]))
                            ->get();

                        foreach ($surveyDataRows as $sdr) {
                            $blob = json_decode($sdr->surveydatablob ?? '[]');
                            if (is_array($blob)) {
                                foreach ($blob as $item) {
                                    if (isset($item->QuestionID) && isset($item->SelectedAnswers[0]->AnswerValue)) {
                                        $surveyAnswersMap[$item->QuestionID] = $item->SelectedAnswers[0]->AnswerValue;
                                    }
                                }
                            }
                        }
                    }

                    $rowData = [
                        $campaigncode,
                        $row->eventid,
                        $row->eventdealers,
                        $row->transtype,
                        $row->eventname,
                        rtrim(trim($row->custfname ?? ''), ","),
                        rtrim(trim($row->custlname ?? ''), ","),
                        $row->cardnumber ?? '',
                        rtrim(trim($custAddrLine1), ","),
                        rtrim(trim($custAddrCity), ","),
                        rtrim(trim($custAddrStateProvinceCode), ","),
                        rtrim(trim($custPostalCode), ","),
                        rtrim(trim($custAddrCountryCode), ","),
                        $row->custemail ?? '',
                        $row->custphone ?? '',
                        $row->custgender ?? '',
                        $dob,
                        $row->custmotorcyclelic ?? '',
                        $row->custdriverslicense ?? '',
                        $demoBike,
                        $row->transdate ?? '',
                        $licExp,
                        $row->custoptin ?? '',
                        $targetSurveyId ?? '',
                    ];

                    foreach ($allQuestionIds as $qId) {
                        $rowData[] = $surveyAnswersMap[$qId] ?? '';
                    }

                    $rows[] = $rowData;
                }
            }
        }

        // Generate Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetTitle);

        foreach ($rows as $rowIndex => $rowData) {
            $r = $rowIndex + 1;
            foreach ($rowData as $columnIndex => $value) {
                $colString = Coordinate::stringFromColumnIndex($columnIndex + 1);
                $sheet->setCellValue($colString . $r, $value);
            }
        }

        $fileName = time() . '.xlsx';
        $publicFolder = public_path('excel');
        if (!File::exists($publicFolder)) {
            File::makeDirectory($publicFolder, 0755, true);
        }
        $filePath = $publicFolder . '/' . $fileName;

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath, $fileName);
    }
}
