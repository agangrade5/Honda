<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    /* ── Local query helper methods replacing legacy API ── */

    private function mapBrandNameToKey(string $brandName): ?string
    {
        $normalized = strtolower(str_replace([' ', '-', '_'], '', $brandName));
        if (str_contains($normalized, 'harley')) {
            return 'HarleyDavidson';
        }
        if ($normalized === 'bmw') {
            return 'BMW';
        }
        if ($normalized === 'ducati') {
            return 'Ducati';
        }
        if ($normalized === 'honda') {
            return 'Honda';
        }
        if ($normalized === 'kawasaki') {
            return 'Kawasaki';
        }
        if ($normalized === 'suzuki') {
            return 'Suzuki';
        }
        if ($normalized === 'triumph') {
            return 'Triumph';
        }
        if ($normalized === 'yamaha') {
            return 'Yamaha';
        }
        if ($normalized === 'other' || $normalized === 'none' || $normalized === 'never' || $normalized === 'neverowned') {
            return 'Other';
        }
        return null;
    }

    private function getReport106Data(int $eventId, ?string $startDate = null, ?string $endDate = null): array
    {
        $queryTDR = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->where('transtype', 39);

        $queryJump = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->whereIn('transtype', [2, 12, 14, 16, 19, 22, 24, 26, 28, 30]);

        $queryLead = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->whereIn('transtype', [1, 11, 13, 15, 18, 21, 23, 25, 27, 29]);

        $queryReg = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->where('transtype', 0);

        if ($startDate && $endDate) {
            $formattedStart = date('Y-m-d H:i:s', strtotime($startDate));
            $formattedEnd   = date('Y-m-d H:i:s', strtotime($endDate));
            $formattedEnd   = str_replace("00:00:00", "23:59:59", $formattedEnd);
            $formattedEnd   = str_replace(":00", ":59", $formattedEnd);

            $queryTDR->whereBetween('transdate', [$formattedStart, $formattedEnd]);
            $queryJump->whereBetween('transdate', [$formattedStart, $formattedEnd]);
            $queryLead->whereBetween('transdate', [$formattedStart, $formattedEnd]);
            $queryReg->whereBetween('transdate', [$formattedStart, $formattedEnd]);
        } else {
            $date = date("Y-m-d");
            $queryTDR->whereRaw('DATE(transdate) > ?', [$date]);
            $queryJump->whereRaw('DATE(transdate) > ?', [$date]);
            $queryLead->whereRaw('DATE(transdate) > ?', [$date]);
            $queryReg->whereRaw('DATE(transdate) > ?', [$date]);
        }

        $tdr = $queryTDR->count();
        $jump = $queryJump->count();
        $lead = $queryLead->count();
        $reg = $queryReg->distinct('custid')->count('custid');

        return [
            'Message'           => 'Success',
            'TotalDemoRides'    => $tdr,
            'DemoRegistrations' => $reg,
            'Jumpstart'         => $jump,
            'LeadGen'           => $lead,
        ];
    }

    private function getReport108Data(int $eventId): array
    {
        $totalLeads = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->distinct('custid')
            ->count('custid');

        $count1 = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->whereIn('transtype', [1, 11, 13, 15, 18, 21, 23, 25, 27, 29])
            ->count();

        $count2 = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->whereIn('transtype', [2, 12, 14, 16, 19, 22, 24, 26, 28, 30])
            ->count();

        $count7 = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->where('transtype', 7)
            ->count();

        $count8 = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->where('transtype', 8)
            ->count();

        $emailOptIn = DB::table('customertrans as ct')
            ->join('customers as cm', 'ct.custid', '=', 'cm.custid')
            ->where('ct.eventid', $eventId)
            ->where('ct.custid', '>', 0)
            ->where('cm.custoptin', 1)
            ->distinct('ct.custid')
            ->count('ct.custid');

        return [
            'Message'    => 'Success',
            'TotalLeads' => $totalLeads,
            'count1'     => $count1,
            'count2'     => $count2,
            'count7'     => $count7,
            'count8'     => $count8,
            'EmailOptIn' => $emailOptIn,
        ];
    }

    private function getReport109Data(int $eventId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = DB::table('customers as cm')
            ->leftJoin('ethnicity as eth', 'cm.custethnicity', '=', 'eth.ethnicityid')
            ->select('cm.custbirthday', 'cm.custgender', 'cm.custaddress', 'eth.ethnicityname')
            ->whereIn('cm.custid', function ($sub) use ($eventId, $startDate, $endDate) {
                $sub->select('custid')
                    ->from('customertrans')
                    ->where('eventid', $eventId)
                    ->where('custid', '>', 0);
                if ($startDate && $endDate) {
                    $formattedStart = date('Y-m-d H:i:s', strtotime($startDate));
                    $formattedEnd   = date('Y-m-d H:i:s', strtotime($endDate));
                    $formattedEnd   = str_replace("00:00:00", "23:59:59", $formattedEnd);
                    $formattedEnd   = str_replace(":00", ":59", $formattedEnd);
                    $sub->whereBetween('transdate', [$formattedStart, $formattedEnd]);
                }
            });

        $customersArray = $query->get();

        $surveyCollected = DB::table('surveydata')
            ->where('eventid', $eventId)
            ->count();

        $segment1 = 0;
        $segment2 = 0;
        $segment3 = 0;
        $segment4 = 0;
        $segment5 = 0;
        $segment6 = 0;
        $stateData = [];
        $segmentTotal = 0;

        foreach ($customersArray as $custData) {
            $custBorn = strtotime($custData->custbirthday);
            $custAge = $custBorn ? floor((time() - $custBorn) / 31556926) : 0;
            $custAddressArray = explode(",", $custData->custaddress);
            if (isset($custAddressArray[2]) && !empty($custAddressArray[2])) {
                $stateName = trim($custAddressArray[2]);
                if (!isset($stateData[$stateName])) {
                    $stateData[$stateName] = 0;
                }
                $stateData[$stateName]++;
            }

            // SEGMENT 1 : <= 35 and >= 18
            if ($custAge >= 18 && $custAge <= 35) {
                $segment1++;
                $segmentTotal++;
            }

            // SEGMENT 2 : > 35 and M and Caucasian
            if ($custAge > 35 && $custData->custgender == "M" && $custData->ethnicityname == "Caucasian") {
                $segment2++;
                $segmentTotal++;
            }

            // SEGMENT 3 : > 35 and F and Caucasian
            if ($custAge > 35 && $custData->custgender == "F" && $custData->ethnicityname == "Caucasian") {
                $segment3++;
                $segmentTotal++;
            }

            // SEGMENT 4 : > 35 and Hispanic
            if ($custAge > 35 && $custData->ethnicityname == "Hispanic") {
                $segment4++;
                $segmentTotal++;
            }

            // SEGMENT 5 : > 35 and AfricanAmerican
            if ($custAge > 35 && $custData->ethnicityname == "AfricanAmerican") {
                $segment5++;
                $segmentTotal++;
            }

            // SEGMENT 6 : > 35 and Other
            if ($custAge > 35 && $custData->ethnicityname == "Other") {
                $segment6++;
                $segmentTotal++;
            }
        }

        arsort($stateData);

        return [
            'Message'          => 'Success',
            'segment1'         => $segment1,
            'segment2'         => $segment2,
            'segment3'         => $segment3,
            'segment4'         => $segment4,
            'segment5'         => $segment5,
            'segment6'         => $segment6,
            'SurveysCollected' => $surveyCollected,
            'segmentTotal'     => $segmentTotal,
            'stateData'        => $stateData,
            'stateTotalCount'  => array_sum($stateData),
        ];
    }

    private function getReport110Data(int $eventId, ?string $startDate = null, ?string $endDate = null): array
    {
        $l_result = DB::table('customers')
            ->where('custmotorcyclelic', 1)
            ->whereIn('custid', function ($sub) use ($eventId) {
                $sub->select('custid')->from('customertrans')->where('eventid', $eventId);
            })
            ->count();

        $nl_result = DB::table('customers')
            ->where('custmotorcyclelic', 0)
            ->whereIn('custid', function ($sub) use ($eventId) {
                $sub->select('custid')->from('customertrans')->where('eventid', $eventId);
            })
            ->count();

        $query = DB::table('surveydata as s')
            ->leftJoin('customers as c', 's.custid', '=', 'c.custid')
            ->select('s.*', 'c.custgender', 'c.custbirthday');

        if ($startDate && $endDate) {
            $formattedStart = date('Y-m-d H:i:s', strtotime($startDate));
            $formattedEnd   = date('Y-m-d H:i:s', strtotime($endDate));
            $formattedEnd   = str_replace("00:00:00", "23:59:59", $formattedEnd);
            $formattedEnd   = str_replace(":00", ":59", $formattedEnd);
            $query->where('s.eventid', $eventId)
                  ->whereBetween('s.surveydatetime', [$formattedStart, $formattedEnd]);
        } else {
            $query->whereIn('s.custid', function ($sub) use ($eventId) {
                $sub->select('custid')
                    ->from('customertrans')
                    ->where('eventid', $eventId)
                    ->distinct();
            });
        }

        $surveyDataArray = $query->get();

        $totalMaleLeads = 0;
        $totalFemaleLeads = 0;
        $totalAgeMale = 0;
        $totalAgeFemale = 0;
        $underThirty = 0;
        $betweenThirtyForty = 0;
        $betweenFourtyFifty = 0;
        $aboveFifty = 0;
        $totalNeverOwned = 0;

        $possibleAnswerNo = 0;
        $possibleAnswerYesVeteran = 0;
        $possibleAnswerYesActive = 0;
        $possibleAnswerTotal = 0;

        $intentToLearnAnswers = [
            'LessThanThreeMonths' => 0,
            'ThreeToTwelveMonths' => 0,
            'MoreThanOneYear'     => 0,
            'NotSure'             => 0,
            'OverallNotSure'      => 0,
            'NotInterested'       => 0,
            'TotalInterested'     => 0,
        ];

        $answerCountArray = [
            'MoreThanOneYear'     => 0,
            'LessThanThreeMonths' => 0,
            'ThreeToTwelveMonths' => 0,
            'NotSure'             => 0,
            'NoIntentToPurchase'  => 0,
        ];

        $currentlyOwnedResponses = [
            'HarleyDavidson' => 0,
            'BMW'            => 0,
            'Ducati'         => 0,
            'Honda'          => 0,
            'Kawasaki'       => 0,
            'Suzuki'         => 0,
            'Triumph'        => 0,
            'Yamaha'         => 0,
            'Other'          => 0,
        ];

        $previouslyOwnedResponses = [
            'HarleyDavidson' => 0,
            'BMW'            => 0,
            'Ducati'         => 0,
            'Honda'          => 0,
            'Kawasaki'       => 0,
            'Suzuki'         => 0,
            'Triumph'        => 0,
            'Yamaha'         => 0,
            'Other'          => 0,
        ];

        foreach ($surveyDataArray as $surveyDataVal) {
            $born = strtotime($surveyDataVal->custbirthday);
            $age = $born ? floor((time() - $born) / 31556926) : 0;

            if ($surveyDataVal->custgender == 'M') {
                $totalMaleLeads++;
                if ($age > 0) {
                    $totalAgeMale += $age;
                }
            } else {
                $totalFemaleLeads++;
                if ($age > 0) {
                    $totalAgeFemale += $age;
                }
            }

            if ($age > 0) {
                if ($age < 30) {
                    $underThirty++;
                } else if ($age >= 30 && $age < 40) {
                    $betweenThirtyForty++;
                } else if ($age >= 40 && $age <= 50) {
                    $betweenFourtyFifty++;
                } else if ($age > 50) {
                    $aboveFifty++;
                }
            }

            if (!empty($surveyDataVal->surveydatablob)) {
                $surveyBlob = json_decode($surveyDataVal->surveydatablob);
                if (is_array($surveyBlob)) {
                    foreach ($surveyBlob as $surveyBlobRow) {
                        // possible answers (Active Military)
                        if (isset($surveyBlobRow->QuestionID) && $surveyBlobRow->QuestionID == 7) {
                            if (isset($surveyBlobRow->SelectedAnswers[0])) {
                                $ansVal = $surveyBlobRow->SelectedAnswers[0]->AnswerValue;
                                if ($ansVal == "No") {
                                    $possibleAnswerNo++;
                                    $possibleAnswerTotal++;
                                } else if ($ansVal == "Yes, Veteran") {
                                    $possibleAnswerYesVeteran++;
                                    $possibleAnswerTotal++;
                                } else if ($ansVal == "Yes, Active") {
                                    $possibleAnswerYesActive++;
                                    $possibleAnswerTotal++;
                                }
                            }
                        }

                        // Intent to Purchase
                        if (isset($surveyBlobRow->SelectedAnswers[0])) {
                            $ansId = $surveyBlobRow->SelectedAnswers[0]->AnswerID;
                            $ansVal = $surveyBlobRow->SelectedAnswers[0]->AnswerValue ?? '';

                            $mappedIntent = null;
                            if ($ansId == 22 || str_contains(strtolower($ansVal), '0-3') || str_contains(strtolower($ansVal), 'less than 3')) {
                                $mappedIntent = 'LessThanThreeMonths';
                            } else if ($ansId == 23 || str_contains(strtolower($ansVal), '3-6') || str_contains(strtolower($ansVal), '6-12') || str_contains(strtolower($ansVal), '3 months to')) {
                                $mappedIntent = 'ThreeToTwelveMonths';
                            } else if ($ansId == 24 || str_contains(strtolower($ansVal), '1 year') || str_contains(strtolower($ansVal), 'more than a year')) {
                                $mappedIntent = 'MoreThanOneYear';
                            } else if ($ansId == 25 || str_contains(strtolower($ansVal), 'not sure')) {
                                $mappedIntent = 'NotSure';
                            } else if ($ansId == 26 || str_contains(strtolower($ansVal), 'none') || str_contains(strtolower($ansVal), 'no intent')) {
                                $mappedIntent = 'NoIntentToPurchase';
                            }

                            if ($mappedIntent) {
                                $answerCountArray[$mappedIntent]++;
                            }

                            // Brand mappings
                            if ($ansId == 13) $currentlyOwnedResponses['HarleyDavidson']++;
                            if ($ansId == 14) $currentlyOwnedResponses['BMW']++;
                            if ($ansId == 15) $currentlyOwnedResponses['Ducati']++;
                            if ($ansId == 16) $currentlyOwnedResponses['Honda']++;
                            if ($ansId == 17) $currentlyOwnedResponses['Kawasaki']++;
                            if ($ansId == 18) $currentlyOwnedResponses['Suzuki']++;
                            if ($ansId == 19) $currentlyOwnedResponses['Triumph']++;
                            if ($ansId == 20) $currentlyOwnedResponses['Yamaha']++;
                            if ($ansId == 21) $currentlyOwnedResponses['Other']++;
                            if ($ansId == 12) $totalNeverOwned++;

                            // Also try string value matching
                            $brandKey = $this->mapBrandNameToKey($ansVal);
                            if ($brandKey) {
                                $currentlyOwnedResponses[$brandKey]++;
                                $previouslyOwnedResponses[$brandKey]++;
                            }
                        }

                        // Intent to Learn
                        if (isset($surveyBlobRow->QuestionID) && ($surveyBlobRow->QuestionID == 2 || $surveyBlobRow->QuestionID == 154)) {
                            if (isset($surveyBlobRow->SelectedAnswers[0])) {
                                $ansId = $surveyBlobRow->SelectedAnswers[0]->AnswerID;
                                $ansVal = $surveyBlobRow->SelectedAnswers[0]->AnswerValue ?? '';

                                if ($ansId == 6 || str_contains(strtolower($ansVal), '0-3') || str_contains(strtolower($ansVal), 'less than 3')) {
                                    $intentToLearnAnswers['LessThanThreeMonths']++;
                                } else if ($ansId == 7 || str_contains(strtolower($ansVal), '3-6') || str_contains(strtolower($ansVal), '6-12') || str_contains(strtolower($ansVal), '3 months to')) {
                                    $intentToLearnAnswers['ThreeToTwelveMonths']++;
                                } else if ($ansId == 8 || str_contains(strtolower($ansVal), '1 year') || str_contains(strtolower($ansVal), 'more than a year')) {
                                    $intentToLearnAnswers['MoreThanOneYear']++;
                                } else if ($ansId == 9 || str_contains(strtolower($ansVal), 'not sure')) {
                                    $intentToLearnAnswers['NotSure']++;
                                    $intentToLearnAnswers['OverallNotSure']++;
                                } else if ($ansId == 4 || str_contains(strtolower($ansVal), 'none')) {
                                    $intentToLearnAnswers['NotInterested']++;
                                }
                            }
                        }
                    }
                }
            }
        }

        $averageAgeFemale = $totalFemaleLeads ? floor($totalAgeFemale / $totalFemaleLeads) : 0;
        $averageAgeMale = $totalMaleLeads ? floor($totalAgeMale / $totalMaleLeads) : 0;
        $intentToLearnAnswers['TotalInterested'] = $intentToLearnAnswers['LessThanThreeMonths'] + $intentToLearnAnswers['ThreeToTwelveMonths'] + $intentToLearnAnswers['MoreThanOneYear'] + $intentToLearnAnswers['NotSure'];

        return [
            'Message'                       => 'Success',
            'LeadsWithoutMotorcycleLicense' => $nl_result,
            'LeadsMotorcycleLicense'        => $l_result,
            'MaleLeads'                     => $totalMaleLeads,
            'FemaleLeads'                   => $totalFemaleLeads,
            'AverageAgeFemale'              => $averageAgeFemale,
            'AverageAgeMale'                => $averageAgeMale,
            'UnderThirty'                   => $underThirty,
            'BetweenThirtyFourty'           => $betweenThirtyForty,
            'BetweenFourtyFifty'            => $betweenFourtyFifty,
            'AboveFifty'                    => $aboveFifty,
            'IntendToPurchaseGraph'         => $answerCountArray,
            'IntendToLearnGraph'            => $intentToLearnAnswers,
            'TerminalsUsed'                 => '3',
            'PreviouslyOwnedGraph'          => $previouslyOwnedResponses,
            'CurrentlyOwnedGraph'           => $currentlyOwnedResponses,
            'NeverOwnedBike'                => $totalNeverOwned,
            'possibleAnswerNo'              => $possibleAnswerNo,
            'possibleAnswerYesVeteran'      => $possibleAnswerYesVeteran,
            'possibleAnswerYesActive'       => $possibleAnswerYesActive,
            'possibleAnswerTotal'           => $possibleAnswerTotal,
        ];
    }

    private function getReport2002Data(int $eventId): array
    {
        $event = DB::table('events')
            ->where('eventid', $eventId)
            ->first();

        if (!$event) {
            return [];
        }

        $custIds = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('custid', '>', 0)
            ->distinct()
            ->pluck('custid');

        $surveysData = DB::table('surveydata')
            ->whereIn('custid', $custIds)
            ->get();

        $answerCounts = [];
        foreach ($surveysData as $row) {
            if (!empty($row->surveydatablob)) {
                $blob = json_decode($row->surveydatablob, true);
                if (is_array($blob)) {
                    foreach ($blob as $q) {
                        if (isset($q['SelectedAnswers']) && is_array($q['SelectedAnswers'])) {
                            foreach ($q['SelectedAnswers'] as $ans) {
                                if (isset($ans['AnswerID'])) {
                                    $ansId = $ans['AnswerID'];
                                    if (!isset($answerCounts[$ansId])) {
                                        $answerCounts[$ansId] = 0;
                                    }
                                    $answerCounts[$ansId]++;
                                }
                            }
                        }
                    }
                }
            }
        }

        $surveyIds = array_filter([
            $event->postridesurvey,
            $event->jumpstartsurvey,
            $event->demosurvey,
            $event->leadgensurvey
        ]);

        $surveys = [];
        if (!empty($surveyIds)) {
            $surveyRecords = DB::table('surveys')
                ->whereIn('surveyid', $surveyIds)
                ->get();

            foreach ($surveyRecords as $sRecord) {
                $surveyBlob = json_decode($sRecord->surveyblob, true);
                $questions_answer = [];

                if (isset($surveyBlob['SurveyData']) && is_array($surveyBlob['SurveyData'])) {
                    foreach ($surveyBlob['SurveyData'] as $q) {
                        $qId = $q['QuestionID'] ?? '';
                        $questions_answer['Question'][$qId]['name'] = $q['QuestionText'][0]['LanguageText'] ?? '';

                        if (isset($q['Answers']) && is_array($q['Answers'])) {
                            foreach ($q['Answers'] as $ans) {
                                $ansId = $ans['AnswerID'] ?? null;
                                if (is_array($ansId)) {
                                    $ansId = $ansId[0] ?? null;
                                }
                                $countVal = $ansId ? ($answerCounts[$ansId] ?? 0) : 0;
                                $questions_answer['Question'][$qId]['answers'][$ansId] = [
                                    'name'  => $ans['AnswerText'][0]['LanguageText'] ?? '',
                                    'count' => $countVal,
                                ];
                            }
                        }
                    }
                }

                $surveys[$sRecord->surveyid] = [
                    'name'      => $sRecord->surveyname,
                    'questions' => $questions_answer,
                ];
            }
        }

        // Convert the surveys data recursively to objects to match blade's stdClass expectation
        $surveysObj = json_decode(json_encode($surveys), false);

        $custTrans = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('transtype', 39)
            ->get();

        $custTransFormatted = [];
        foreach ($custTrans as $tran) {
            $decoded = [];
            if (!empty($tran->transdescriptionblob)) {
                $decoded = json_decode($tran->transdescriptionblob, true) ?: [];
            }
            $custTransFormatted[] = [
                'transid'              => $tran->transid,
                'custid'               => $tran->custid,
                'eventid'              => $tran->eventid,
                'transtype'            => $tran->transtype,
                'transdate'            => $tran->transdate,
                'transdescriptionblob' => $tran->transdescriptionblob,
                'ModelName'            => $decoded['Model'] ?? $decoded['ModelName'] ?? '',
            ];
        }

        return [
            'dynamic_survey_data' => $surveysObj,
            'Event'               => (array)$event,
            'Survey'              => reset($surveys) ?: null,
            'CustTrans'           => $custTransFormatted,
        ];
    }

    private function getDemoGraphReportsData(int $eventId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('transtype', 4);

        if ($startDate && $endDate) {
            $formattedStart = date('Y-m-d H:i:s', strtotime($startDate));
            $formattedEnd   = date('Y-m-d H:i:s', strtotime($endDate));
            $formattedEnd   = str_replace("00:00:00", "23:59:59", $formattedEnd);
            $formattedEnd   = str_replace(":00", ":59", $formattedEnd);

            $query->whereBetween('transdate', [$formattedStart, $formattedEnd]);
        } else {
            $date = date("Y-m-d");
            $query->whereRaw('DATE(transdate) > ?', [$date]);
        }

        $demoRegs = $query->get();

        $groupsCount = [];
        foreach ($demoRegs as $reg) {
            if (empty($reg->transdescriptionblob)) {
                continue;
            }
            $details = json_decode($reg->transdescriptionblob, true);
            if (empty($details) || !isset($details['DemoRide'])) {
                continue;
            }
            $vin = $details['DemoRide'];

            $vehicle = DB::table('vehicles')
                ->where('vehiclevin', $vin)
                ->first();
            if ($vehicle && $vehicle->groupid) {
                $group = DB::table('vehiclegroups')
                    ->where('groupid', $vehicle->groupid)
                    ->first();
                if ($group && $group->groupname) {
                    $groupname = $group->groupname;
                    if (isset($groupsCount[$groupname])) {
                        $groupsCount[$groupname]['count']++;
                    } else {
                        $groupsCount[$groupname] = ['count' => 1];
                    }
                }
            }
        }
        return $groupsCount;
    }

    private function getNHRAReportData(int $eventId, ?string $startDate = null, ?string $endDate = null): array
    {
        if (empty($startDate) && empty($endDate)) {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        }

        $formattedStart = date('Y-m-d', strtotime($startDate));
        $formattedEnd   = date('Y-m-d', strtotime($endDate));

        $selectJumpstart = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->whereIn('transtype', [12, 2])
            ->where('custid', '>', 0)
            ->whereRaw('DATE(transdate) BETWEEN ? AND ?', [$formattedStart, $formattedEnd])
            ->count();

        $selectNHRA = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->whereIn('transtype', [10])
            ->where('custid', '>', 0)
            ->whereRaw('DATE(transdate) BETWEEN ? AND ?', [$formattedStart, $formattedEnd])
            ->count();

        $selectKids = DB::table('customertrans')
            ->leftJoin('customers', 'customertrans.custid', '=', 'customers.custid')
            ->where('customertrans.eventid', $eventId)
            ->where('customertrans.transtype', 10)
            ->where('customertrans.custid', '>', 0)
            ->whereRaw('DATE(customertrans.transdate) BETWEEN ? AND ?', [$formattedStart, $formattedEnd])
            ->select('customers.custbirthday')
            ->get();

        $totalKids = 0;
        foreach ($selectKids as $kid) {
            if ($kid->custbirthday) {
                $born = strtotime($kid->custbirthday);
                $age = floor((time() - $born) / 31556926);
                if ($age < 18) {
                    $totalKids++;
                }
            }
        }

        $selectPhotoApp = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('transtype', 17)
            ->where('custid', '>', 0)
            ->whereRaw('DATE(transdate) BETWEEN ? AND ?', [$formattedStart, $formattedEnd])
            ->count();

        $selectPhotoLead = DB::table('customertrans')
            ->where('eventid', $eventId)
            ->where('transtype', 17)
            ->where('custid', '>', 0)
            ->whereRaw('DATE(transdate) BETWEEN ? AND ?', [$formattedStart, $formattedEnd])
            ->distinct('custid')
            ->count('custid');

        return [
            'Message'   => 'Success',
            'Jumpstart' => $selectJumpstart,
            'NHRACount' => $selectNHRA,
            'PhotoApp'  => $selectPhotoApp,
            'PhotoLead' => $selectPhotoLead,
            'totalKids' => $totalKids
        ];
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

        $report1Raw = $this->getReport108Data($eventId);
        $report2Raw = $this->getReport109Data($eventId);
        $report3Raw = $this->getReport110Data($eventId);
        $reportData2Raw = $this->getReport2002Data($eventId);

        // Build GraphTrans (Demo rides by model) from CustTrans
        $graphTrans = [];
        if (!empty($reportData2Raw['CustTrans'])) {
            foreach ($reportData2Raw['CustTrans'] as $row) {
                $model = $row['ModelName'] ?? null;
                if ($model) {
                    $graphTrans[$model][] = $model;
                }
            }
        }

        $shareLink = url('/ReportingDashboardView/' . base64_encode($eventId));

        return view('backend.events.report', [
            'title'        => 'Event Report – ' . $event->eventname,
            'event'        => $event,
            'report1'      => $report1Raw,
            'report2'      => $report2Raw,
            'report3'      => $report3Raw,
            'reportData2'  => (object)$reportData2Raw,
            'graphTrans'   => $graphTrans,
            'shareLink'    => $shareLink,
            'encodedId'    => $encodedId,
        ]);
    }

    /**
     * AJAX: Demo Reports with date range.
     */
    public function demoReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $data = $this->getReport106Data($eventId, $request->input('startDate'), $request->input('endDate'));
        return response()->json($data);
    }

    /**
     * AJAX: Demo Reports 2 (Market Segments) with date range.
     */
    public function demoReports2(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $data = $this->getReport109Data($eventId, $request->input('startDate'), $request->input('endDate'));
        return response()->json($data);
    }

    /**
     * AJAX: Stats (report3) with date range.
     */
    public function statsReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $data = $this->getReport110Data($eventId, $request->input('startDate'), $request->input('endDate'));
        return response()->json($data);
    }

    /**
     * AJAX: Demo Rides by Group.
     */
    public function demoGraphReports(Request $request, string $encodedId)
    {
        $eventId = $this->decodeEventId($encodedId);
        if (!$eventId) {
            return response()->json(['error' => 'Invalid Event ID'], 400);
        }

        $data = $this->getDemoGraphReportsData($eventId, $request->input('startDate'), $request->input('endDate'));
        return response()->json($data);
    }

    /**
     * Helper to decode encoded or encrypted Event ID.
     */
    private function decodeEventId(string $encodedId): ?int
    {
        $decoded = base64_decode($encodedId, true);
        if ($decoded !== false && is_numeric($decoded)) {
            return (int)$decoded;
        }

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

        $event = DB::table('events')->where('eventid', $eventId)->first();
        if (!$event) {
            abort(404, 'Event not found.');
        }

        $report1Raw = $this->getReport108Data($eventId);
        $report2Raw = $this->getReport109Data($eventId);
        $report3Raw = $this->getReport110Data($eventId);
        $reportData2Raw = $this->getReport2002Data($eventId);

        $graphTrans = [];
        if (!empty($reportData2Raw['CustTrans'])) {
            foreach ($reportData2Raw['CustTrans'] as $row) {
                $model = $row['ModelName'] ?? null;
                if ($model) {
                    $graphTrans[$model][] = $model;
                }
            }
        }

        return view('backend.events.public-report', [
            'title'        => 'Event Report – ' . $event->eventname,
            'event'        => $event,
            'report1'      => $report1Raw,
            'report2'      => $report2Raw,
            'report3'      => $report3Raw,
            'reportData2'  => (object)$reportData2Raw,
            'graphTrans'   => $graphTrans,
            'encodedId'    => $encodedId,
        ]);
    }

    /**
     * AJAX public demo reports.
     */
    public function publicDemoReports(Request $request, string $encodedId)
    {
        return $this->demoReports($request, $encodedId);
    }

    /**
     * AJAX public demo reports (second set) for Active Military, Market Segments, etc.
     */
    public function publicDemoReports2(Request $request, string $encodedId)
    {
        return $this->demoReports2($request, $encodedId);
    }

    /**
     * AJAX public demo graph reports for Demo Rides by Group.
     */
    public function publicDemoGraphReports(Request $request, string $encodedId)
    {
        return $this->demoGraphReports($request, $encodedId);
    }

    /**
     * AJAX public stats reports.
     */
    public function publicStatsReports(Request $request, string $encodedId)
    {
        return $this->statsReports($request, $encodedId);
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

        $data = $this->getNHRAReportData($eventId, $request->input('startDate'), $request->input('endDate'));
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
