<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\EmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templatesList = EmailTemplate::all();

        foreach ($templatesList as $t) {
            $t->TemplateID = $t->templateid;
            $t->EmailTemplateSubj = $t->emailtemplatesubj;
            $t->EmailSubj = $t->emailsubj;
            $t->TemplateBlob = $t->templateblob;
        }

        $emailtemplates = (object)[
            'Success' => 1,
            'EmailTemplates' => $templatesList,
        ];

        return view('backend.email-templates.index', [
            'title' => 'Manage Email Templates',
            'emailtemplates' => $emailtemplates,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTemplateRequest $request)
    {
        EmailTemplate::create([
            'emailsubj' => $request->input('EmailTemplateSubj'),
            'emailtemplatesubj' => $request->input('EmailSubject'),
            'templateblob' => $request->input('TemplateBlob'),
            'clientid' => 1,
        ]);

        return redirect()->back()->with('msg', 'The Email Template has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTemplateRequest $request, string $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $template->update([
            'emailtemplatesubj' => $request->input('EmailTemplateSub'),
            'templateblob' => $request->input('TemplateBlob1'),
        ]);

        return redirect()->back()->with('msg', 'The Email Template has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplateRequest $request, string $id)
    {
        $templateId = $request->input('DeleteEmailTemplateID');
        $template = EmailTemplate::findOrFail($templateId);
        $template->delete();

        return redirect()->back()->with('msg', 'The Email Template has been deleted successfully');
    }

    /**
     * Send test email using standard Laravel Mailer.
     */
    public function sendTestEmail(EmailTemplateRequest $request)
    {
        $to = $request->input('EmailSubject');
        $subject = $request->input('EmailTemplateSubject');
        $html = $request->input('template');

        try {
            Mail::html($html, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject);
            });
            return redirect()->back()->with('msg', 'The Test Email has been sent successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('msg', 'Error sending test email: ' . $e->getMessage());
        }
    }
}
