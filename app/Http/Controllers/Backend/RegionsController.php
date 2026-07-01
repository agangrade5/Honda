<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ReportRegion;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = ReportRegion::all();

        return view('backend.manage-regions.index', [
            'title' => 'Manage Regions',
            'regions' => $regions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'RegionName' => 'required|string|max:100',
        ]);

        ReportRegion::create([
            'regionname' => $request->input('RegionName'),
            'clientid' => auth()->user()?->clientid ?? 1,
        ]);

        return redirect()->back()->with('msg', 'The Region has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'RegionID' => 'required|exists:reportregions,regionid',
            'RegionName' => 'required|string|max:100',
        ]);

        $region = ReportRegion::findOrFail($request->input('RegionID'));
        $region->update([
            'regionname' => $request->input('RegionName'),
        ]);

        return redirect()->back()->with('msg', 'The Region has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'DeleteRegionID' => 'required|exists:reportregions,regionid',
        ]);

        $region = ReportRegion::findOrFail($request->input('DeleteRegionID'));
        $region->delete();

        return redirect()->back()->with('msg', 'The Region has been deleted successfully');
    }
}
