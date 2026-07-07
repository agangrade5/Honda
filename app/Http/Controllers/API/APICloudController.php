<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\UserService;
use App\Services\GlobalFunctionsService;

class APICloudController extends Controller
{
    protected $userService;
    protected $globalFunctions;

    public function __construct(UserService $userService, GlobalFunctionsService $globalFunctions)
    {
        $this->userService = $userService;
        $this->globalFunctions = $globalFunctions;
    }

    public function handle(Request $request)
    {
        // Force JSON response header
        header('Content-Type: application/json');

        $json_input = $request->getContent();

        if (strlen($json_input) == 0) {
            echo json_encode(['Message' => 'Error: No posted data provided.']);
            exit;
        }

        $decodedjson = json_decode($json_input);
        if (!$decodedjson) {
            echo json_encode(['Message' => 'Error: Invalid JSON data.']);
            exit;
        }

        $clientID = null;
        $baseURL = 'honda.kickstartuser.com/API';
        $isCloud = true;
        $proxyCourseTime = 20;
        $trikeCourseTime = 28;

        if ($isCloud == false) {
            $getProxyEventID = DB::table('proxyeventconfig')->where('configid', 1)->value('eventid');
            $proxyEventID = $getProxyEventID;
        } else {
            $proxyEventID = $this->globalFunctions->connectionDataParser($decodedjson, 'EventID');
        }

        $providedAPIKey = $this->globalFunctions->connectionDataParser($decodedjson, 'APIKey');
        $authenticationArray = $this->globalFunctions->authenticateAPIKey($providedAPIKey);

        if ($authenticationArray['Authorized'] == false) {
            return response()->json(['Message' => 'Unauthorized']);
        }

        $clientID = $authenticationArray['ClientID'];
        $terminalID = $this->globalFunctions->connectionDataParser($decodedjson, 'TerminalID');
        $apiKey = $this->globalFunctions->connectionDataParser($decodedjson, 'APIKey');
        $appMode = $this->globalFunctions->connectionDataParser($decodedjson, 'AppMode');
        $clientTime = $this->globalFunctions->connectionDataParser($decodedjson, 'ClientTime');
        $apiMethod = $this->globalFunctions->connectionDataParser($decodedjson, 'Method');
        $fullData = json_encode($decodedjson);

        $this->globalFunctions->createNewSystemLog($fullData);

        if (!in_array($apiMethod, ['100', '153', '150', '151', '140', '149', '147', '146', '144', '142', '141', '99', '134', '118', '130', '105', '110', '111', '112', '113', '114', '116', '129', '109', '127', '128', '126']) && $apiMethod < 900 && $isCloud == false) {
            $this->globalFunctions->createShadowLog($fullData);
        }

        // Execute the switch statement
        switch ($apiMethod) {


    case 99: // Get current eventID
        if ($appMode == '35') {
            $proxyEventID = DB::table('proxyeventconfig')->where('configid', 1)->value('eventid');
            $getSurveyID = DB::table('hogevents')->where('hogeventid', $proxyEventID)->value('eventsurveyid');
            $getSurveyBlob = DB::table('hogsurveys')->where('hogsurveyid', $getSurveyID)->value('surveyblob');

            $responseArray = [
                'Message' => 'Success',
                'EventID' => $proxyEventID,
                'SurveyID' => $getSurveyID,
                'SurveyBlob' => json_decode($getSurveyBlob)
            ];
            return response()->json($responseArray);
        } else {
            $proxyEventID = DB::table('proxyeventconfig')->where('configid', 1)->value('eventid');

            $responseArray = [
                'Message' => 'Success',
                'EventID' => $proxyEventID,
            ];
            return response()->json($responseArray);
        }
        break;

        //Get All Bikes 
        case 153:
            $getEventDetails = DB::table('events')->where('eventid', $proxyEventID)->first();
            
            $trucksBlob = $getEventDetails && $getEventDetails->eventstrucksblob 
                ? @unserialize($getEventDetails->eventstrucksblob) : [];
            
            $getTruckDetails = [];
            if (!empty($trucksBlob)) {
                $getTruckDetails = DB::table('trucks')->whereIn('truckid', (array)$trucksBlob)->get();
            }

            $btsetid = array();
            $btsetidwithtruckid = array();
            foreach($getTruckDetails as $truckrow){
                $btsetid[] = $truckrow->bt_setid;
                $btsetidwithtruckid[$truckrow->bt_setid] = $truckrow->truckid;
            }

            $resultArray = [];
            if (!empty($btsetid)) {
                $resultArray = DB::table('btmodels')->whereIn('bt_setid', $btsetid)->get();
            }

            $responseArray = array('Message' => 'Success');
            $j = 0;
            foreach($resultArray as $row){
                $responseArray['Bikes'][$j]['BikeID'] = $row->bt_modelid;
                $responseArray['Bikes'][$j]['BikeModel'] = $row->bt_modelname;
                $responseArray['Bikes'][$j]['TruckID'] = $btsetidwithtruckid[$row->bt_setid] ?? null;
                $responseArray['Bikes'][$j]['Quantity'] = $row->bt_qty;
                
                if (strlen($row->bt_position) == 1) {
                    $responseArray['Bikes'][$j]['position'] = "0" . $row->bt_position;
                } else {
                    $responseArray['Bikes'][$j]['position'] = (string)$row->bt_position;
                }
                $responseArray['Bikes'][$j]['Times'] = json_decode($row->bt_times);

                //Get BTQ data
                $getBTQueueDetails = DB::table('btqueue as btq')
                    ->leftJoin('customers as c', 'btq.btq_cardnumber', '=', 'c.cardnumber')
                    ->select('btq.*', 'c.custfname', 'c.custid', 'c.custlname', 'c.cardnumber', 'c.custphone')
                    ->where('btq.btq_btmodelid', $row->bt_modelid)
                    ->get();
                
                foreach($getBTQueueDetails as $btqkey => $btqrow){
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['Time'] = $btqrow->btq_time;
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['QueueID'] = $btqrow->btq_id;
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['Status'] = $btqrow->btq_status;
                    
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['CustomerData']['CardNumber'] = $btqrow->cardnumber;
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['CustomerData']['FirstName'] = $btqrow->custfname;
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['CustomerData']['LastName'] = $btqrow->custlname;
                    
                    $photoEvalURL = DB::table('regphotos')->where('custid', $btqrow->custid)->value('photolocation');
                    
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['CustomerData']['PhotoURL'] = $photoEvalURL ?: '';
                    $responseArray['Bikes'][$j]['BookedTimes'][$btqkey]['CustomerData']['PhoneNumber'] = $btqrow->custphone;
                }
                $j++;
            }
            return response()->json($responseArray);
            break;

        //Book Bike Request
        case 154:
            $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
            $BikeID = $this->globalFunctions->dataParser($decodedjson, 'BikeID');
            $Time = $this->globalFunctions->dataParser($decodedjson, 'Time');

            $getModelQty = DB::table('btmodels')->where('bt_modelid', $BikeID)->first();
            $bikeQuantity = $getModelQty ? $getModelQty->bt_qty : 0;

            $arrayCount = DB::table('btqueue')->where('btq_btmodelid', $BikeID)->where('btq_time', $Time)->count();

            if ($arrayCount >= $bikeQuantity) {
                return response()->json(['Message' => 'Failed - Already booked.']);
            } else {
                DB::table('btqueue')->insert([
                    'btq_cardnumber' => $CardNumber,
                    'btq_btmodelid' => $BikeID,
                    'btq_time' => $Time,
                    'btq_eventid' => $proxyEventID,  
                    'btq_status' => 0                  
                ]);
                return response()->json(['Message' => 'Success']);
            }
        break;

        //Cancel Booking
        case 155:
            $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
            $BikeID = $this->globalFunctions->dataParser($decodedjson, 'BikeID');
            $Time = $this->globalFunctions->dataParser($decodedjson, 'Time');            
            
            DB::table('btqueue')
                ->where('btq_cardnumber', $CardNumber)
                ->where('btq_btmodelid', $BikeID)
                ->where('btq_time', $Time)
                ->where('btq_eventid', $proxyEventID)
                ->delete();
                
            return response()->json(['Message' => 'Success']);
        break;

        //Check-In
        case 156:
            $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
            $CustomerID = DB::table('customers')->where('cardnumber', $CardNumber)->value('custid');
            $BikeID = $this->globalFunctions->dataParser($decodedjson, 'BikeID');
            $BikeDetails = DB::table('btmodels')->where('bt_modelid', $BikeID)->value('bt_modelname');
            $Time = $this->globalFunctions->dataParser($decodedjson, 'Time');            
            
            DB::table('btqueue')
                ->where('btq_cardnumber', $CardNumber)
                ->where('btq_btmodelid', $BikeID)
                ->where('btq_time', $Time)
                ->where('btq_eventid', $proxyEventID)
                ->update(['btq_status' => 1]);
                        
            DB::table('customertrans')->insert([
                'eventid' => $proxyEventID,
                'transtype' => $appMode,
                'custid' => $CustomerID,
                'transdate' => $clientTime,
                'servertime' => date("Y-m-d H:i:s"),
                'terminalid' => $terminalID,
                'transdescriptionblob' => json_encode(['Model' => $BikeDetails])
            ]);
                        
            return response()->json(['Message' => 'Success']);
        break;
        

    case 1000: //Event check
        $getEventDetails = DB::table('events')->where('eventid', $proxyEventID)->get();        
        return response()->json($getEventDetails);
        break;

    case 100: //Event Configuration


        //Get the currently downloaded event config number
        //The Proxy setup software will set this row of config to the event id it needs to be configured for.
        //After the setup processes, it will download all data required to run that event.

        //ToDo: Remove after implementation of multi-cloud event

        $getEventDetails = $database->select('events', '*', array('eventid' => $proxyEventID));
        $getpreregisterHTMLDetails = $database->select('preregisterHTML', '*', array('eventid' => $proxyEventID));

        $responseArray = array();
        $responseArray[0]['EventID'] = $proxyEventID;
        if(isset($getpreregisterHTMLDetails) && !empty($getpreregisterHTMLDetails)){
            $responseArray[0]['preregisterHTML'] = $getpreregisterHTMLDetails;
        }
        else{
            $getpreregisterHTMLDetails = $database->select('preregisterHTML', '*', array('eventid' => 'system'));
            $responseArray[0]['preregisterHTML'] = $getpreregisterHTMLDetails;
        }
        $responseArray[0]['CourseTime'] = $proxyCourseTime;

        $responseArray[0]['TrikeCourseTime'] = $proxyCourseTime + $getEventDetails[0]['trikewait'];

        $trikeCourseTime = $proxyCourseTime + $getEventDetails[0]['trikewait'];


        $responseArray[0]['EventName'] = $getEventDetails[0]['eventname'];

        if (!is_null($getEventDetails[0]['eventjumpstartwaiver'])) {

            $getWaiverJumpStartQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventjumpstartwaiver']));

            $responseArray[0]['JumpStartWaiver'] = array(
                'WaiverID' => $getWaiverJumpStartQuery[0]['legalid'],
                'WaiverHTML' => $getWaiverJumpStartQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventpassengerwaiver2'])) {

            $getWaiverPassenger2 = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventpassengerwaiver2']));

            $responseArray[0]['DemoPassengerWaiver2'] = array(
                'WaiverID' => $getWaiverPassenger2[0]['legalid'],
                'WaiverHTML' => $getWaiverPassenger2[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventdemowaiver2'])) {

            $getWaiverDemo2 = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventdemowaiver2']));

            $responseArray[0]['DemoWaiver2'] = array(
                'WaiverID' => $getWaiverDemo2[0]['legalid'],
                'WaiverHTML' => $getWaiverDemo2[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventjumpstartwaiverunderage'])) {

            $getWaiverJumpStartUnderageQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventjumpstartwaiverunderage']));

            $responseArray[0]['JumpStartWaiverUnderage'] = array(
                'WaiverID' => $getWaiverJumpStartUnderageQuery[0]['legalid'],
                'WaiverHTML' => $getWaiverJumpStartUnderageQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventdemowaiver'])) {

            $getWaiverDemoQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventdemowaiver']));

            $responseArray[0]['DemoWaiver'] = array(
                'WaiverID' => $getWaiverDemoQuery[0]['legalid'],
                'WaiverHTML' => $getWaiverDemoQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['trikewaiver'])) {

            $getWaiverTrikeQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['trikewaiver']));

            $responseArray[0]['TrikeWaiver'] = array(
                'WaiverID' => $getWaiverTrikeQuery[0]['legalid'],
                'WaiverHTML' => $getWaiverTrikeQuery[0]['legalhtml']
            );

        }


        if (!is_null($getEventDetails[0]['demopassengerwaiver'])) {

            $getWaiverPassengerQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['demopassengerwaiver']));

            $responseArray[0]['DemoPassengerWaiver'] = array(
                'WaiverID' => $getWaiverPassengerQuery[0]['legalid'],
                'WaiverHTML' => $getWaiverPassengerQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['trikepassengerwaiver'])) {

            $getTrikePassengerQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['trikepassengerwaiver']));

            $responseArray[0]['TrikePassengerWaiver'] = array(
                'WaiverID' => $getTrikePassengerQuery[0]['legalid'],
                'WaiverHTML' => $getTrikePassengerQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventlivewirelg'])) {

            $getTrikePassengerQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventlivewirelg']));

            $responseArray[0]['LiveWireLeadGen'] = array(
                'WaiverID' => $getTrikePassengerQuery[0]['legalid'],
                'WaiverHTML' => $getTrikePassengerQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventlivewirejs'])) {

            $getTrikePassengerQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventlivewirejs']));

            $responseArray[0]['LiveWireJumpstart'] = array(
                'WaiverID' => $getTrikePassengerQuery[0]['legalid'],
                'WaiverHTML' => $getTrikePassengerQuery[0]['legalhtml']
            );

        }

        if (!is_null($getEventDetails[0]['eventlivewirejsunderage'])) {

            $getTrikePassengerQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventlivewirejsunderage']));

            $responseArray[0]['LiveWireJumpstartUnderage'] = array(
                'WaiverID' => $getTrikePassengerQuery[0]['legalid'],
                'WaiverHTML' => $getTrikePassengerQuery[0]['legalhtml']
            );

        }
        
         if (!is_null($getEventDetails[0]['eventleadgenwaiver'])) {

            $getLeadGenWaiverQuery = $database->select('legal', '*', array('legalid' => $getEventDetails[0]['eventleadgenwaiver']));

            $responseArray[0]['LeadGenWaiver'] = array(
                'WaiverID' => $getLeadGenWaiverQuery[0]['legalid'],
                'WaiverHTML' => $getLeadGenWaiverQuery[0]['legalhtml']
            );

        }




        $leadGenSurveyID = $getEventDetails[0]['leadgensurvey'];
        $demoSurvey = $getEventDetails[0]['demosurvey'];
        $postRideSurvey = $getEventDetails[0]['postridesurvey'];
        $jumpstartSurvey = $getEventDetails[0]['jumpstartsurvey'];


        //Add Survey Responses

        $getLeadGenSurvey = $database->select('surveys', 'surveyblob', array('surveyid' => $leadGenSurveyID));

        $getDemoSurvey = $database->select('surveys', 'surveyblob', array('surveyid' => $demoSurvey));

        $postRideSurvey = $database->select('surveys', 'surveyblob', array('surveyid' => $postRideSurvey));

        $jumpstartSurvey = $database->select('surveys', 'surveyblob', array('surveyid' => $jumpstartSurvey));
        
        $json_flag = false;
        $tmpDealers = new stdClass();
        $tmpJsonObject = new stdClass();

        $eventDealers = array(); 
        if(isset($getEventDetails[0]['eventdealers']) && !empty($getEventDetails[0]['eventdealers'])){
            $eventDealers = @unserialize($getEventDetails[0]['eventdealers']);        
            if($eventDealers!==FALSE && count($eventDealers)>1){ 
                $json_flag = true;
                $tmpJsonObject->QuestionID = 9999999;
                $tmpJsonObject->Required = 'YES';

                $tmpQuestionTxt = new stdClass();
                $tmpQuestionTxt->Language = 100;
                $tmpQuestionTxt->LanguageText = 'Choose your preferred dealer';
                $tmpJsonObject->QuestionText[0] = $tmpQuestionTxt; /*new stdClass()->Language = 100,
                                                                ->LanguageText = 'Choose your preferred dealer';*/

                
                $tmpDealers = $database->select('dealers', 'dealername', array('dealerid' => $eventDealers));
                $answerIDCount = 9001;
                foreach($tmpDealers as $tindex => $tmpDealer){
                    $tmpJsonObject->Answers[$tindex]->AnswerID = $answerIDCount;
                    $tmpJsonObject->Answers[$tindex]->Required = 'NO';

                    //Manage json for answer text.
                    $tmpAnswerTxt = new stdClass();
                    $tmpAnswerTxt->Language = 100;
                    $tmpAnswerTxt->LanguageText = $tmpDealer;
                    $tmpJsonObject->Answers[$tindex]->AnswerText[0] = $tmpAnswerTxt;

                    $tmpJsonObject->Answers[$tindex]->AnswerType = 1;
                    $tmpJsonObject->Answers[$tindex]->MailedFlag = 0;
                    $answerIDCount++;

                }
                //print_r($tmpJsonObject);
            }
        }
        

        $tmpJsonArray = (array)$tmpJsonObject;



        if (!is_null($jumpstartSurvey[0])) {
            normalizeAnswerIds($jumpstartSurvey[0]);
            $responseArray[0]['DynoSurvey'] = $jumpstartSurvey[0];

        } else {
            $responseArray[0]['DynoSurvey'] = null;
        }

        if (!is_null($getLeadGenSurvey[0])) {
            $tmpLeadGenSurvey = $getLeadGenSurvey[0];
            if($json_flag){
                $tmpLeadGenSurveyArray = json_decode($tmpLeadGenSurvey);
                
                if(!empty($tmpJsonArray)){
                    $tmpLeadGenSurveyArray->SurveyData[] = $tmpJsonObject;
                }
                normalizeAnswerIds($tmpLeadGenSurveyArray);
                $tmpLeadGenSurvey = json_encode($tmpLeadGenSurveyArray);
            }
            $responseArray[0]['LeadGenSurvey'] = $tmpLeadGenSurvey;

        } else {
            $responseArray[0]['LeadGenSurvey'] = null;
        }

        if (!is_null($getDemoSurvey[0])) {
            $tmpDemoSurvey = $getDemoSurvey[0];
            if($json_flag){ 
                $tmpDemoSurveyArray = json_decode($tmpDemoSurvey);
                
                if(!empty($tmpJsonArray)){ //print_r($tmpJsonObject);die;
                    $tmpDemoSurveyArray->SurveyData[] = $tmpJsonObject;
                }
                normalizeAnswerIds($tmpDemoSurveyArray);
                $tmpDemoSurvey = json_encode($tmpDemoSurveyArray);
            }
            $responseArray[0]['DemoSurvey'] = $tmpDemoSurvey;
        } else {
            $responseArray[0]['DemoSurvey'] = null;
        }

        if (!is_null($postRideSurvey[0])) {
            if(intval($AppMode)==55){
                normalizeAnswerIds($postRideSurvey[0]);
                $responseArray[0]['LeadGenSurvey'] = $postRideSurvey[0];
            }
            else{
                normalizeAnswerIds($postRideSurvey[0]);
                $responseArray[0]['PostRideSurvey'] = $postRideSurvey[0];
            }

        } else {

            $responseArray[0]['PostRideSurvey'] = null;

        }
        
       //print_r($responseArray[0]['DemoSurvey']);die;
        $responseArray[0]['BikesAndTimesFlag'] = 0;//$getEventDetails[0]['eventbikesandtimes'];


        //---------------------------------Ram----23 march--------------------------------------------------
        //--------------------------surveys of new registration events--------------------------------------------------------
        $registrationsurveyid = isset($getEventDetails[0]['registrationsurveyid'])?$getEventDetails[0]['registrationsurveyid']:-1;
        $responseArray[0]['registrationsurveyid'] = ($registrationsurveyid == -1)?NULL:$registrationsurveyid;

        $surveySql  = 'SELECT `surveyblob` FROM `surveys` WHERE surveyid='.$registrationsurveyid;
        $surveyData = $database->query($surveySql)->fetchAll(PDO::FETCH_ASSOC); 
        $surveyData = $surveyData[0]['surveyblob'];
        //Put array
        normalizeAnswerIds($surveyData);
        $responseArray[0]['RegistrationSurvey'] = $surveyData;
        //----------------------------------------------XXXXXXXXXXX--------------------------------------------




        echo json_encode($responseArray);


        break;

    case 101: //Submit Customer Profile
        $dataArray = isset($decodedjson->Data) ? (array)$decodedjson->Data : [];
        $response = $this->userService->submitCustomerProfile(
            $dataArray,
            $proxyEventID,
            $clientID,
            $isCloud
        );
        return response()->json($response);
        break;

    case 102: //Submit Survey Data
        $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $CustomerID = DB::table('customers')->where('cardnumber', $CardNumber)->value('custid');
        $SurveyID = $this->globalFunctions->dataParser($decodedjson, 'SurveyID');
        $SurveyQuestions = $this->globalFunctions->dataParser($decodedjson, 'SurveyQuestions');
        
        if(isset($SurveyQuestions) && !empty($SurveyQuestions)){
            foreach ($SurveyQuestions as $questionIndex => $question) {
                if (!empty($question->SelectedAnswers)) {
                    foreach ($question->SelectedAnswers as $answerIndex => $answer) {
                        if (isset($answer->AnswerID) && is_array($answer->AnswerID)) {
                            $SurveyQuestions[$questionIndex]
                                ->SelectedAnswers[$answerIndex]
                                ->AnswerID = (string)($answer->AnswerID[0] ?? '');
                        }
                    }
                }
            }
        }
        
        $EventID = $proxyEventID;
        $SurveyCompletedTime = $this->globalFunctions->dataParser($decodedjson, 'SurveyCompletedTime');

        $surveyExistsResponse = DB::table('surveydata')
            ->where('custid', $CustomerID)
            ->where('surveyid', $SurveyID)
            ->first();

        if ($surveyExistsResponse) {
            if (strtotime($surveyExistsResponse->surveydatetime) > strtotime($SurveyCompletedTime)) {
                $writeRecordTrans = DB::table('customertrans')->insert([
                    'eventid' => $EventID,
                    'transtype' => $appMode,
                    'custid' => $CustomerID,
                    'transdate' => $SurveyCompletedTime,
                    'servertime' => date("Y-m-d H:i:s"),
                    'terminalid' => $terminalID
                ]);

                return response()->json([
                    'Message' => 'Success',
                    'RecordWrite' => $writeRecordTrans
                ]);
            }
            
            DB::table('surveydata')
                ->where('surveyid', $SurveyID)
                ->where('custid', $CustomerID)
                ->update([
                    'surveydatablob' => json_encode($SurveyQuestions),
                    'surveydatetime' => $SurveyCompletedTime,
                    'eventid' => $EventID,
                    'servertime' => date("Y-m-d H:i:s")
                ]);
                
            $writeRecordTrans = DB::table('customertrans')->insert([
                'eventid' => $EventID,
                'transtype' => $appMode,
                'custid' => $CustomerID,
                'transdate' => $SurveyCompletedTime,
                'servertime' => date("Y-m-d H:i:s"),
                'terminalid' => $terminalID
            ]);
        } else {
            DB::table('surveydata')->insert([
                'custid' => $CustomerID,
                'surveyid' => $SurveyID,
                'surveydatablob' => json_encode($SurveyQuestions),
                'surveydatetime' => $SurveyCompletedTime,
                'eventid' => $EventID,
                'servertime' => date("Y-m-d H:i:s")
            ]);

            $writeRecordTrans = DB::table('customertrans')->insert([
                'eventid' => $EventID,
                'transtype' => $appMode,
                'custid' => $CustomerID,
                'transdate' => $SurveyCompletedTime,
                'servertime' => date("Y-m-d H:i:s"),
                'terminalid' => $terminalID
            ]);
        }
        
        return response()->json([
            'Message' => 'Success',
            'RecordTrans' => $writeRecordTrans
        ]);
        break;

    case 103: //Login to authenticate Upload
            $Username = $this->globalFunctions->dataParser($decodedjson, 'Username');
            $Password = $this->globalFunctions->dataParser($decodedjson, 'Password');

            $authenticateCredentialsArray = DB::table('users')
                ->where('clientid', $clientID)
                ->where('username', $Username)
                ->where('userpass', $Password)
                ->get();

            if (count($authenticateCredentialsArray) > 0) {
                return response()->json([
                    'Message' => 'Success',
                    'AllowedEvents' => $authenticateCredentialsArray[0]->allowevents
                ]);
            } else {
                return response()->json([
                    'Message' => 'Invalid Login'
                ]);
            }
        break;

    case 105: //Get Customer Info by Card Number
        $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $SurveyID = $this->globalFunctions->dataParser($decodedjson, 'SurveyID');
        $EventID = $proxyEventID;
        
        $findCustomer = DB::table('customers')->where('cardnumber', $CardNumber)->first();

        if ($findCustomer) {
            $surveyCustomerResults = DB::table('surveydata')
                ->where('custid', $findCustomer->custid)
                ->where('surveyid', $SurveyID)
                ->first();

            $blackListStatus = 0;
            $photoEvalURL = DB::table('regphotos')->where('custid', $findCustomer->custid)->value('photolocation') ?: '';

            $blackListQuery = DB::table('restrictedriders')->where('restrictlic', $findCustomer->custmotorcyclelic)->first();
            $waiverStatus = 0;
            $waiverQuery = DB::table('legaldata')->where('custid', $findCustomer->custid)->where('eventid', $proxyEventID)->first();
            $blackListCardNumber = DB::table('restrictedriders')->where('restrictlic', $CardNumber)->first();

            if ($blackListCardNumber || $blackListQuery) {
                $blackListStatus = 1;
            }

            if ($waiverQuery) {
                $waiverStatus = 1;
            }

            $customerData = [
                'CardNumber' => (string)$findCustomer->cardnumber,
                'FirstName' => $findCustomer->custfname,
                'LastName' => $findCustomer->custlname,
                'AddressBlob' => $findCustomer->custaddress,
                'CountryID' => $findCustomer->custcountry,
                'DOB' => $findCustomer->custbirthday,
                'LicExpiration' => $findCustomer->custlicexpire,
                'MotorcycleLic' => $findCustomer->custmotorcyclelic,
                'Gender' => $findCustomer->custgender,
                'Ethnicity' => $findCustomer->custethnicity,
                'PreferredLanguage' => $findCustomer->custlang,
                'Email' => $findCustomer->custemail,
                'Phone' => $findCustomer->custphone,
                'DriversLicense' => $findCustomer->custdriverslicense,
                'OptIn' => $findCustomer->custoptin,
                'CustomerPhoto' => $photoEvalURL,
                'Blacklisted' => $blackListStatus,
                'WaiverSigned' => $waiverStatus
            ];

            if ($surveyCustomerResults) {
                return response()->json([
                    'Message' => 'Success',
                    'CustomerData' => $customerData,
                    'SurveyData' => [
                        'CustomerID' => $findCustomer->custid,
                        'SurveyID' => $surveyCustomerResults->surveyid,
                        'SurveyQuestions' => json_decode($surveyCustomerResults->surveydatablob)
                    ]
                ]);
            } else {
                $customerData['WaiverSigned'] = 0; // matching legacy logic
                return response()->json([
                    'Message' => 'Success',
                    'CustomerData' => $customerData,
                    'SurveyData' => [
                        'CustomerID' => $findCustomer->custid,
                        'SurveyID' => null,
                        'SurveyQuestions' => null
                    ]
                ]);
            }
        } else {
            return response()->json([
                'Message' => 'Success',
                'DataFound' => 'None'
            ]);
        }
        break;

    case 106: //Add to WaitList
        $custID = $this->globalFunctions->dataParser($decodedjson, 'CustID');
        $modelID = $this->globalFunctions->dataParser($decodedjson, 'ModelID');
        $insertWaitListSQL = DB::table('queue')->insert([
            'custid' => $custID,
            'checkintime' => $clientTime,
            'servertime' => date("Y-m-d H:i:s"),
            'modelid' => $modelID,
            'clientid' => $clientID
        ]);

        if ($insertWaitListSQL) {
            return response()->json([
                "Message" => 'Success'
            ]);
        }
        break;

    case 107: //Upload Diagnostic Log
        $customerData = $this->globalFunctions->dataParser($decodedjson, 'CustomerData');
        $surveyData = $this->globalFunctions->dataParser($decodedjson, 'SurveyData');
        $customerRegError = $this->globalFunctions->dataParser($decodedjson, 'CustomerRegError');
        $LogData = $this->globalFunctions->dataParser($decodedjson, 'LogData');

        $body = 'START OF LOG DATA - Terminal ID: ' . $terminalID . '<br />---------<br /><br />---------<br />' . $customerData . '<br />---------<br /><br />---------<br />' . $surveyData . '<br />---------<br /><br />---------<br />' . $customerRegError . '<br />---------<br /><br />---------<br />' . ($LogData[0] ?? '');

        if ($isCloud == true) {
            $this->globalFunctions->serverSendEmail('cdaden@ncompasstrac.com', $body, 'ERROR LOG UPLOAD');
        }

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 108: //Get Waiver


        break;

    case 109: //Get Groups
        $groupsArray = DB::table('vehiclegroups')->where('clientid', $clientID)->get();

        $groupConstructedArray = [];

        foreach ($groupsArray as $x => $group) {
            $modelsArray = DB::table('models')
                ->where('clientid', $clientID)
                ->where('groupid', $group->groupid)
                ->get();

            $modelConstructedArray = [];

            foreach ($modelsArray as $y => $model) {
                $waitListArray = DB::table('queue')
                    ->where('clientid', $clientID)
                    ->where('modelid', $model->modelid)
                    ->where('eventid', $proxyEventID)
                    ->orderBy('checkintime', 'asc')
                    ->get();

                $waitListConstructedArray = [];

                if ($waitListArray->count() > 0) {
                    foreach ($waitListArray as $z => $waitItem) {
                        $customerDataQuery = DB::table('customers')->where('custid', $waitItem->custid)->first();

                        $waitListConstructedArray[$z] = array(
                            'Position' => $z,
                            'FirstName' => $customerDataQuery ? $customerDataQuery->custfname : '',
                            'LastName' => $customerDataQuery ? $customerDataQuery->custlname : '',
                            'CardNumber' => $customerDataQuery ? $customerDataQuery->cardnumber : '',
                            'EstimatedRideTime' => $waitItem->estimatedridetime,
                            'ModelID' => $waitItem->modelid
                        );
                    }
                }

                $waitTimeQuery = DB::table('queue')
                    ->where('modelid', $model->modelid)
                    ->where('eventid', $proxyEventID)
                    ->get();

                $getModelCount = DB::table('vehicles')->where('modelid', $model->modelid)->get();
                $getServiceCount = DB::table('vehicles')->where('modelid', $model->modelid)->where('vehiclestatus', 'SERVICE')->get();
                $getUnavailableCount = DB::table('vehicles')->where('modelid', $model->modelid)->where('vehiclestatus', 'UNAVAILABLE')->get();
                $getOutCount = DB::table('vehicles')->where('modelid', $model->modelid)->where('vehiclestatus', 'OUT')->get();

                $date = date_create(date("Y-m-d H:i:s"));

                $currentProxyCourseTime = $proxyCourseTime;
                if ((substr($model->modelname, 0, 3)) == 'Tri') {
                    $currentProxyCourseTime = $trikeCourseTime;
                }

                $mathServiceCount = $getServiceCount->count();
                $mathModelCount = $getModelCount->count();
                $mathUnavailableCount = $getUnavailableCount->count();
                $mathWaitListCount = $waitTimeQuery->count();
                $mathNetAvailableBikes = $mathModelCount - ($mathServiceCount + $mathUnavailableCount);
                $rideMultiplier = ($mathWaitListCount + $getOutCount->count()) - $mathNetAvailableBikes;
                $rideTimeVariable = $rideMultiplier * $currentProxyCourseTime;

                $rideTime = date("Y-m-d H:i:s");
                if ($rideTimeVariable >= 0) {
                    $getFirstPersonTime = DB::table('queue')
                        ->where('modelid', $model->modelid)
                        ->orderBy('estimatedridetime', 'asc')
                        ->get();

                    $index = $rideMultiplier - $getOutCount->count();
                    if (isset($getFirstPersonTime[$index])) {
                        $date = date_create($getFirstPersonTime[$index]->estimatedridetime);
                    }
                    $date = date_add($date, date_interval_create_from_date_string(($currentProxyCourseTime . ' minutes')));
                    $rideTime = date_format($date, 'Y-m-d H:i:s');
                }

                $modelConstructedArray[$y] = array(
                    'ModelID' => $model->modelid,
                    'ModelName' => $model->modelname,
                    'Waitlist' => $waitListConstructedArray,
                    'EstimatedRideTime' => $rideTime
                );
            }

            $vehiclesArray = DB::table('vehicles')->where('groupid', $group->groupid)->get();
            $vehiclesConstructedArray = [];

            if ($vehiclesArray->count() > 0) {
                foreach ($vehiclesArray as $a => $vehicle) {
                    if ($vehicle->vehiclestatus == 'SERVICE') {
                        $nowTime = time();
                        $vehicleTime = strtotime($vehicle->vehicleduein);
                        $calculateTime = abs($vehicleTime - $nowTime) / 60;

                        $vehiclesConstructedArray[$a] = array(
                            'VehicleID' => $vehicle->vehicleid,
                            'ModelID' => $vehicle->modelid,
                            'GroupID' => $vehicle->groupid,
                            'Nickname' => $vehicle->vehiclenickname,
                            'VehicleColor' => $vehicle->vehiclecolor,
                            'VehicleLicPlate' => $vehicle->vehiclelicplate,
                            'VehicleRealVIN' => $vehicle->vehiclevin,
                            'VehicleVIN' => $vehicle->cov,
                            'VehicleStatus' => $vehicle->vehiclestatus,
                            'Rider' => $vehicle->currentrider,
                            'Passenger' => $vehicle->currentpassenger,
                            'VehicleDue' => round($calculateTime, 1, PHP_ROUND_HALF_UP) . ' min'
                        );
                    } else if ($vehicle->vehiclestatus == 'OUT') {
                        $nowTime = time();
                        $vehicleTime = strtotime($vehicle->vehicleduein);
                        $calculateTime = ($vehicleTime - $nowTime) / 60;

                        if ($calculateTime < 0) {
                            DB::table('vehicles')->where('cov', $vehicle->cov)->update(['vehiclestatus' => 'OVERDUE']);
                            $vehicle->vehiclestatus = 'OVERDUE';
                        }

                        $customerDataQuery = DB::table('customers')->where('cardnumber', $vehicle->currentrider)->first();
                        $photoEvalURL = 'None';
                        if ($customerDataQuery) {
                            $photoCustomerResults = DB::table('regphotos')->where('custid', $customerDataQuery->custid)->value('photolocation');
                            if ($photoCustomerResults) {
                                $photoEvalURL = $photoCustomerResults;
                            }
                        }

                        $vehiclesConstructedArray[$a] = array(
                            'VehicleID' => $vehicle->vehicleid,
                            'ModelID' => $vehicle->modelid,
                            'GroupID' => $vehicle->groupid,
                            'Nickname' => $vehicle->vehiclenickname,
                            'VehicleColor' => $vehicle->vehiclecolor,
                            'VehicleLicPlate' => $vehicle->vehiclelicplate,
                            'VehicleRealVIN' => $vehicle->vehiclevin,
                            'VehicleVIN' => $vehicle->cov,
                            'VehicleStatus' => $vehicle->vehiclestatus,
                            'Rider' => array(
                                'CardNumber' => $vehicle->currentrider,
                                'FirstName' => $customerDataQuery ? $customerDataQuery->custfname : '',
                                'LastName' => $customerDataQuery ? $customerDataQuery->custlname : '',
                                'AddressBlob' => $customerDataQuery ? $customerDataQuery->custaddress : '',
                                'DOB' => $customerDataQuery ? $customerDataQuery->custbirthday : '',
                                'Email' => $customerDataQuery ? $customerDataQuery->custemail : '',
                                'Phone' => $customerDataQuery ? $customerDataQuery->custphone : '',
                                'CustomerPhoto' => $photoEvalURL
                            ),
                            'Passenger' => $vehicle->currentpassenger,
                            'VehicleDue' => round($calculateTime, 1, PHP_ROUND_HALF_UP) . ' min',
                        );
                    } else if ($vehicle->vehiclestatus == 'OVERDUE') {
                        $nowTime = time();
                        $vehicleTime = strtotime($vehicle->vehicleduein);
                        $calculateTime = ($vehicleTime - $nowTime) / 60;

                        $customerDataQuery = DB::table('customers')->where('cardnumber', $vehicle->currentrider)->first();
                        $photoEvalURL = 'None';
                        if ($customerDataQuery) {
                            $photoCustomerResults = DB::table('regphotos')->where('custid', $customerDataQuery->custid)->value('photolocation');
                            if ($photoCustomerResults) {
                                $photoEvalURL = $photoCustomerResults;
                            }
                        }

                        $vehiclesConstructedArray[$a] = array(
                            'VehicleID' => $vehicle->vehicleid,
                            'ModelID' => $vehicle->modelid,
                            'GroupID' => $vehicle->groupid,
                            'Nickname' => $vehicle->vehiclenickname,
                            'VehicleVIN' => $vehicle->cov,
                            'VehicleColor' => $vehicle->vehiclecolor,
                            'VehicleLicPlate' => $vehicle->vehiclelicplate,
                            'VehicleRealVIN' => $vehicle->vehiclevin,
                            'VehicleStatus' => $vehicle->vehiclestatus,
                            'Rider' => array(
                                'CardNumber' => $vehicle->currentrider,
                                'FirstName' => $customerDataQuery ? $customerDataQuery->custfname : '',
                                'LastName' => $customerDataQuery ? $customerDataQuery->custlname : '',
                                'Phone' => $customerDataQuery ? $customerDataQuery->custphone : '',
                                'CustomerPhoto' => $photoEvalURL
                            ),
                            'Passenger' => $vehicle->currentpassenger,
                            'VehicleDue' => round($calculateTime, 1, PHP_ROUND_HALF_UP) . ' min',
                        );
                    } else {
                        $vehiclesConstructedArray[$a] = array(
                            'VehicleID' => $vehicle->vehicleid,
                            'ModelID' => $vehicle->modelid,
                            'GroupID' => $vehicle->groupid,
                            'Nickname' => $vehicle->vehiclenickname,
                            'VehicleVIN' => $vehicle->cov,
                            'VehicleColor' => $vehicle->vehiclecolor,
                            'VehicleLicPlate' => $vehicle->vehiclelicplate,
                            'VehicleRealVIN' => $vehicle->vehiclevin,
                            'VehicleStatus' => $vehicle->vehiclestatus,
                            'Rider' => $vehicle->currentrider,
                            'Passenger' => $vehicle->currentpassenger,
                            'VehicleDue' => $vehicle->vehicleduein
                        );
                    }
                }
            }

            $groupConstructedArray[$x] = array(
                'GroupID' => $group->groupid,
                'GroupName' => $group->groupname,
                'Models' => $modelConstructedArray,
                'Vehicles' => $vehiclesConstructedArray
            );
        }

        return response()->json([
            'Message' => 'Success',
            'Groups' => $groupConstructedArray
        ]);
        break;

    case 110: //Get Bikes by Group ID
        $groupID = $this->globalFunctions->dataParser($decodedjson, 'GroupID');

        $groupQuerySQL = DB::table('vehicles')->where('groupid', $groupID)->get();

        return response()->json([
            'Message' => 'Success',
            'Vehicles' => $groupQuerySQL
        ]);
        break;

    case 111: //Get Customer Data by Card Number
        $getEventDetails = DB::table('events')->where('eventid', $proxyEventID)->first();
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $findCustomerResults = DB::table('customers')->where('cardnumber', $cardNumber)->get();

        if ($findCustomerResults->count() > 0) {
            $customer = $findCustomerResults[0];
            $blackListStatus = 0;
            $photoEvalURL = DB::table('regphotos')->where('custid', $customer->custid)->value('photolocation') ?: '';

            $blackListQuery = DB::table('restrictedriders')->where('restrictlic', $customer->custdriverslicense)->get();
            $blackListCardNumber = DB::table('restrictedriders')->where('restrictlic', $cardNumber)->get();

            if ($blackListCardNumber->count() > 0 || $blackListQuery->count() > 0) {
                $blackListStatus = 1;
            }

            $waiverStatus = 0;
            $TrikeStatus = 0;

            if ($getEventDetails) {
                $waiverQuery = DB::table('legaldata')
                    ->where('custid', $customer->custid)
                    ->where('legalid', $getEventDetails->eventdemowaiver)
                    ->where('eventid', $proxyEventID)
                    ->get();

                $checkPassengerWaiver = DB::table('legaldata')
                    ->where('custid', $customer->custid)
                    ->where('legalid', $getEventDetails->demopassengerwaiver)
                    ->where('eventid', $proxyEventID)
                    ->get();

                $TrikewaiverQuery = DB::table('legaldata')
                    ->where('custid', $customer->custid)
                    ->where('legalid', $getEventDetails->trikewaiver)
                    ->where('eventid', $proxyEventID)
                    ->get();

                if ($waiverQuery->count() > 0 || $checkPassengerWaiver->count() > 0) {
                    $waiverStatus = 1;
                }

                if ($TrikewaiverQuery->count() > 0) {
                    $TrikeStatus = 1;
                }
            }

            return response()->json([
                'Message' => 'Success',
                'CustomerData' => array(
                    'CardNumber' => (string)$customer->cardnumber,
                    'FirstName' => $customer->custfname,
                    'LastName' => $customer->custlname,
                    'AddressBlob' => $customer->custaddress,
                    'CountryID' => $customer->custcountry,
                    'DOB' => $customer->custbirthday,
                    'LicExpiration' => $customer->custlicexpire,
                    'MotorcycleLic' => $customer->custmotorcyclelic,
                    'Gender' => $customer->custgender,
                    'Ethnicity' => $customer->custethnicity,
                    'PreferredLanguage' => $customer->custlang,
                    'Email' => $customer->custemail,
                    'Phone' => $customer->custphone,
                    'CustomerPhoto' => $photoEvalURL,
                    'BlackListStatus' => $blackListStatus,
                    'Rider' => $waiverStatus,
                    'AllowedTrike' => $TrikeStatus
                )
            ]);
        } else {
            return response()->json([
                'Message' => 'Customer Not Found'
            ]);
        }
        break;

    case 112: //Get Models by Group
        $groupID = $this->globalFunctions->dataParser($decodedjson, 'GroupID');
        $modelQuerySQL = DB::table('models')->where('groupid', $groupID)->get();
        return response()->json([
            'Message' => 'Success',
            'Models' => $modelQuerySQL
        ]);
        break;

    case 113: //Get Waitlist by Model
        $modelID = $this->globalFunctions->dataParser($decodedjson, 'ModelID');
        $waitListQuery = DB::table('queue')
            ->where('eventid', $proxyEventID)
            ->where('clientid', $clientID)
            ->where('modelid', $modelID)
            ->get();
        return response()->json([
            'Message' => 'Success',
            'Waitlist' => $waitListQuery
        ]);
        break;

    case 114: // Get Customer Data by CustID
        $custID = $this->globalFunctions->dataParser($decodedjson, 'CustID');
        $findCustomerResults = DB::table('customers')->where('custid', $custID)->get();

        if ($findCustomerResults->count() > 0) {
            return response()->json([
                'Message' => 'Success',
                'CustomerData' => array(
                    'CardNumber' => (string)$findCustomerResults[0]->cardnumber,
                    'FirstName' => $findCustomerResults[0]->custfname,
                    'LastName' => $findCustomerResults[0]->custlname,
                )
            ]);
        } else {
            return response()->json([
                'Message' => 'Customer Not Found'
            ]);
        }
        break;

    case 115: //Book Rider by Model
        $modelID = $this->globalFunctions->dataParser($decodedjson, 'ModelID');
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        $waitTimeQuery = DB::table('queue')
            ->where('modelid', $modelID)
            ->where('eventid', $proxyEventID)
            ->get();

        $customerQuery = DB::table('customers')->where('cardnumber', $cardNumber)->get();
        $getModelCount = DB::table('vehicles')->where('modelid', $modelID)->get();
        $getServiceCount = DB::table('vehicles')->where('modelid', $modelID)->where('vehiclestatus', 'SERVICE')->get();
        $getUnavailableCount = DB::table('vehicles')->where('modelid', $modelID)->where('vehiclestatus', 'UNAVAILABLE')->get();

        $date = date_create(date("Y-m-d H:i:s"));
        $getOutCount = DB::table('vehicles')->where('modelid', $modelID)->where('vehiclestatus', 'OUT')->get();

        $getModelName = '';
        if ($getModelCount->count() > 0) {
            $getModelNameFromDB = DB::table('models')->where('modelid', $modelID)->first();
            $getModelName = $getModelNameFromDB ? $getModelNameFromDB->modelname : '';
        }

        $currentProxyCourseTime = $proxyCourseTime;
        if ((substr($getModelName, 0, 3)) == 'Tri') {
            $currentProxyCourseTime = $trikeCourseTime;
        }

        $mathServiceCount = $getServiceCount->count();
        $mathModelCount = $getModelCount->count();
        $mathUnavailableCount = $getUnavailableCount->count();
        $mathWaitListCount = $waitTimeQuery->count();
        $mathNetAvailableBikes = $mathModelCount - ($mathServiceCount + $mathUnavailableCount);
        $rideMultiplier = ($mathWaitListCount + $getOutCount->count()) - $mathNetAvailableBikes;
        $rideTimeVariable = $rideMultiplier * $currentProxyCourseTime;

        $rideTime = date("Y-m-d H:i:s");
        if ($rideTimeVariable >= 0) {
            $getFirstPersonTime = DB::table('queue')
                ->where('modelid', $modelID)
                ->orderBy('estimatedridetime', 'asc')
                ->get();

            $index = $rideMultiplier - $getOutCount->count();
            if (isset($getFirstPersonTime[$index])) {
                $date = date_create($getFirstPersonTime[$index]->estimatedridetime);
            }
            $date = date_add($date, date_interval_create_from_date_string(($currentProxyCourseTime . ' minutes')));
            $rideTime = date_format($date, 'Y-m-d H:i:s');
        }

        if ($customerQuery->count() > 0) {
            DB::table('queue')->insert([
                'custid' => $customerQuery[0]->custid,
                'checkintime' => date("Y-m-d H:i:s"),
                'servertime' => date("Y-m-d H:i:s"),
                'modelid' => $modelID,
                'clientid' => $clientID,
                'eventid' => $proxyEventID,
                'estimatedridetime' => $rideTime
            ]);
        }

        $responseArray = array(
            'Message' => 'Success',
            'EstimatedRideTime' => $rideTime
        );

        return response()->json($responseArray);
        break;

    case 116: //Get WaitTime by Model ID
        $modelID = $this->globalFunctions->dataParser($decodedjson, 'ModelID');

        $waitTimeQuery = DB::table('queue')
            ->where('modelid', $modelID)
            ->where('eventid', $proxyEventID)
            ->get();

        $getModelCount = DB::table('vehicles')->where('modelid', $modelID)->get();
        $getServiceCount = DB::table('vehicles')->where('modelid', $modelID)->where('vehiclestatus', 'SERVICE')->get();
        $getUnavailableCount = DB::table('vehicles')->where('modelid', $modelID)->where('vehiclestatus', 'UNAVAILABLE')->get();
        $getOutCount = DB::table('vehicles')->where('modelid', $modelID)->where('vehiclestatus', 'OUT')->get();

        $date = date_create(date("Y-m-d H:i:s"));

        $getModelName = '';
        if ($getModelCount->count() > 0) {
            $getModelNameFromDB = DB::table('models')->where('modelid', $modelID)->first();
            $getModelName = $getModelNameFromDB ? $getModelNameFromDB->modelname : '';
        }

        $currentProxyCourseTime = $proxyCourseTime;
        if ((substr($getModelName, 0, 3)) == 'Tri') {
            $currentProxyCourseTime = $trikeCourseTime;
        }

        $mathServiceCount = $getServiceCount->count();
        $mathModelCount = $getModelCount->count();
        $mathUnavailableCount = $getUnavailableCount->count();
        $mathWaitListCount = $waitTimeQuery->count();
        $mathNetAvailableBikes = $mathModelCount - ($mathServiceCount + $mathUnavailableCount);
        $rideMultiplier = ($mathWaitListCount + $getOutCount->count()) - $mathNetAvailableBikes;
        $rideTimeVariable = $rideMultiplier * $currentProxyCourseTime;

        $rideTime = date("Y-m-d H:i:s");
        if ($rideTimeVariable >= 0) {
            $getFirstPersonTime = DB::table('queue')
                ->where('modelid', $modelID)
                ->orderBy('estimatedridetime', 'asc')
                ->get();

            $index = $rideMultiplier - $getOutCount->count();
            if (isset($getFirstPersonTime[$index])) {
                $date = date_create($getFirstPersonTime[$index]->estimatedridetime);
            }
            $date = date_add($date, date_interval_create_from_date_string(($currentProxyCourseTime . ' minutes')));
            $rideTime = date_format($date, 'Y-m-d H:i:s');
        }

        return response()->json([
            'Message' => 'Success',
            'EstimatedRideTime' => $rideTime
        ]);
        break;

    case 117: //Delete Rider by Card Number
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $custID = DB::table('customers')->where('cardnumber', $cardNumber)->value('custid');
        $modelID = $this->globalFunctions->dataParser($decodedjson, 'ModelID');

        DB::table('queue')->where([
            'custid' => $custID,
            'eventid' => $proxyEventID,
            'clientid' => $clientID,
            'modelid' => $modelID
        ])->delete();

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 118: //Get Bike Info by VIN
        $bikeVIN = $this->globalFunctions->dataParser($decodedjson, 'VIN');
        $vinSQL = DB::table('vehicles')->where('cov', $bikeVIN)->first();

        return response()->json([
            'Message' => 'Success',
            'BikeDetails' => $vinSQL
        ]);
        break;

    case 119: //Checkout Bike by Card Number
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $vehicleID = $this->globalFunctions->dataParser($decodedjson, 'VIN');
        $passenger = $this->globalFunctions->dataParser($decodedjson, 'Passenger');

        $custID = DB::table('customers')->where('cardnumber', $cardNumber)->value('custid');
        $passengerCustID = DB::table('customers')->where('cardnumber', $passenger)->value('custid');

        $date = date_create(date("Y-m-d H:i:s"));

        $getVehicleDetails = DB::table('vehicles')->where('vehiclevin', $vehicleID)->first();

        if ($getVehicleDetails) {
            $getModelID = $getVehicleDetails->modelid;
            $getModelName = DB::table('models')->where('modelid', $getModelID)->first();
            $actualModelName = $getModelName ? $getModelName->modelname : '';

            $currentProxyCourseTime = $proxyCourseTime;
            if (substr($actualModelName, 0, 3) == 'Tri') {
                $currentProxyCourseTime = $trikeCourseTime;
            }

            date_add($date, date_interval_create_from_date_string($currentProxyCourseTime . ' minutes'));
            $dueAt = date_format($date, 'Y-m-d H:i:s');

            $passengerValue = null;
            if (strlen($passenger) >= 3) {
                $passengerValue = $passenger;
            }

            //Update Bike Status
            DB::table('vehicles')->where('vehiclevin', $vehicleID)->update([
                'vehiclestatus' => 'OUT',
                'vehicleduein' => $dueAt,
                'currentrider' => $cardNumber,
                'currentpassenger' => $passengerValue,
            ]);

            //write rider tx
            if ($custID) {
                DB::table('customertrans')->insert([
                    'eventid' => $proxyEventID,
                    'transtype' => 4,
                    'custid' => $custID,
                    'transdate' => date("Y-m-d H:i:s"),
                    'servertime' => date("Y-m-d H:i:s"),
                    'terminalid' => $terminalID,
                    'transdescriptionblob' => json_encode(["DemoRide" => $vehicleID, "RiderStatus" => 'Rider'])
                ]);

                //Remove from WaitList
                DB::table('queue')->where([
                    'custid' => $custID,
                    'eventid' => $proxyEventID,
                    'clientid' => $clientID
                ])->delete();
            }

            if ($passengerCustID) {
                DB::table('customertrans')->insert([
                    'eventid' => $proxyEventID,
                    'transtype' => 4,
                    'custid' => $passengerCustID,
                    'transdate' => date("Y-m-d H:i:s"),
                    'servertime' => date("Y-m-d H:i:s"),
                    'terminalid' => $terminalID,
                    'transdescriptionblob' => json_encode(["DemoRide" => $vehicleID, "RiderStatus" => 'Passenger'])
                ]);
            }
        }

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 120: //Checkin Bike by VIN
        $bikeVIN = $this->globalFunctions->dataParser($decodedjson, 'VIN');

        $vehicle = DB::table('vehicles')->where('vehiclevin', $bikeVIN)->first();
        
        $riderArray = null;
        $passengerArray = [];

        if ($vehicle) {
            $currentPassenger = $vehicle->currentpassenger;
            $currentRider = $vehicle->currentrider;

            if ($currentRider) {
                $riderData = DB::table('customers')->where('cardnumber', $currentRider)->first();
                if ($riderData) {
                    $riderArray = array(
                        'CardNumber' => $riderData->cardnumber,
                        'FirstName' => $riderData->custfname,
                        'LastName' => $riderData->custlname,
                    );
                }
            }

            if ($currentPassenger) {
                $passengerData = DB::table('customers')->where('cardnumber', $currentPassenger)->first();
                if ($passengerData) {
                    $passengerArray = array(
                        'CardNumber' => $passengerData->cardnumber,
                        'FirstName' => $passengerData->custfname,
                        'LastName' => $passengerData->custlname,
                    );
                }
            }

            //Set bike back to available
            DB::table('vehicles')->where('vehiclevin', $bikeVIN)->update([
                'vehiclestatus' => 'Available',
                'vehicleduein' => '0',
                'currentrider' => null,
                'currentpassenger' => null
            ]);
        }

        return response()->json([
            'Message' => 'Success',
            'Rider' => $riderArray,
            'Passenger' => $passengerArray
        ]);
        break;

    case 121: //Checkout Bike for Service
        $bikeVIN = $this->globalFunctions->dataParser($decodedjson, 'VIN');

        DB::table('vehicles')->where('vehiclevin', $bikeVIN)->update([
            'vehiclestatus' => 'SERVICE',
            'vehicleduein' => date("Y-m-d H:i:s")
        ]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;


    case 122: //Checkin Bike FROM Service
        $bikeVIN = $this->globalFunctions->dataParser($decodedjson, 'VIN');
        DB::table('vehicles')->where('vehiclevin', $bikeVIN)->update([
            'vehiclestatus' => 'Available',
            'vehicleduein' => '0',
        ]);
        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 123: //Upload Photo
        $photoData = $this->globalFunctions->dataParser($decodedjson, 'PhotoData');
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $custIDSource = DB::table('customers')->where('cardnumber', $cardNumber)->value('custid');
        $photoData = base64_decode($photoData);
        $photoURL = '/assets/photos/' . $cardNumber . '.jpg';

        if (!file_exists(public_path('assets/photos'))) {
            mkdir(public_path('assets/photos'), 0777, true);
        }

        if (!file_put_contents(public_path("assets/photos/" . $cardNumber . ".jpg"), $photoData)) {
            return response()->json([
                "Message" => 'Failed'
            ]);
        }

        $insertPhoto = DB::table('regphotos')->insert([
            'custid' => $custIDSource,
            'photolocation' => "/assets/photos/" . $cardNumber . ".jpg",
            'phototimetaken' => date("Y-m-d H:i:s"),
            'servertime' => date("Y-m-d H:i:s")
        ]);

        if ($insertPhoto) {
            return response()->json([
                'Message' => 'Success',
                'PhotoURL' => $photoURL
            ]);
        }
        break;

    case 124: //Blacklist Customer
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $restrictReason = $this->globalFunctions->dataParser($decodedjson, 'RestrictReason');
        $driversLicense = DB::table('customers')->where('cardnumber', $cardNumber)->value('custdriverslicense');

        if (strlen($driversLicense) > 0) {
            DB::table('restrictedriders')->insert([
                'restrictlic' => $driversLicense,
                'restrictcomment' => $restrictReason,
                'restricttime' => date("Y-m-d H:i:s"),
                'servertime' => date("Y-m-d H:i:s"),
                'clientid' => $clientID
            ]);
        }

        DB::table('restrictedriders')->insert([
            'restrictlic' => $cardNumber,
            'restrictcomment' => $restrictReason,
            'restricttime' => date("Y-m-d H:i:s"),
            'servertime' => date("Y-m-d H:i:s"),
            'clientid' => $clientID
        ]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 125: //Upload Waiver
        $waiverData = $this->globalFunctions->dataParser($decodedjson, 'SigData');
        $waiverID = $this->globalFunctions->dataParser($decodedjson, 'WaiverID');
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        $RandomNumber = rand(1, 1000000);
        $custIDSource = DB::table('customers')->where('cardnumber', $cardNumber)->value('custid');
        $waiverData = base64_decode($waiverData);

        if (!file_exists(public_path('assets/legal/sigs'))) {
            mkdir(public_path('assets/legal/sigs'), 0777, true);
        }

        if (!file_put_contents(public_path("assets/legal/sigs/" . $RandomNumber . "-" . $waiverID . ".jpg"), $waiverData)) {
            return response()->json([
                "Message" => 'Failed'
            ]);
        }

        $getJumpStartWaiverID = DB::table('events')->where('eventid', $proxyEventID)->first();

        if ($getJumpStartWaiverID && $waiverID == $getJumpStartWaiverID->eventjumpstartwaiver) {
            if ($isCloud) {
                //sendJumpStartEmail($proxyEventID, $cardNumber, $database);
            }
        }

        $insertWaiver = DB::table('legaldata')->insert([
            'legalsignature' => $waiverData,
            'legalid' => $waiverID,
            'custid' => $custIDSource,
            'legalsignaturetime' => $clientTime,
            'servertime' => date("Y-m-d H:i:s"),
            'processedtime' => date("Y-m-d H:i:s"),
            'legaldoclocation' => '/assets/sigs/' . $RandomNumber . '-' . $waiverID . '.jpg',
            'eventid' => $proxyEventID
        ]);

        if ($insertWaiver) {
            return response()->json([
                "Message" => 'Success'
            ]);
        }
        break;

    case 126: //Post Ride Survey Delete
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        $selectPRPhoto = DB::table('postridesurvey')->where('custid', $cardNumber)->value('ridephoto');

        if (!is_null($selectPRPhoto)) {
            $photoURL = $baseURL . $selectPRPhoto;

            //Add the actual photo verification check
            if (strlen($photoURL) > strlen($baseURL)) {
                if ($isCloud == false) {
                    $this->globalFunctions->sendPhoto($proxyEventID, $cardNumber, $photoURL);
                }
            }
        }

        DB::table('postridesurvey')->where('custid', $cardNumber)->delete();

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 127: //Insert PR Survey Data
        $custCardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        if ($custCardNumber == 0) {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }

        $insertDB = DB::table('postridesurvey')->insertGetId([
            'clientid' => 1,
            'custid' => $custCardNumber,
            'checkintime' => date("Y-m-d H:i:s")
        ]);

        return response()->json([
            'Message' => 'Success',
            'PRSurveyID' => $insertDB
        ]);
        break;

    case 128: //Update PR Survey with Photo
        $prSurveyID = $this->globalFunctions->dataParser($decodedjson, 'PRSurveyID');
        $prPictureData = $this->globalFunctions->dataParser($decodedjson, 'PRPictureData');

        $randomNumber = rand(1, 50000);
        $prPicture = base64_decode($prPictureData);

        if (!file_exists(public_path('assets/postride'))) {
            mkdir(public_path('assets/postride'), 0777, true);
        }

        if (!file_put_contents(public_path("assets/postride/" . $prSurveyID . "-" . $randomNumber . ".jpg"), $prPicture)) {
            return response()->json([
                "Message" => 'Failed'
            ]);
        }

        DB::table('postridesurvey')->where('prsurveyid', $prSurveyID)->update([
            'ridephoto' => "/assets/postride/" . $prSurveyID . "-" . $randomNumber . ".jpg"
        ]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 129: //Get all Post Ride Survey Data
        $getData = DB::table('postridesurvey')->orderBy('checkintime', 'desc')->get();

        $compiledArray = array();

        foreach ($getData as $x => $row) {
            $discoverCustID = DB::table('customers')->where('cardnumber', $row->custid)->value('custid');

            $photoLocation = '';
            $custEmail = '';
            $custFname = '';
            $custLname = '';
            $cardNumber = '';

            if ($discoverCustID) {
                $photoLocation = DB::table('regphotos')->where('custid', $discoverCustID)->value('photolocation') ?: '';
                $customerInfo = DB::table('customers')->where('custid', $discoverCustID)->first();
                if ($customerInfo) {
                    $custEmail = $customerInfo->custemail;
                    $custFname = $customerInfo->custfname;
                    $custLname = $customerInfo->custlname;
                    $cardNumber = $customerInfo->cardnumber;
                }
            }

            $compiledArray[$x] = array(
                'PRSurveyID' => $row->prsurveyid,
                'CustomerData' => array(
                    'CardNumber' => $cardNumber,
                    'CustEmail' => $custEmail,
                    'CustPhoto' => $photoLocation,
                    'CustFirstName' => $custFname,
                    'CustLastName' => $custLname
                ),
                'Time' => $row->checkintime,
                'RidePhoto' => $row->ridephoto
            );
        }

        return response()->json([
            'Message' => 'Success',
            'PRSurveyItems' => $compiledArray
        ]);
        break;


    case 130: //Update Email Address
        $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $NewEmail = $this->globalFunctions->dataParser($decodedjson, 'NewEmail');

        DB::table('customers')->where('cardnumber', $CardNumber)->update(['custemail' => $NewEmail]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;


    //START OF NHRA WEB SERIES

    case 131: //GET TOP X SCORES
        $requestedCount = $this->globalFunctions->dataParser($decodedjson, 'RacerCount');

        $getTopScores = DB::table('dragracescores')
            ->where('eventid', $proxyEventID)
            ->orderBy('score', 'asc')
            ->orderBy('scoreid', 'asc')
            ->limit($requestedCount)
            ->get();

        $racersArray = array();

        if ($getTopScores->count() > 0) {
            foreach ($getTopScores as $key => $racer) {
                $getCustomerData = DB::table('customers')->where('cardnumber', $racer->cardnumber)->first();

                if ($getCustomerData) {
                    $getCustomerPhoto = DB::table('regphotos')->where('custid', $getCustomerData->custid)->first();

                    $racersArray[$key]['CardNumber'] = $getCustomerData->cardnumber;
                    $racersArray[$key]['CustName'] = $getCustomerData->custfname . ' ' . $getCustomerData->custlname;
                    $racersArray[$key]['DragRaceTime'] = $racer->score;

                    if ($getCustomerPhoto) {
                        $racersArray[$key]['CustPhoto'] = $getCustomerPhoto->photolocation;
                    } else {
                        $racersArray[$key]['CustPhoto'] = 'None';
                    }
                }
            }

            if (count($racersArray) > 0) {
                return response()->json([
                    'Message' => 'Success',
                    'Racers' => $racersArray
                ]);
            } else {
                return response()->json([
                    'Message' => 'Failed'
                ]);
            }
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;

    case 132: //Disqualify Racer
        $disqualifyCardnumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        $deleteRacer = DB::table('dragracescores')
            ->where('cardnumber', $disqualifyCardnumber)
            ->where('eventid', $proxyEventID)
            ->delete();

        if ($deleteRacer) {
            return response()->json([
                'Message' => 'Success'
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;

    case 133: //Submit Score
        $submitCardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $dragRaceTime = $this->globalFunctions->dataParser($decodedjson, 'DragRaceTime');

        $checkExisting = DB::table('dragracescores')
            ->where('cardnumber', $submitCardNumber)
            ->where('eventid', $proxyEventID)
            ->where('datetime', date("Y:m:d"))
            ->get();

        if ($checkExisting->count() > 0) {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }

        $insertRecord = DB::table('dragracescores')->insert([
            'cardnumber' => $submitCardNumber,
            'eventid' => $proxyEventID,
            'score' => $dragRaceTime,
            'datetime' => date("Y:m:d")
        ]);

        if ($insertRecord) {
            return response()->json([
                'Message' => 'Success'
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;


    case 134: //Upload Photo
        $photoData = $this->globalFunctions->dataParser($decodedjson, 'PhotoData');
        $cardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $custIDSource = DB::table('customers')
            ->select('custid', 'custfname')
            ->where('cardnumber', $cardNumber)
            ->first();

        $photoData = base64_decode($photoData);
        $photoURL = '/assets/nhracards/' . $cardNumber . '.jpg';

        if (!file_exists(public_path('assets/nhracards'))) {
            mkdir(public_path('assets/nhracards'), 0777, true);
        }

        if (!file_put_contents(public_path("assets/nhracards/" . $cardNumber . ".jpg"), $photoData)) {
            return response()->json([
                "Message" => 'Failed'
            ]);
        }

        if ($isCloud == false) {
            $ini_filename = public_path('assets/nhracards/' . $cardNumber . ".jpg");
            if (file_exists($ini_filename) && function_exists('imagecreatefromjpeg')) {
                $im = imagecreatefromjpeg($ini_filename);
                if ($im) {
                    $to_crop_array = array('x' => 170, 'y' => 20, 'width' => 300, 'height' => 400);
                    $thumb_im = imagecrop($im, $to_crop_array);
                    if ($thumb_im) {
                        imagejpeg($thumb_im, public_path('assets/nhracards/' . $cardNumber . "-cropped.jpg"));
                        imagedestroy($thumb_im);
                    }
                    imagedestroy($im);
                }
            }
        }

        if ($custIDSource) {
            $insertPhoto = DB::table('nhraphotos')->insert([
                'custid' => $custIDSource->custid,
                'photolocation' => "/assets/nhracards/" . $cardNumber . ".jpg",
                'phototimetaken' => date("Y-m-d H:i:s"),
                'servertime' => date("Y-m-d H:i:s")
            ]);

            if ($insertPhoto) {
                $custom_layout = array(53.6, 85);
                $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $custom_layout, true, 'UTF-8', false);
                $pdf->SetMargins(0, 0, 0, true);
                $pdf->setPrintHeader(false);
                $pdf->SetFooterMargin(0);
                $pdf->setPrintFooter(false);
                $pdf->SetAutoPageBreak(TRUE, 0);

                $pdf->SetFont('helvetica', '', 10);
                $pdf->AddPage();

                $img_file = public_path('assets/nhracards/' . $cardNumber . '-cropped.jpg');
                if (!file_exists($img_file)) {
                    $img_file = public_path('assets/nhracards/' . $cardNumber . '.jpg');
                }

                $pdf->SetXY(0, 0);
                if (file_exists($img_file)) {
                    $pdf->Image($img_file, 14.1, 41.5, 25.4, 34);
                }

                $pdf->SetFillColor(245, 130, 32);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetXY(14.1, 70.5);
                $pdf->Cell(25.4, 5, $custIDSource->custfname, 0, false, 'L', '#F58220', '', 0, false, 'T', 'M');

                $pdf->AddPage();
                $pdf->SetXY(0, 0);
                $pdf->write2DBarcode($cardNumber . '!NCT-HD', 'PDF417', 14.3, 65, 45.4, 15);

                if (!file_exists(public_path('assets/nhrapdf'))) {
                    mkdir(public_path('assets/nhrapdf'), 0777, true);
                }

                $file_location = public_path("assets/nhrapdf/" . $cardNumber . ".pdf");
                $pdf->Output($file_location, 'F');

                if ($isCloud == false) {
                    exec('lpr ' . escapeshellarg(public_path('assets/nhrapdf/' . $cardNumber . ".pdf")));
                    DB::table('printhistory')->insert([
                        'cardnumber' => $cardNumber,
                        'printdatetime' => date("Y-m-d H:i:s")
                    ]);
                }

                return response()->json([
                    'Message' => 'Success',
                    'PhotoURL' => $photoURL
                ]);
            }
        }

        return response()->json([
            'Message' => 'Failed'
        ]);
        break;

    case 135: //Delete all drag races for that day
        DB::table('dragracescores')->delete();

        return response()->json([
            'Message' => 'Success'
        ]);
        break;


    case 136: //Authenticate Login
        if ($appMode == 35) {
            $specifiedUsername = $this->globalFunctions->dataParser($decodedjson, 'username');
            $specifiedPassword = $this->globalFunctions->dataParser($decodedjson, 'password');

            $getEventsList = DB::table('hogevents')->select('hogeventid', 'eventname')->where('eventend', '>=', date("Y-m-d"))->get();

            $eventsListArray = array();
            foreach ($getEventsList as $x => $row) {
                $eventsListArray[$x]['eventid'] = $row->hogeventid;
                $eventsListArray[$x]['eventname'] = $row->eventname;
            }

            return response()->json([
                'Message' => 'Success',
                'Events' => $eventsListArray
            ]);
        } else {
            $specifiedUsername = $this->globalFunctions->dataParser($decodedjson, 'username');
            $specifiedPassword = $this->globalFunctions->dataParser($decodedjson, 'password');

            $getEventsList = DB::table('events')->select('eventid', 'eventname')->where('eventend', '>=', date("Y-m-d"))->get();

            return response()->json([
                'Message' => 'Success',
                'Events' => $getEventsList
            ]);
        }
        break;

    case 137: //Get list of Reprints
        $getList = DB::table('printhistory')->orderBy('printdatetime', 'desc')->limit(30)->get();

        if ($getList->count() > 0) {
            $customCustomerArray = array();

            foreach ($getList as $key => $customer) {
                $getCustomerData = DB::table('customers')->where('cardnumber', $customer->cardnumber)->first();

                if ($getCustomerData) {
                    $customCustomerArray[$key]['PrintID'] = $customer->printid;
                    $customCustomerArray[$key]['PrintTime'] = $customer->printdatetime;
                    $customCustomerArray[$key]['FirstName'] = $getCustomerData->custfname;
                    $customCustomerArray[$key]['LastName'] = $getCustomerData->custlname;
                }
            }

            return response()->json([
                'Message' => 'Success',
                'PrintHistory' => $customCustomerArray
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;

    case 138: //Reprint
        $printID = $this->globalFunctions->dataParser($decodedjson, 'PrintID');
        $getCustomerCardNumber = DB::table('printhistory')->where('printid', $printID)->value('cardnumber');

        if ($isCloud == false && $getCustomerCardNumber) {
            exec('lpr ' . escapeshellarg(public_path('assets/nhrapdf/' . $getCustomerCardNumber . ".pdf")));
        }

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 139: //Upload Photo App Picture
        $PictureData = $this->globalFunctions->dataParser($decodedjson, 'PictureData');
        $CardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        $RandomNumber = rand(1, 50000);
        $Picture = base64_decode($PictureData);

        if (!file_exists(public_path('assets/photoapp'))) {
            mkdir(public_path('assets/photoapp'), 0777, true);
        }

        if (!file_put_contents(public_path("assets/photoapp/" . $CardNumber . "-" . $RandomNumber . ".jpg"), $Picture)) {
            return response()->json([
                "Message" => 'Failed'
            ]);
        }

        $photoURL = $baseURL . "/assets/photoapp/" . $CardNumber . "-" . $RandomNumber . ".jpg";

        if ($isCloud == true) {
            $this->globalFunctions->sendPhotoApp($proxyEventID, $CardNumber, $photoURL);
        }

        $CustomerID = DB::table('customers')->where('cardnumber', $CardNumber)->value('custid');

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 140: //Get next card number auto assign
        if ($isCloud == false) {
            $getNextCard = DB::table('cards')
                ->where('eventid', 0)
                ->where('cardbatch', 9)
                ->value('cardnumber');
        } else {
            $getNextCard = DB::table('cards')
                ->whereNull('eventid')
                ->where('cardbatch', 9)
                ->value('cardnumber');
        }

        if ($getNextCard) {
            $updateCardNumber = DB::table('cards')
                ->where('cardnumber', $getNextCard)
                ->update(['eventid' => $proxyEventID]);

            if ($updateCardNumber) {
                return response()->json([
                    'Message' => 'Success',
                    'CardNumber' => (string) $getNextCard
                ]);
            }
        }

        return response()->json([
            'Message' => 'Failed'
        ]);
        break;
    
    case 1400: //Get next card number auto assign for pre registration
        if ($isCloud == false) { 
            $getNextCard = DB::table('cards')
                ->where('eventid', 0)
                ->where('cardbatch', 1)
                ->value('cardnumber');
        } else { 
            $getNextCard = DB::table('cards')
                ->whereNull('eventid')
                ->where('cardbatch', 1)
                ->value('cardnumber');            
        }

        if ($getNextCard) {
            $updateCardNumber = DB::table('cards')
                ->where('cardnumber', $getNextCard)
                ->update(['eventid' => $proxyEventID]);

            if ($updateCardNumber) {
                return response()->json([
                    'Message' => 'Success',
                    'CardNumber' => $getNextCard
                ]);
            }
        }

        return response()->json([
            'Message' => 'Failed'
        ]);
        break;

    case 141: //Checkout Bike for Unavailable
        $bikeVIN = $this->globalFunctions->dataParser($decodedjson, 'VIN');

        DB::table('vehicles')
            ->where('vehiclevin', $bikeVIN)
            ->update([
                'vehiclestatus' => 'UNAVAILABLE',
                'vehicleduein' => date("Y-m-d H:i:s")
            ]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 142: //Get Details by COV Number
        $bikeVIN = $this->globalFunctions->dataParser($decodedjson, 'VIN');

        $getDataForBike = DB::table('vehicles')->where('cov', $bikeVIN)->get();

        if ($getDataForBike->count() > 0) {
            if ($isCloud == false) {
                DB::table('vehicles')->where('cov', $bikeVIN)->update(['checkinflag' => '1']);
            }

            $compiledList = array();
            foreach ($getDataForBike as $row) {
                $modelName = DB::table('models')->where('modelid', $row->modelid)->value('modelname') ?: "Unassigned";
                $groupName = DB::table('vehiclegroups')->where('groupid', $row->groupid)->value('groupname') ?: "Unassigned";

                $rowArray = (array) $row;
                $rowArray['ModelName'] = $modelName;
                $rowArray['GroupName'] = $groupName;

                $compiledList[] = $rowArray;
            }

            return response()->json([
                'Message' => 'Success',
                'BikeDetails' => $compiledList[0]
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed',
                'Details' => 'DNE'
            ]);
        }
        break;

    case 143: //Insert new Bike
        $vehicleVIN = $this->globalFunctions->dataParser($decodedjson, 'vehiclevin');
        $vehicleNickName = $this->globalFunctions->dataParser($decodedjson, 'vehiclenickname');
        $vehicleGroupID = $this->globalFunctions->dataParser($decodedjson, 'groupid');
        $vehicleModel = $this->globalFunctions->dataParser($decodedjson, 'modelid');
        $vehicleTruckID = $this->globalFunctions->dataParser($decodedjson, 'truckid');
        $vehicleLicPlate = $this->globalFunctions->dataParser($decodedjson, 'vehiclelicplate');
        $vehicleColor = $this->globalFunctions->dataParser($decodedjson, 'vehiclecolor');
        $vehicleCOV = $this->globalFunctions->dataParser($decodedjson, 'cov');

        $checkDatabaseForExisting = DB::table('vehicles')->where('cov', $vehicleCOV)->value('vehicleid');

        if ($checkDatabaseForExisting) {
            DB::table('vehicles')->where('cov', $vehicleCOV)->update([
                'vehiclevin' => $vehicleVIN,
                'vehiclenickname' => $vehicleNickName,
                'groupid' => $vehicleGroupID,
                'vehiclestatus' => 'Available',
                'modelid' => $vehicleModel,
                'truckid' => $vehicleTruckID,
                'vehiclelicplate' => $vehicleLicPlate,
                'vehiclecolor' => $vehicleColor,
                'clientid' => $clientID,
                'cov' => $vehicleCOV
            ]);

            return response()->json([
                'Message' => 'Success'
            ]);
        } else {
            $insertBikeIntoDatabase = DB::table('vehicles')->insert([
                'vehiclevin' => $vehicleVIN,
                'vehiclenickname' => $vehicleNickName,
                'groupid' => $vehicleGroupID,
                'vehiclestatus' => 'Available',
                'modelid' => $vehicleModel,
                'truckid' => $vehicleTruckID,
                'vehiclelicplate' => $vehicleLicPlate,
                'vehiclecolor' => $vehicleColor,
                'clientid' => $clientID,
                'cov' => $vehicleCOV
            ]);

            if ($insertBikeIntoDatabase) {
                return response()->json([
                    'Message' => 'Success'
                ]);
            } else {
                return response()->json([
                    'Message' => 'Failed'
                ]);
            }
        }
        break;

    case 144: //Get Vehicle Bike List
        $vinSQL = DB::table('vehicles')->get();

        return response()->json([
            'Message' => 'Success',
            'BikeDetails' => $vinSQL
        ]);
        break;

    case 145: //Remove bike from the list
        $bikeCOV = $this->globalFunctions->dataParser($decodedjson, 'cov');

        if ($isCloud == false) {
            DB::table('vehicles')->where('cov', $bikeCOV)->delete();

            return response()->json([
                'Message' => 'Success'
            ]);
        } else if ($isCloud == true) {
            DB::table('vehicles')->where('cov', $bikeCOV)->update(['truckid' => null]);

            return response()->json([
                'Message' => 'Updated'
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;


    case 146: //Get History by Bike
        $bikeCOV = $this->globalFunctions->dataParser($decodedjson, 'cov');
        $getBikeVIN = DB::table('vehicles')->where('cov', $bikeCOV)->value('vehiclevin');

        if ($getBikeVIN) {
            $totalCustomersArray = array();

            $getRecords = DB::table('customertrans')
                ->where('transdescriptionblob', 'LIKE', '%' . $getBikeVIN . '%')
                ->orderBy('transdate', 'desc')
                ->get();

            if ($getRecords->count() > 0) {
                foreach ($getRecords as $x => $record) {
                    $getCustomerDetails = DB::table('customers')->where('custid', $record->custid)->first();

                    if ($getCustomerDetails) {
                        $getCustomerPhoto = DB::table('regphotos')->where('custid', $getCustomerDetails->custid)->first();

                        $totalCustomersArray[$x]['CardNumber'] = $getCustomerDetails->cardnumber;
                        $totalCustomersArray[$x]['FirstName'] = $getCustomerDetails->custfname;
                        $totalCustomersArray[$x]['LastName'] = $getCustomerDetails->custlname;
                        $totalCustomersArray[$x]['AddressBlob'] = $getCustomerDetails->custaddress;
                        $totalCustomersArray[$x]['CountryID'] = $getCustomerDetails->custcountry;
                        $totalCustomersArray[$x]['DOB'] = $getCustomerDetails->custbirthday;
                        $totalCustomersArray[$x]['LicExpiration'] = $getCustomerDetails->custlicexpire;
                        $totalCustomersArray[$x]['MotorcycleLic'] = $getCustomerDetails->custmotorcyclelic;
                        $totalCustomersArray[$x]['Gender'] = $getCustomerDetails->custgender;
                        $totalCustomersArray[$x]['Ethnicity'] = $getCustomerDetails->custethnicity;
                        $totalCustomersArray[$x]['PreferredLanguage'] = $getCustomerDetails->custlang;
                        $totalCustomersArray[$x]['Email'] = $getCustomerDetails->custemail;
                        $totalCustomersArray[$x]['Phone'] = $getCustomerDetails->custphone;

                        if ($getCustomerPhoto) {
                            $totalCustomersArray[$x]['CustomerPhoto'] = $getCustomerPhoto->photolocation;
                        } else {
                            $totalCustomersArray[$x]['CustomerPhoto'] = 'None';
                        }
                    }
                }
            }

            return response()->json([
                'Message' => 'Success',
                'Details' => $totalCustomersArray
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;

    case 147: //Get Waivers by Card Number
        $customerProvidedCardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $getCustomerID = DB::table('customers')->where('cardnumber', $customerProvidedCardNumber)->value('custid');

        if ($getCustomerID) {
            $getWaiverData = DB::table('legaldata')->where('custid', $getCustomerID)->get();
            $waiverAssemblyList = array();

            if ($getWaiverData->count() > 0) {
                foreach ($getWaiverData as $x => $waiver) {
                    $waiverAssemblyList[$x]['WaiverID'] = $waiver->legalid;
                    $getWaiverName = DB::table('legal')->where('legalid', $waiver->legalid)->value('legalname');

                    if ($getWaiverName) {
                        $waiverAssemblyList[$x]['WaiverName'] = $getWaiverName;
                    } else {
                        $waiverAssemblyList[$x]['WaiverName'] = 'Removed-From-Cloud';
                    }
                }
            }

            return response()->json([
                'Message' => 'Success',
                'WaiversOnFile' => $waiverAssemblyList
            ]);
        } else {
            return response()->json([
                'Message' => 'Failed',
                'Details' => 'Customer ID not found'
            ]);
        }
        break;

    case 148: //Send Email by Legal ID
        $recipientEmailAddress = $this->globalFunctions->dataParser($decodedjson, 'Email');
        $requestedLegalID = $this->globalFunctions->dataParser($decodedjson, 'WaiverID');
        $customerCardNumber = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');

        $getCustomerID = DB::table('customers')->where('cardnumber', $customerCardNumber)->first();

        if ($isCloud == true && $getCustomerID) {
            $getWaiverID = DB::table('legaldata')
                ->where('custid', $getCustomerID->custid)
                ->where('legalid', $requestedLegalID)
                ->value('legaldataid');

            if ($getWaiverID) {
                $this->globalFunctions->serverSendEmail(
                    $recipientEmailAddress,
                    '<b>The system has received your request for a copy of a legal waiver form. Please view the form by clicking on the following link (using a computer):</b><br /><br /><a href="http://hd.kickstartuser.com/Reports/WaiverPDF.php?WaiverID=' . $getWaiverID . '">Download Legal Waiver in PDF</a><br /><br /><br /><b>This request was generated by the KickStart automated system provided by NcompassTrac, LLC. If the Waiver is not generated, please try again within 15 minutes because the On-Site transport may be slightly behind.</b>',
                    'Your Requested Legal Waiver Form'
                );

                return response()->json([
                    'Message' => 'Success'
                ]);
            } else {
                return response()->json([
                    'Message' => 'Failed'
                ]);
            }
        } else {
            return response()->json([
                'Message' => 'Success'
            ]);
        }
        break;

    case 149: //Get Vehicle Bike List
        $vinSQL = DB::table('vehicles')->get();
        $compiledList = array();

        if ($vinSQL->count() > 0) {
            foreach ($vinSQL as $row) {
                $calculateTime = 0;
                $nowTime = time();
                $vehicleTime = strtotime($row->vehicleduein ?: date("Y-m-d H:i:s"));
                $calculateTime = abs($vehicleTime - $nowTime) / 60;
                $vehicleDue = round($calculateTime, 1, PHP_ROUND_HALF_UP) . ' min';

                $modelName = DB::table('models')->where('modelid', $row->modelid)->value('modelname') ?: "Unassigned";
                $groupName = DB::table('vehiclegroups')->where('groupid', $row->groupid)->value('groupname') ?: "Unassigned";

                $rowArray = (array) $row;
                $rowArray['VehicleDue'] = $vehicleDue;
                $rowArray['ModelName'] = $modelName;
                $rowArray['GroupName'] = $groupName;

                $compiledList[] = $rowArray;
            }
        }

        return response()->json([
            'Message' => 'Success',
            'BikeDetails' => $compiledList
        ]);
        break;

    case 150: //HOG Search Request
        $HOGNumber = $this->globalFunctions->dataParser($decodedjson, 'HOGNumber');
        $FirstName = $this->globalFunctions->dataParser($decodedjson, 'FirstName');
        $LastName = $this->globalFunctions->dataParser($decodedjson, 'LastName');
        $AddressStreet = $this->globalFunctions->dataParser($decodedjson, 'AddressStreet');
        $AddressZip = $this->globalFunctions->dataParser($decodedjson, 'AddressZip');
        $AddressCountry = $this->globalFunctions->dataParser($decodedjson, 'AddressCountry');
        $CountryCode = $this->globalFunctions->dataParser($decodedjson, 'CountryCode');

        $customerResponseData = array();

        if (strlen($HOGNumber) > 0) {
            $getHogCards = DB::table('hogcards')->where('membershipnumber', $HOGNumber)->get();

            if ($getHogCards->count() > 0) {
                foreach ($getHogCards as $x => $row) {
                    $hogMemberExpiration = $row->membershipexpirationdate;
                    $expirationValue = 'Active';

                    $hogMemberYear = substr($hogMemberExpiration, 0, -2);
                    $hogMemberMonth = substr($hogMemberExpiration, -2);
                    $days = date('t', mktime(0, 0, 0, (int)$hogMemberMonth, 1, (int)$hogMemberYear));
                    $hogCombinedExpiration = $hogMemberMonth . '/' . $days . '/' . $hogMemberYear;

                    if (strtotime($hogCombinedExpiration) < strtotime(date('m/d/y'))) {
                        $expirationValue = 'Expired';
                    }

                    $customerResponseData[$x]['AddressStreet'] = $row->memberaddress1;
                    $customerResponseData[$x]['AddressCity'] = $row->membercity;
                    $customerResponseData[$x]['AddressZip'] = $row->memberpostalcode;
                    $customerResponseData[$x]['AddressCountry'] = trim(preg_replace('/\s\s+/', '', $row->memberaddresscountry));
                    $customerResponseData[$x]['HOGNumber'] = $row->membershipnumber;
                    $customerResponseData[$x]['HOGType'] = $row->membershiptypedescription;
                    $customerResponseData[$x]['HOGStatus'] = $expirationValue;
                    $customerResponseData[$x]['Expiration'] = $row->membershipexpirationdate;
                    $customerResponseData[$x]['FirstName'] = $row->memberfirstname;
                    $customerResponseData[$x]['LastName'] = $row->memberlastname;
                    $customerResponseData[$x]['AddressState'] = $row->memberstate;
                }
            } else {
                return response()->json([
                    'Message' => 'Success',
                    'Results' => array()
                ]);
            }
        } else if (strlen($FirstName) > 0 || strlen($LastName) > 0) {
            $FirstName = $this->globalFunctions->stripInvalidCharacters($FirstName);
            $LastName = $this->globalFunctions->stripInvalidCharacters($LastName);

            $query = DB::table('hogcards');

            if (strlen($FirstName) > 0 && strlen($LastName) > 0) {
                $query->whereRaw('memberfirstname SOUNDS LIKE ?', [$FirstName])
                      ->whereRaw('memberlastname SOUNDS LIKE ?', [$LastName])
                      ->where('memberaddresscountry', 'like', '%' . $CountryCode . '%')
                      ->orderBy('memberlastname', 'asc');
            } else if (strlen($FirstName) > 0 && strlen($LastName) == 0) {
                $query->whereRaw('memberfirstname SOUNDS LIKE ?', [$FirstName])
                      ->where('memberaddresscountry', 'like', '%' . $CountryCode . '%')
                      ->orderBy('memberfirstname', 'asc');
            } else if (strlen($FirstName) == 0 && strlen($LastName) > 0) {
                $query->whereRaw('memberlastname SOUNDS LIKE ?', [$LastName])
                      ->where('memberaddresscountry', 'like', '%' . $CountryCode . '%')
                      ->orderBy('memberlastname', 'asc');
            }

            $getHogCards = $query->get();

            if ($getHogCards->count() > 0) {
                foreach ($getHogCards as $x => $row) {
                    $hogMemberExpiration = $row->membershipexpirationdate;
                    $expirationValue = 'Active';

                    $hogMemberYear = substr($hogMemberExpiration, 0, -2);
                    $hogMemberMonth = substr($hogMemberExpiration, -2);
                    $days = date('t', mktime(0, 0, 0, (int)$hogMemberMonth, 1, (int)$hogMemberYear));
                    $hogCombinedExpiration = $hogMemberMonth . '/' . $days . '/' . $hogMemberYear;

                    if (strtotime($hogCombinedExpiration) < strtotime(date('m/d/y'))) {
                        $expirationValue = 'Expired';
                    }

                    $customerResponseData[$x]['AddressStreet'] = $row->memberaddress1;
                    $customerResponseData[$x]['AddressCity'] = $row->membercity;
                    $customerResponseData[$x]['AddressZip'] = $row->memberpostalcode;
                    $customerResponseData[$x]['AddressCountry'] = trim(preg_replace('/\s\s+/', '', $row->memberaddresscountry));
                    $customerResponseData[$x]['HOGNumber'] = $row->membershipnumber;
                    $customerResponseData[$x]['HOGType'] = $row->membershiptypedescription;
                    $customerResponseData[$x]['HOGStatus'] = $expirationValue;
                    $customerResponseData[$x]['Expiration'] = $row->membershipexpirationdate;
                    $customerResponseData[$x]['FirstName'] = $row->memberfirstname;
                    $customerResponseData[$x]['LastName'] = $row->memberlastname;
                    $customerResponseData[$x]['AddressState'] = $row->memberstate;
                }
            } else {
                return response()->json([
                    'Message' => 'Success',
                    'Results' => array()
                ]);
            }
        } else if (strlen($AddressStreet) > 0) {
            $AddressStreet = $this->globalFunctions->stripInvalidCharacters($AddressStreet);

            $getHogCards = DB::table('hogcards')
                ->where('memberaddress1', 'like', '%' . $AddressStreet . '%')
                ->where('memberpostalcode', 'like', '%' . $AddressZip . '%')
                ->get();

            if ($getHogCards->count() > 0) {
                foreach ($getHogCards as $x => $row) {
                    $hogMemberExpiration = $row->membershipexpirationdate;
                    $expirationValue = 'Active';

                    $hogMemberYear = substr($hogMemberExpiration, 0, -2);
                    $hogMemberMonth = substr($hogMemberExpiration, -2);
                    $days = date('t', mktime(0, 0, 0, (int)$hogMemberMonth, 1, (int)$hogMemberYear));
                    $hogCombinedExpiration = $hogMemberMonth . '/' . $days . '/' . $hogMemberYear;

                    if (strtotime($hogCombinedExpiration) < strtotime(date('m/d/y'))) {
                        $expirationValue = 'Expired';
                    }

                    $customerResponseData[$x]['AddressStreet'] = $row->memberaddress1;
                    $customerResponseData[$x]['AddressCity'] = $row->membercity;
                    $customerResponseData[$x]['AddressZip'] = $row->memberpostalcode;
                    $customerResponseData[$x]['AddressCountry'] = trim(preg_replace('/\s\s+/', '', $row->memberaddresscountry));
                    $customerResponseData[$x]['HOGNumber'] = $row->membershipnumber;
                    $customerResponseData[$x]['HOGType'] = $row->membershiptypedescription;
                    $customerResponseData[$x]['HOGStatus'] = $expirationValue;
                    $customerResponseData[$x]['Expiration'] = $row->membershipexpirationdate;
                    $customerResponseData[$x]['FirstName'] = $row->memberfirstname;
                    $customerResponseData[$x]['LastName'] = $row->memberlastname;
                    $customerResponseData[$x]['AddressState'] = $row->memberstate;
                }
            } else {
                return response()->json([
                    'Message' => 'Success',
                    'Results' => array()
                ]);
            }
        }

        if (count($customerResponseData) > 0) {
            return response()->json([
                'Message' => 'Success',
                'Results' => $customerResponseData
            ]);
        } else {
            return response()->json([
                'Message' => 'Success',
                'Results' => ''
            ]);
        }
        break;

    case 151: //Get HOG Customer Data
        $HOGNumber = $this->globalFunctions->dataParser($decodedjson, 'HOGNumber');
        $AddressCountry = $this->globalFunctions->dataParser($decodedjson, 'AddressCountry');
        $SurveyID = $this->globalFunctions->dataParser($decodedjson, 'SurveyID');

        $getHogCard = DB::table('hogcards')->where('hogidentifier', $HOGNumber . $AddressCountry)->get();

        $getHOGSurveyData = DB::table('hogsurveydata')
            ->where('hogidentifier', $HOGNumber . $AddressCountry)
            ->where('surveyid', $SurveyID)
            ->get();

        $customerResponseData = array();

        if ($getHogCard->count() == 1) {
            $row = $getHogCard->first();
            $hogMemberExpiration = $row->membershipexpirationdate;
            $expirationValue = 'Active';

            $hogMemberYear = substr($hogMemberExpiration, 0, -2);
            $hogMemberMonth = substr($hogMemberExpiration, -2);
            $days = date('t', mktime(0, 0, 0, (int)$hogMemberMonth, 1, (int)$hogMemberYear));
            $hogCombinedExpiration = $hogMemberMonth . '/' . $days . '/' . $hogMemberYear;

            if (strtotime($hogCombinedExpiration) < strtotime(date('m/d/y'))) {
                $expirationValue = 'Expired';
            }

            $customerResponseData[0]['AddressStreet'] = $row->memberaddress1;
            $customerResponseData[0]['AddressCity'] = $row->membercity;
            $customerResponseData[0]['AddressZip'] = $row->memberpostalcode;
            $customerResponseData[0]['AddressCountry'] = trim(preg_replace('/\s\s+/', '', $row->memberaddresscountry));
            $customerResponseData[0]['HOGNumber'] = $row->membershipnumber;
            $customerResponseData[0]['HOGType'] = $row->membershiptypedescription;
            $customerResponseData[0]['HOGStatus'] = $expirationValue;
            $customerResponseData[0]['Expiration'] = $row->membershipexpirationdate;
            $customerResponseData[0]['FirstName'] = $row->memberfirstname;
            $customerResponseData[0]['LastName'] = $row->memberlastname;
            $customerResponseData[0]['AddressState'] = $row->memberstate;

            if ($getHOGSurveyData->count() > 0) {
                $surveyRow = $getHOGSurveyData->first();
                return response()->json([
                    'Message' => 'Success',
                    'CustomerData' => $customerResponseData[0],
                    'SurveyData' => array(
                        'CustomerID' => $surveyRow->hogidentifier,
                        'SurveyID' => $surveyRow->surveyid,
                        'SurveyQuestions' => json_decode($surveyRow->surveydatablob)
                    )
                ]);
            } else {
                return response()->json([
                    'Message' => 'Success',
                    'CustomerData' => $customerResponseData[0],
                    'SurveyData' => array('' => '')
                ]);
            }
        } else {
            return response()->json([
                'Message' => 'Failed'
            ]);
        }
        break;

    case 152: // Submit HOG Survey data


        $HOGMemberID = dataParser($decodedjson, 'HOGNumber');
        $HOGMemberCountry = dataParser($decodedjson, 'AddressCountry');
        $SurveyID = dataParser($decodedjson, 'SurveyID');
        $SurveyQuestions = dataParser($decodedjson, 'SurveyQuestions');
        $EventID = $proxyEventID;
        $SurveyCompletedTime = dataParser($decodedjson, 'SurveyCompletedTime');
        $LocationBlob = dataParser($decodedjson, 'LocationBlob');

        $HOGIdentifier = $HOGMemberID . $HOGMemberCountry;

        //Check if survey response already exists
    case 152: // Submit HOG Survey data
        $HOGIdentifier = $this->globalFunctions->dataParser($decodedjson, 'HOGIdentifier');
        $SurveyID = $this->globalFunctions->dataParser($decodedjson, 'SurveyID');
        $SurveyQuestions = $this->globalFunctions->dataParser($decodedjson, 'SurveyQuestions');
        $SurveyCompletedTime = $this->globalFunctions->dataParser($decodedjson, 'SurveyCompletedTime');
        $EventID = $this->globalFunctions->dataParser($decodedjson, 'EventID');
        $TerminalID = $this->globalFunctions->dataParser($decodedjson, 'TerminalID');
        $LocationBlob = $this->globalFunctions->dataParser($decodedjson, 'LocationBlob');

        $surveyExistsResponse = DB::table('hogsurveydata')
            ->where('hogidentifier', $HOGIdentifier)
            ->where('surveyid', $SurveyID)
            ->first();

        if ($surveyExistsResponse) {
            //Survey response already recorded for this client. Update!
            //Accommodate Old Local Data - Check before update!
            if (strtotime($surveyExistsResponse->surveydatetime) > strtotime($SurveyCompletedTime)) {
                return response()->json([
                    'Message' => 'Success',
                ]);
            }

            DB::table('hogsurveydata')
                ->where('surveyid', $SurveyID)
                ->where('hogidentifier', $HOGIdentifier)
                ->update([
                    'surveydatablob' => json_encode($SurveyQuestions),
                    'surveydatetime' => $SurveyCompletedTime,
                    'eventid' => $EventID,
                    'servertime' => date("Y-m-d H:i:s")
                ]);
        } else {
            //Survey Response doesn't exist
            DB::table('hogsurveydata')->insert([
                'hogidentifier' => $HOGIdentifier,
                'surveyid' => $SurveyID,
                'surveydatablob' => json_encode($SurveyQuestions),
                'surveydatetime' => $SurveyCompletedTime,
                'eventid' => $EventID,
                'servertime' => date("Y-m-d H:i:s")
            ]);

            $locationArray = array('Location' => $LocationBlob);

            if (strlen($LocationBlob) > 0) {
                DB::table('hogcustomertrans')->insert([
                    'eventid' => $EventID,
                    'transtype' => $appMode,
                    'hogidentifier' => $HOGIdentifier,
                    'transdate' => $SurveyCompletedTime,
                    'servertime' => date("Y-m-d H:i:s"),
                    'terminalid' => $TerminalID,
                    'transdescriptionblob' => json_encode($locationArray)
                ]);
            } else {
                DB::table('hogcustomertrans')->insert([
                    'eventid' => $EventID,
                    'transtype' => $appMode,
                    'hogidentifier' => $HOGIdentifier,
                    'transdate' => $SurveyCompletedTime,
                    'servertime' => date("Y-m-d H:i:s"),
                    'terminalid' => $TerminalID,
                    'transdescriptionblob' => null
                ]);
            }
        }

        return response()->json([
            'Message' => 'Success',
        ]);
        break;

    case 900: //Generate Physical Card Numbers for Print
        $cardGenerationCount = $this->globalFunctions->adminPostParser($decodedjson, 'CardCount');
        $securityToken = $this->globalFunctions->adminPostParser($decodedjson, 'SecurityToken');

        for ($card = 1; $card <= $cardGenerationCount; $card++) {
            $tempCardNumber = $this->globalFunctions->generateNewCardNumber();
            $cardExists = DB::table('cards')->where('cardnumber', $tempCardNumber)->exists();

            while ($cardExists) {
                //Card already exists, need to regenerate
                $tempCardNumber = $this->globalFunctions->generateNewCardNumber();
                $cardExists = DB::table('cards')->where('cardnumber', $tempCardNumber)->exists();
            }

            DB::table('cards')->insert([
                'cardnumber' => $tempCardNumber,
                'cardtype' => 'Physical',
                'cardbatch' => '2',
                'clientid' => $clientID
            ]);
        }

        return response()->json([
            'Message' => 'Success',
            'CardsCreated' => $cardGenerationCount
        ]);
        break;

    case 901:
        $results = DB::table('cards')->where('cardbatch', '2')->pluck('cardnumber');

        $out = "";
        foreach ($results as $cardnumber) {
            $out .= $cardnumber . "!NCT-YA\n";
        }
        return response($out)->header('Content-Type', 'text/plain');
        break;

    case 903:
        $time1 = time();
        sleep(60);
        $time2 = time();

        $diff = ($time2 - $time1) / 60;
        return response($time1 . "\n" . $time2 . "\n" . $diff)->header('Content-Type', 'text/plain');
        break;

    case 904: //Test Database Backup Download
        $this->globalFunctions->serverSendEmail('cdaden@ncompasstrac.com', 'Test', 'Test');
        return response('Done');
        break;

    case 905: //Test Printer
        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    //Insert customer details for send email cron
    //Created for Web Registration
    case 999: 
        $EventID            = $proxyEventID;
        $CardNumber         = $this->globalFunctions->dataParser($decodedjson, 'CardNumber');
        $CustomerID         = $this->globalFunctions->dataParser($decodedjson, 'CustomerID');
        $riderOrPassenger   = $this->globalFunctions->dataParser($decodedjson, 'riderOrPassenger');
        $TimeSlotTime   = $this->globalFunctions->dataParser($decodedjson, 'TimeSlotTime');
        $TimeSlotDate   = $this->globalFunctions->dataParser($decodedjson, 'TimeSlotDate');
        $TimeSlotModel   = $this->globalFunctions->dataParser($decodedjson, 'TimeSlotModel');

        //Insert Record
        DB::table('newregistrationemail')->insert([
            'card_number'   => $CardNumber,
            'event_id'      => $EventID,
            'customer_id'   => $CustomerID,
            'is_sent'       => 0,
            'is_under_cron' => 0,
            'slot_booking' => json_encode(array('BookingTime' => $TimeSlotTime, 'BookingDate' => $TimeSlotDate, 'BookingModel' => $TimeSlotModel)),
            'current_date'  => date("Y-m-d H:i:s"),
            'is_passenger'  => $riderOrPassenger
        ]);

        DB::table('reminderqueue')->insert([
            'eventid' => $EventID,
            'custid' => $CustomerID,
            'slot_booking' => json_encode(array('BookingTime' => $TimeSlotTime, 'BookingDate' => $TimeSlotDate, 'BookingModel' => $TimeSlotModel)),
            'type' => $riderOrPassenger,
            'regdate' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    //Insert parent details for preregistration
    //Created for Web Registration preregistrationcustomerparents
    case 1011:         
        $custid         = $this->globalFunctions->dataParser($decodedjson, 'custid');
        $parentfname    = $this->globalFunctions->dataParser($decodedjson, 'parentfname');
        $parentlname   = $this->globalFunctions->dataParser($decodedjson, 'parentlname');
        $parentemail   = $this->globalFunctions->dataParser($decodedjson, 'parentemail');
        $parentphone   = $this->globalFunctions->dataParser($decodedjson, 'parentphone');
        $parentbirthday   = $this->globalFunctions->dataParser($decodedjson, 'parentbirthday');

        //Insert Record
        DB::table('preregistrationcustomerparents')->insert([
            'custid'            => $custid,
            'parentfname'       => $parentfname,            
            'parentlname'       => $parentlname,
            'parentemail'       => $parentemail,
            'parentphone'       => $parentphone,
            'parentbirthday'    => $parentbirthday
        ]);

        return response()->json([
            'Message' => 'Success'
        ]);
        break;

    case 902: //Harley Test integration
        return response()->json([
            "Message" => "Success",
            "Customers" => [
                [
                    "CardNumber" => "1234567890",
                    "First Name" => "John",
                    "Last Name" => "Smith",
                    "AddressBlob" => "1234 Somewhere Ln., Demo Town, AZ, 86335",
                    "CountryID" => 10,
                    "DOB" => "08181988",
                    "LicExpiration" => "08182053",
                    "MotorcycleLic" => 1,
                    "Gender" => "M",
                    "Ethnicity" => "Caucasian",
                    "PreferredLanguage" => 0,
                    "Email" => "johnsmith@gmail.com",
                    "Phone" => "9286341111",
                    "Surveys" => [
                        [
                            "CustomerID" => 12345,
                            "SurveyID" => 202,
                            "SurveyQuestions" => [
                                [
                                    "QuestionID" => 123,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 334,
                                            "AnswerValue" => "Yes"
                                        ]
                                    ]
                                ],
                                [
                                    "QuestionID" => 124,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 337,
                                            "AnswerValue" => "johnsmith@gmail.com"
                                        ],
                                        [
                                            "AnswerID" => 338,
                                            "AnswerValue" => "9286341111"
                                        ]
                                    ]
                                ],
                                [
                                    "QuestionID" => 125,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 339,
                                            "AnswerValue" => "Harley Davidson"
                                        ],
                                        [
                                            "AnswerID" => 341,
                                            "AnswerValue" => "Kawasaki"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "CardNumber" => "1234567890",
                    "First Name" => "John",
                    "Last Name" => "Smith",
                    "AddressBlob" => "1234 Somewhere Ln., Demo Town, AZ, 86335",
                    "CountryID" => 10,
                    "DOB" => "08181988",
                    "LicExpiration" => "08182053",
                    "MotorcycleLic" => 1,
                    "Gender" => "M",
                    "Ethnicity" => "Caucasian",
                    "PreferredLanguage" => 0,
                    "Email" => "johnsmith@gmail.com",
                    "Phone" => "9286341111",
                    "Surveys" => [
                        [
                            "CustomerID" => 12345,
                            "SurveyID" => 202,
                            "SurveyQuestions" => [
                                [
                                    "QuestionID" => 123,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 334,
                                            "AnswerValue" => "Yes"
                                        ]
                                    ]
                                ],
                                [
                                    "QuestionID" => 124,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 337,
                                            "AnswerValue" => "johnsmith@gmail.com"
                                        ],
                                        [
                                            "AnswerID" => 338,
                                            "AnswerValue" => "9286341111"
                                        ]
                                    ]
                                ],
                                [
                                    "QuestionID" => 125,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 339,
                                            "AnswerValue" => "Harley Davidson"
                                        ],
                                        [
                                            "AnswerID" => 341,
                                            "AnswerValue" => "Kawasaki"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "CustomerID" => 12345,
                            "SurveyID" => 201,
                            "SurveyQuestions" => [
                                [
                                    "QuestionID" => 123,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 334,
                                            "AnswerValue" => "Yes"
                                        ]
                                    ]
                                ],
                                [
                                    "QuestionID" => 124,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 337,
                                            "AnswerValue" => "johnsmith@gmail.com"
                                        ],
                                        [
                                            "AnswerID" => 338,
                                            "AnswerValue" => "9286341111"
                                        ]
                                    ]
                                ],
                                [
                                    "QuestionID" => 125,
                                    "SelectedAnswers" => [
                                        [
                                            "AnswerID" => 339,
                                            "AnswerValue" => "Harley Davidson"
                                        ],
                                        [
                                            "AnswerID" => 341,
                                            "AnswerValue" => "Kawasaki"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        break;

    default:
        return response("Invalid API method request.", 400)->header('Content-Type', 'text/plain');
        break;
}

return response()->json(['Message' => 'Success']);
    }
}
