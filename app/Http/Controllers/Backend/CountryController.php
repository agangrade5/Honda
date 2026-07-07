<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CountryRequest;
use App\Models\Country;
use App\Models\State;
use App\Models\ReportRegion;
use Illuminate\Http\Request;
use App\Rules\NoScripts;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::leftJoin('reportregions', 'countries.regionid', '=', 'reportregions.regionid')
            ->select([
                'countries.countryid as CountryID',
                'countries.countryname as CountryName',
                'countries.countrycode as CountryCode',
                'countries.regionid as RegionID',
                'reportregions.regionname as Region',
            ])
            ->get();

        foreach ($countries as $country) {
            $country->StateName = State::where('countryid', $country->CountryID)->get();
        }

        $regions = ReportRegion::all();

        return view('backend.country.index', [
            'title' => 'Manage Countries',
            'countries' => $countries,
            'regions' => $regions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CountryRequest $request)
    {
        Country::create([
            'countryname' => $request->input('CountryName'),
            'regionid' => $request->input('RegionID'),
            'countrycode' => $request->input('CountryCode'),
            'recordstatus' => 1,
        ]);

        return redirect()->back()->with(['msg' => 'The Country has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CountryRequest $request, string $id)
    {
        $country = Country::findOrFail($id);
        $country->update([
            'countryname' => $request->input('CountryName'),
            'regionid' => $request->input('RegionID'),
            'countrycode' => $request->input('CountryCode'),
        ]);

        return redirect()->back()->with(['msg' => 'The Country has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CountryRequest $request, string $id)
    {
        // Delete all child states first
        State::where('countryid', $id)->delete();

        // Delete the country
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()->back()->with(['msg' => 'The Country has been deleted successfully', 'status' => 'success']);
    }

    /**
     * AJAX Action: Add state to a country.
     */
    public function addState(Request $request)
    {
        $request->validate([
            'CountryID' => ['required', 'integer', 'exists:countries,countryid', new NoScripts()],
            'StateName' => ['required', 'string', 'max:100', new NoScripts()],
        ]);

        $state = State::create([
            'countryid' => $request->input('CountryID'),
            'statename' => $request->input('StateName'),
        ]);

        return response($state->stateid);
    }

    /**
     * AJAX Action: Edit state name.
     */
    public function editState(Request $request)
    {
        $request->validate([
            'StateIDEdit' => ['required', 'integer', 'exists:states,stateid', new NoScripts()],
            'StateName1' => ['required', 'string', 'max:100', new NoScripts()],
        ]);

        $state = State::findOrFail($request->input('StateIDEdit'));
        $state->update([
            'statename' => $request->input('StateName1'),
        ]);

        return response($state->statename);
    }

    /**
     * AJAX Action: Delete state.
     */
    public function deleteState(Request $request)
    {
        $request->validate([
            'StateIDDelete' => ['required', 'integer', 'exists:states,stateid', new NoScripts()],
        ]);

        $state = State::findOrFail($request->input('StateIDDelete'));
        $state->delete();

        return response('');
    }
}
