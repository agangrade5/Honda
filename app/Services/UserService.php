<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Submit or update a customer profile.
     * Equivalent to legacy Case 101.
     */
    public function submitCustomerProfile($data, $eventID, $clientID, $isCloud)
    {
        $cardNumber = $data['CardNumber'] ?? '';
        $firstName = $data['FirstName'] ?? '';
        $lastName = $data['LastName'] ?? '';
        $addressBlob = $data['AddressBlob'] ?? '';
        $countryID = $data['CountryID'] ?? '';
        $dob = $data['DOB'] ?? '';
        $licExpiration = $data['LicExpiration'] ?? '';
        $motorcycleLic = $data['MotorcycleLic'] ?? 0;
        $gender = $data['Gender'] ?? '';
        $ethnicity = $data['Ethnicity'] ?? '';
        $preferredLanguage = $data['PrefferedLanguage'] ?? '';
        $email = $data['Email'] ?? '';
        $phone = $data['Phone'] ?? '';
        $driversLicense = $data['DriversLicense'] ?? '';
        $rawPDFData = $data['RawBarcode'] ?? '';
        $rawMagStripe = $data['RawMag'] ?? '';
        $optIn = $data['OptIn'] ?? 0;
        $customerRegisteredTime = $data['CustomerRegistrationDate'] ?? date('Y-m-d H:i:s');

        if (strlen($cardNumber) == 0) {
            // Find the next available card number
            $query = DB::table('cards')->where('cardbatch', 8);
            if ($isCloud == false) {
                $query->where('eventid', 0);
            } else {
                $query->whereNull('eventid');
            }
            $nextCard = $query->first();

            if (!$nextCard) {
                return ['Message' => 'Failed: No available cards.'];
            }
            $cardNumber = $nextCard->cardnumber;
        }

        $existingCustomer = DB::table('customers')->where('cardnumber', $cardNumber)->first();

        if ($existingCustomer) {
            // Check for offline upload - old records
            if (strtotime($existingCustomer->custlastupdated) > strtotime($customerRegisteredTime)) {
                return [
                    'Message' => 'Success',
                    'CustomerID' => $existingCustomer->custid,
                    'CardNumber' => $cardNumber
                ];
            }

            // Update existing customer
            DB::table('customers')->where('custid', $existingCustomer->custid)->update([
                'custfname' => $firstName,
                'custlname' => $lastName,
                'custaddress' => $addressBlob,
                'custcountry' => $countryID,
                'custemail' => $email,
                'custphone' => $phone,
                'custgender' => $gender,
                'custbirthday' => $dob,
                'custdriverslicense' => $driversLicense,
                'custethnicity' => $ethnicity,
                'custmotorcyclelic' => $motorcycleLic,
                'custlicexpire' => $licExpiration,
                'custlang' => $preferredLanguage,
                'clientid' => $clientID,
                'custoptin' => $optIn,
                'custlastupdated' => $customerRegisteredTime
            ]);

            // Update raw license data if provided
            if (strlen($rawPDFData) > 0) {
                DB::table('rawlicensedata')->where('custid', $existingCustomer->custid)->update(['rawscan' => $rawPDFData]);
            }
            if (strlen($rawMagStripe) > 0) {
                DB::table('rawlicensedata')->where('custid', $existingCustomer->custid)->update(['rawmagswipe' => $rawMagStripe]);
            }

            return [
                'Message' => 'Success',
                'CustomerID' => $existingCustomer->custid,
                'CardNumber' => $cardNumber
            ];
        } else {
            // Create new customer
            $newCustId = DB::table('customers')->insertGetId([
                'cardnumber' => $cardNumber,
                'custfname' => $firstName,
                'custlname' => $lastName,
                'custaddress' => $addressBlob,
                'custcountry' => $countryID,
                'custemail' => $email,
                'custphone' => $phone,
                'custgender' => $gender,
                'custbirthday' => $dob,
                'custdriverslicense' => $driversLicense,
                'custethnicity' => $ethnicity,
                'custmotorcyclelic' => $motorcycleLic,
                'custlicexpire' => $licExpiration,
                'clientid' => $clientID,
                'custoptin' => $optIn,
                'custlastupdated' => $customerRegisteredTime
            ]);

            // Associate card with event
            DB::table('cards')->where('cardnumber', $cardNumber)->update(['eventid' => $eventID]);

            // Insert raw license data if provided
            if (strlen($rawMagStripe) > 0 && strlen($rawPDFData) > 0) {
                DB::table('rawlicensedata')->insert([
                    'rawmagstripe' => $rawMagStripe,
                    'rawscan' => $rawPDFData,
                    'custid' => $newCustId
                ]);
            } elseif (strlen($rawMagStripe) > 0) {
                DB::table('rawlicensedata')->insert([
                    'rawmagstripe' => $rawMagStripe,
                    'custid' => $newCustId
                ]);
            } elseif (strlen($rawPDFData) > 0) {
                DB::table('rawlicensedata')->insert([
                    'rawscan' => $rawPDFData,
                    'custid' => $newCustId
                ]);
            }

            return [
                'Message' => 'Success',
                'CustomerID' => $newCustId,
                'CardNumber' => $cardNumber
            ];
        }
    }
}
