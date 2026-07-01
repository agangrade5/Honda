<?php

namespace App\Http\Requests\Backend;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeName = $this->route() ? $this->route()->getName() : '';

        if ($routeName === 'manage-events.destroy') {
            return [
                'DeleteEventID' => ['required', 'exists:events,eventid', new NoScripts()],
            ];
        }

        $rules = [
            'EventName' => ['required', 'string', 'max:255', new NoScripts()],
            'EventCountry' => ['required', 'exists:countries,countryid', new NoScripts()],
            'EventStartDate' => ['required', new NoScripts()],
            'EventEndDate' => ['required', new NoScripts()],
            'EventWelcomeEmail' => ['nullable', new NoScripts()],
            'EventScheduledEmail' => ['nullable', new NoScripts()],
            'EventTyEmail' => ['nullable', new NoScripts()],
            'EventPrEmail' => ['nullable', new NoScripts()],
            'EventSalesEmail' => ['nullable', new NoScripts()],
            'EventCampaignCode' => ['nullable', new NoScripts()],
            'EventWebsite' => ['nullable', new NoScripts()],
            'DealerID' => ['nullable', 'array'],
            'TruckID' => ['nullable', 'array'],
            'UserID' => ['nullable', 'array'],
            'EventDemoPassengerWaiver' => ['nullable', new NoScripts()],
            'TrikePassengerWaiver' => ['nullable', new NoScripts()],
            'EventPRSurvey' => ['nullable', new NoScripts()],
            'EventDemo' => ['nullable', new NoScripts()],
            'EventLeadGen' => ['nullable', new NoScripts()],
            'EnableSms' => ['nullable', new NoScripts()],
            'EventJumpStart' => ['nullable', new MyNumericBooleanRule()],
            'EventPhotoApp' => ['nullable', new NoScripts()],
            'EventPhotoAppEmail' => ['nullable', new NoScripts()],
            'EventJumpStartWaiver' => ['nullable', new NoScripts()],
            'EventJumpStartWaiverUnderAge' => ['nullable', new NoScripts()],
            'EventLeadGenWaiver' => ['nullable', new NoScripts()],
            'EventSmsTemplateId' => ['nullable', new NoScripts()],
            'EventDemoWaiver' => ['nullable', new NoScripts()],
            'TrikeWaiver' => ['nullable', new NoScripts()],
            'EventLiveWireJumpStart' => ['nullable', new NoScripts()],
            'EventLivewireLeadGen' => ['nullable', new NoScripts()],
            'EventLiveWireJumpStartWaiver' => ['nullable', new NoScripts()],
            'EventLiveWireJumpStartUnderAgeWaiver' => ['nullable', new NoScripts()],
            'EventLiveWireLeadGenWaiver' => ['nullable', new NoScripts()],
            'EventDemoPassengerWaiver2' => ['nullable', new NoScripts()],
            'EventGuardianWaiver' => ['nullable', new NoScripts()],
            'EventDemoWaiver2' => ['nullable', new NoScripts()],
            'leadgensurvey' => ['nullable', new NoScripts()],
            'demosurvey' => ['nullable', new NoScripts()],
            'postridesurvey' => ['nullable', new NoScripts()],
            'jumpstartsurvey' => ['nullable', new NoScripts()],
            'registrationsuccessfulemailtemplate' => ['nullable', new NoScripts()],
            'waitlisttemplateemailtemplate' => ['nullable', new NoScripts()],
            'additionaldetails' => ['nullable', new NoScripts()],
            'remindertemplateemailtemplate' => ['nullable', new NoScripts()],
            'remindertemplate2emailtemplate' => ['nullable', new NoScripts()],
            'EventRegistrationDeadlinePSTTemp1' => ['nullable', new NoScripts()],
            'EventReminderTemp2' => ['required_if:alloweventpreregistrations,1', new NoScripts()],
            'EventReminderTemp1' => ['required_if:alloweventpreregistrations,1', new NoScripts()],
            'alloweventpreregistrations' => ['nullable', new NoScripts()],
            'registrationsurveyid' => ['nullable', new NoScripts()],
            'EventPreRegistrationEmailQty' => ['nullable', 'integer', new NoScripts()],
        ];

        if ($routeName === 'manage-events.update') {
            $rules['EventID'] = ['required', 'exists:events,eventid', new NoScripts()];
        }

        if ($this->isMethod('get') || $routeName === 'manage-events.index' || empty($routeName)) {
            $rules['EventID'] = ['nullable', 'exists:events,eventid', new NoScripts()];
            $rules['DeleteEventID'] = ['nullable', 'exists:events,eventid', new NoScripts()];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'EventName.required' => 'The Event Name is required.',
            'EventCountry.required' => 'The Event Type (Country) is required.',
            'EventStartDate.required' => 'The Event Start Date is required.',
            'EventEndDate.required' => 'The Event End Date is required.',
            'EventID.required' => 'The Event ID is required.',
            'EventID.exists' => 'The selected Event ID is invalid.',
            'DeleteEventID.required' => 'The Event ID to delete is required.',
            'DeleteEventID.exists' => 'The selected Event to delete is invalid.',
            'EventReminderTemp1.required_if' => 'The Reminder 1 field is required when pre-registration is allowed.',
            'EventReminderTemp2.required_if' => 'The Reminder 2 field is required when pre-registration is allowed.',
        ];
    }
}

class MyNumericBooleanRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Acceptable boolean/numeric-boolean values
    }
}
