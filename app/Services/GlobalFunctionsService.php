<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class GlobalFunctionsService
{
    public function dataParser($providedarray, $key)
    {
        if (isset($providedarray->Data) && property_exists($providedarray->Data, $key)) {
            return $providedarray->Data->$key;
        }
        return '';
    }

    public function connectionDataParser($providedarray, $key)
    {
        if (isset($providedarray->ConnectionData) && property_exists($providedarray->ConnectionData, $key)) {
            return $providedarray->ConnectionData->$key;
        }
        return '';
    }

    public function createNewSystemLog($fullData)
    {
        DB::table('syslog')->insert([
            'logdate' => date("Y-m-d H:i:s"),
            'logmsg' => $fullData
        ]);
    }

    public function createShadowLog($fullData)
    {
        DB::table('proxyshadow')->insert([
            'logdate' => date("Y-m-d H:i:s"),
            'logmsg' => $fullData
        ]);
    }

    public function isConnected()
    {
        return false;
    }

    public function authenticateAPIKey($apiKey)
    {
        $result = DB::table('apikeys')
            ->select('apiallowaccess', 'clientid')
            ->where('apikey', $apiKey)
            ->first();

        if ($result && $result->apiallowaccess == 1) {
            return [
                'Authorized' => true,
                'ClientID' => $result->clientid
            ];
        }

        return [
            'Authorized' => false
        ];
    }

    public function adminPostParser($providedarray, $key)
    {
        if (isset($providedarray->AdminPost) && property_exists($providedarray->AdminPost, $key)) {
            return $providedarray->AdminPost->$key;
        }
        return '';
    }

    public function generateNewCardNumber()
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

    public function parseSurveyBlob($providedarray, $key)
    {
        if (property_exists((object)$providedarray, $key)) {
            return $providedarray->$key;
        }
        return '';
    }

    public function parsePost($providedarray, $key)
    {
        if (property_exists((object)$providedarray, $key)) {
            return $providedarray->$key;
        }
        return '';
    }

    public function dateDiff($time1, $time2, $precision = 6)
    {
        if (!is_int($time1)) {
            $time1 = strtotime($time1);
        }
        if (!is_int($time2)) {
            $time2 = strtotime($time2);
        }

        if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
        }

        $intervals = ['year', 'month', 'day', 'hour', 'minute', 'second'];
        $diffs = [];

        foreach ($intervals as $interval) {
            $ttime = strtotime('+1 ' . $interval, $time1);
            $add = 1;
            $looped = 0;
            while ($time2 >= $ttime) {
                $add++;
                $ttime = strtotime("+" . $add . " " . $interval, $time1);
                $looped++;
            }
            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
        }

        $count = 0;
        $times = [];
        foreach ($diffs as $interval => $value) {
            if ($count >= $precision) {
                break;
            }
            if ($value > 0) {
                if ($value != 1) {
                    $interval .= "s";
                }
                $times[] = $value . " " . $interval;
                $count++;
            }
        }
        return implode(", ", $times);
    }

    public function stripInvalidCharacters($string)
    {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    }

    public function serverSendEmail($to, $body, $subject)
    {
        try {
            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP();
            $mail->Host       = 'mail.smtp2go.com';
            $mail->Port       = 2525;
            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rewards@sendmail.ncompasstrac.com';
            $mail->Password   = 'Wdf87d#$#fd87dfER76gDF#R874';
            $mail->SetFrom('rewards@sendmail.ncompasstrac.com', 'Yamaha Motor Corporation');
            $mail->Subject    = $subject;
            $mail->MsgHTML($body);
            $mail->AddAddress($to);
            $mail->send();
        } catch (Exception $e) {
            \Log::error('Mail sending failed: ' . $mail->ErrorInfo);
        }
    }

    public function sendPhoto($eventID, $cardNumber, $photoURL)
    {
        $getCustomerEmail = DB::table('customers')->where('cardnumber', $cardNumber)->value('custemail');
        $getEmailTemplate = DB::table('events')->where('eventid', $eventID)->first();

        if ($getEmailTemplate && !is_null($getEmailTemplate->eventpremail)) {
            $getEmailInfo = DB::table('emailtemplates')->where('templateid', $getEmailTemplate->eventpremail)->first();
            if ($getEmailInfo) {
                $emailSrc = $getEmailInfo->templateblob;
                $emailSrc = str_replace('~PRSURVEYPHOTO~', $photoURL, $emailSrc);

                $this->serverSendEmail($getCustomerEmail, $emailSrc, $getEmailInfo->emailtemplatesubj);
            }
        }
    }

    public function sendPhotoApp($eventID, $cardNumber, $photoURL)
    {
        $getCustomerEmail = DB::table('customers')->where('cardnumber', $cardNumber)->value('custemail');
        $getEmailTemplate = DB::table('events')->where('eventid', $eventID)->first();

        if ($getEmailTemplate && !is_null($getEmailTemplate->photoappemail)) {
            $getEmailInfo = DB::table('emailtemplates')->where('templateid', $getEmailTemplate->photoappemail)->first();
            if ($getEmailInfo) {
                $emailSrc = $getEmailInfo->templateblob;
                $emailSrc = str_replace('~PRSURVEYPHOTO~', $photoURL, $emailSrc);

                $this->serverSendEmail($getCustomerEmail, $emailSrc, $getEmailInfo->emailtemplatesubj);
            }
        }
    }
}
