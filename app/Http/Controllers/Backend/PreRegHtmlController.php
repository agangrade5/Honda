<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PreregisterHtml;
use Illuminate\Http\Request;

class PreRegHtmlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('eventid', 'desc')->get();

        return view('backend.pre-reg-html.index', [
            'title' => 'Manage Pre-Reg HTML Templates',
            'events' => $events
        ]);
    }

    /**
     * Store or update the pre-registration HTML templates for the selected event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'EventID' => 'required',
        ]);

        PreregisterHtml::updateOrCreate(
            ['eventid' => $request->input('EventID')],
            [
                'quantityform' => $request->input('EventHTML1') ?? '',
                'infoform' => $request->input('EventHTML2') ?? '',
                'completeform' => $request->input('EventHTML3') ?? '',
                'htmlcontent' => $request->input('EventHTML4') ?? '',
                'errorhtml' => $request->input('EventHTML5') ?? '',
            ]
        );

        return redirect()->back()->with('msg', 'The pre-registration HTML has been updated successfully');
    }

    /**
     * Fetch pre-registration HTML template data for the selected event.
     */
    public function select(Request $request)
    {
        $eventId = $request->input('EventID');
        $prereg = PreregisterHtml::where('eventid', $eventId)->first();

        if ($prereg) {
            return response()->json([
                'quantityform' => $prereg->quantityform,
                'infoform' => $prereg->infoform,
                'completeform' => $prereg->completeform,
                'htmlcontent' => $prereg->htmlcontent,
                'errorhtml' => $prereg->errorhtml,
            ]);
        }

        return response()->json([
            'quantityform' => '',
            'infoform' => '',
            'completeform' => '',
            'htmlcontent' => '',
            'errorhtml' => '',
        ]);
    }
}
