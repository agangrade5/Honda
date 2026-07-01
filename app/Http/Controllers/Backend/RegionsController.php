<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ReportRegion;
use App\Http\Requests\Backend\RegionRequest;

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
    public function store(RegionRequest $request)
    {
        ReportRegion::create([
            'regionname' => $request->input('region_name'),
            'clientid' => auth()->user()?->clientid ?? 1,
        ]);

        return redirect()->back()->with('msg', 'The Region has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegionRequest $request, $id)
    {
        $region = ReportRegion::findOrFail($id);
        $region->update([
            'regionname' => $request->input('region_name'),
        ]);

        return redirect()->back()->with('msg', 'The Region has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegionRequest $request, $id)
    {
        $region = ReportRegion::findOrFail($id);
        $region->delete();

        return redirect()->back()->with('msg', 'The Region has been deleted successfully');
    }
}
