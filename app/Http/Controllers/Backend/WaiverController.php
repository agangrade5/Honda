<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WaiverRequest;
use App\Models\Legal;
use Illuminate\Http\Request;

class WaiverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $legalWaivers = Legal::all();

        foreach ($legalWaivers as $waiver) {
            $waiver->WaiverID = $waiver->legalid;
            $waiver->WaiverName = $waiver->legalname;
            $waiver->WaiverHTML = $waiver->legalhtml;
        }

        $waivers = (object)[
            'Success' => 1,
            'Waivers' => $legalWaivers,
        ];

        return view('backend.waivers.index', [
            'title' => 'Manage Waivers',
            'waivers' => $waivers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WaiverRequest $request)
    {
        Legal::create([
            'legalname' => $request->input('WaiverName'),
            'legalhtml' => $request->input('WaiverHTML'),
        ]);

        return redirect()->back()->with(['msg' => 'The Waiver has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WaiverRequest $request, string $id)
    {
        // WaiverID is expected as a delimited string ID!$!Name
        $parts = explode('!$!', $request->input('WaiverID'));
        $legalId = $parts[0] ?? $id;

        $waiver = Legal::findOrFail($legalId);
        $waiver->update([
            'legalhtml' => $request->input('WaiverHTML1'),
        ]);

        return redirect()->back()->with(['msg' => 'The Waiver has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaiverRequest $request, string $id)
    {
        $legalId = $request->input('DeleteWaiverID');
        $waiver = Legal::findOrFail($legalId);
        $waiver->delete();

        return redirect()->back()->with(['msg' => 'The Waiver has been deleted successfully', 'status' => 'success']);
    }
}
