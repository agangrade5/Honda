<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerTrans;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\NewRegistrationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PreRegEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = null;
        $endDate = null;

        $query = CustomerTrans::query()
            ->join('customers', 'customertrans.custid', '=', 'customers.custid')
            ->where('customers.custemail', '!=', '')
            ->where('customertrans.transtype', 999);

        if ($request->filled('NHRAstartDate') && $request->filled('NHRAendDate')) {
            try {
                $start = Carbon::createFromFormat('m/d/Y', $request->input('NHRAstartDate'))->startOfDay()->format('Y-m-d H:i:s');
                $end = Carbon::createFromFormat('m/d/Y', $request->input('NHRAendDate'))->endOfDay()->format('Y-m-d H:i:s');
                $query->whereBetween('customertrans.transdate', [$start, $end]);
                
                $startDate = $request->input('NHRAstartDate');
                $endDate = $request->input('NHRAendDate');
            } catch (\Exception $e) {
                // Ignore parsing errors
            }
        }

        $customers = $query->orderBy('customertrans.transdate', 'desc')
            ->select('customers.custid', 'customers.custfname', 'customers.custlname', 'customers.custemail')
            ->get();

        return view('backend.pre-reg-emails.index', [
            'title' => 'Manage Pre-Reg Emails',
            'customers' => $customers,
            'NHRAstartDate' => $startDate ?: date('m/01/Y'),
            'NHRAendDate' => $endDate ?: date('m/30/Y')
        ]);
    }

    /**
     * Send email to custom registration pass.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'customerID' => 'required',
            'customerEmail' => 'required|email'
        ]);

        $customerId = $request->input('customerID');
        $customerEmail = $request->input('customerEmail');

        $trans = CustomerTrans::where('custid', $customerId)->orderBy('transdate', 'desc')->first();
        $customer = Customer::where('custid', $customerId)->first();

        if (!$trans || !$customer) {
            return redirect()->back()->with('msg', 'Customer or Transaction not found.');
        }

        $appLabel = "Digital Pass";
        $eventId = $trans->eventid;
        $cardNumber = $customer->cardnumber;

        if ($eventId == 0 || empty($eventId)) {
            return redirect()->back()->with('msg', 'This customer does not have an Event ID.');
        }

        $event = Event::where('eventid', $eventId)->first();
        if (!$event) {
            return redirect()->back()->with('msg', 'This customer does not have an Event ID.');
        }

        $emailTemplateId = $event->registrationsuccessfulemailtemplate;
        $eventName = $event->eventname;

        if (!$emailTemplateId) {
            return redirect()->back()->with('msg', 'No registration success email template configured for this event.');
        }

        $template = EmailTemplate::where('templateid', $emailTemplateId)->first();
        if (!$template) {
            return redirect()->back()->with('msg', 'Email template not found.');
        }

        $emailSubject = $template->emailsubj;
        $emailBody = $template->templateblob;

        $emailBody = str_replace("~FIRST_NAME~", $customer->custfname, $emailBody);
        $emailBody = str_replace("~LAST_NAME~", $customer->custlname, $emailBody);

        $customerName = $customer->custfname . " " . $customer->custlname;

        // Barcode Image
        $imgUrl = 'http://qa.ncompassmkt.com/Mobile/Get2DBarCodePDF417.aspx?number=' . $cardNumber . '!NCT-YA';
        $emailBody = str_replace("~BARCODE_IMAGE~", $imgUrl, $emailBody);

        // iOS and Android wallet pass APIs
        $android = "https://honda.kickstartuser.com/API/Wallet/Wallet_UrbanAirship_API.php?cardnumber=" . $cardNumber . '!NCT-HO' . "&devicetype=0&eventname=" . base64_encode($eventName) . "&label=" . base64_encode($appLabel) . "&customerName=" . base64_encode($customerName);

        $ios = "https://honda.kickstartuser.com/API/Wallet/Wallet_UrbanAirship_API.php?cardnumber=" . $cardNumber . '!NCT-HO' . "&devicetype=1&eventname=" . base64_encode($eventName) . "&customerName=" . base64_encode($customerName) . "&label=" . base64_encode($appLabel);

        $androidU = '#';
        $iosU = '#';

        try {
            $androidResponse = Http::timeout(5)->get($android);
            if ($androidResponse->successful()) {
                $getUrl1 = json_decode($androidResponse->body());
                if (isset($getUrl1->passurl)) {
                    $androidU = $getUrl1->passurl;
                }
            }
        } catch (\Exception $e) {
            // Ignore UrbanAirship connection issues
        }

        try {
            $iosResponse = Http::timeout(5)->get($ios);
            if ($iosResponse->successful()) {
                $getUrl2 = json_decode($iosResponse->body());
                if (isset($getUrl2->passurl)) {
                    $iosU = $getUrl2->passurl;
                }
            }
        } catch (\Exception $e) {
            // Ignore UrbanAirship connection issues
        }

        $emailBody = str_replace("~Android_Wallet_URL~", $androidU, $emailBody);
        $emailBody = str_replace("~iOS_WALLET_URL~", $iosU, $emailBody);

        try {
            Mail::html($emailBody, function ($message) use ($customerEmail, $emailSubject) {
                $message->to($customerEmail)
                        ->from('rewards@sendmail.ncompasstrac.com', 'Honda Motor Company')
                        ->subject($emailSubject);
            });

            NewRegistrationEmail::where('customer_id', $customerId)
                ->where('event_id', $eventId)
                ->update(['is_sent' => 1]);

            return redirect()->back()->with('msg', 'Email has send successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('msg', 'Error sending email: ' . $e->getMessage());
        }
    }
}
