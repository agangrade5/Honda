<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SmsTemplateRequest;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templatesList = SmsTemplate::all();

        foreach ($templatesList as $t) {
            $t->TemplateID = $t->templateid;
            $t->SmsTemplateSubj = $t->smstemplatesubj;
            $t->SmsSubj = $t->smssubj;
            $t->TemplateBlob = $t->templateblob;
        }

        $smstemplates = (object)[
            'Success' => 1,
            'SMSTemplates' => $templatesList,
        ];

        return view('backend.sms-templates.index', [
            'title' => 'Manage SMS Templates',
            'smstemplates' => $smstemplates,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SmsTemplateRequest $request)
    {
        SmsTemplate::create([
            'smssubj' => $request->input('EmailTemplateSubj'),
            'smstemplatesubj' => $request->input('EmailSubject'),
            'templateblob' => $request->input('TemplateBlob'),
            'clientid' => 1,
        ]);

        return redirect()->back()->with('msg', 'The SMS Template has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SmsTemplateRequest $request, string $id)
    {
        $template = SmsTemplate::findOrFail($id);

        $template->update([
            'smstemplatesubj' => $request->input('EmailTemplateSub'),
            'templateblob' => $request->input('TemplateBlob1'),
        ]);

        return redirect()->back()->with('msg', 'The SMS Template has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmsTemplateRequest $request, string $id)
    {
        $templateId = $request->input('DeleteEmailTemplateID');
        $template = SmsTemplate::findOrFail($templateId);
        $template->delete();

        return redirect()->back()->with('msg', 'The SMS Template has been deleted successfully');
    }
}
