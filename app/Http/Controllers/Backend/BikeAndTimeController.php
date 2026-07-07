<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BtModel;
use App\Models\BtSet;
use Illuminate\Http\Request;

class BikeAndTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $btSets = BtSet::all();

        return view('backend.bikes-and-times.index', [
            'title' => 'Manage Bikes and Times',
            'btSets' => $btSets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'BTSetName' => 'required|string|max:255',
        ]);

        BtSet::create([
            'btset_name' => $request->input('BTSetName')
        ]);

        return redirect()->back()->with(['msg' => 'The Set has been created successfully', 'status' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $btSet = BtSet::findOrFail($id);
        $btModels = BtModel::where('bt_setid', $id)->get();

        return view('backend.bikes-and-times.edit', [
            'title' => 'Manage Models',
            'btSet' => $btSet,
            'btModels' => $btModels
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'BTSetName' => 'required|string|max:255',
        ]);

        $btSet = BtSet::findOrFail($id);
        $btSet->update([
            'btset_name' => $request->input('BTSetName')
        ]);

        return redirect()->back()->with(['msg' => 'The Set Name has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $btSet = BtSet::findOrFail($id);
        
        // Delete all associated models
        BtModel::where('bt_setid', $id)->delete();
        $btSet->delete();

        return redirect()->back()->with(['msg' => 'The Set has been deleted successfully', 'status' => 'success']);
    }

    /**
     * Add a model to the specific set.
     */
    public function addModel(Request $request, $id)
    {
        $request->validate([
            'BTModelName' => 'required|string|max:255',
            'BTPosition' => 'required|integer',
            'BTQty' => 'required|integer',
        ]);

        $timesInput = $request->input('TimeAddValue', '');
        $timesArray = array_filter(explode('#$$#', $timesInput));
        $timesJson = json_encode(array_values($timesArray));

        BtModel::create([
            'bt_modelname' => $request->input('BTModelName'),
            'bt_qty' => (int)$request->input('BTQty'),
            'bt_times' => $timesJson,
            'bt_setid' => $id,
            'bt_position' => (int)$request->input('BTPosition'),
        ]);

        return redirect()->back()->with(['msg' => 'The Model has been created successfully', 'status' => 'success']);
    }

    /**
     * Edit a specific model's details inside the set.
     */
    public function editModel(Request $request, $id)
    {
        $request->validate([
            'BTModelID' => 'required|integer',
            'BTModelName' => 'required|string|max:255',
            'BTPosition' => 'required|integer',
            'BTQty' => 'required|integer',
        ]);

        $timesInput = $request->input('TimeEditValue', '');
        $timesArray = array_filter(explode('#$$#', $timesInput));
        $timesJson = json_encode(array_values($timesArray));

        $btModel = BtModel::where('bt_modelid', $request->input('BTModelID'))
            ->where('bt_setid', $id)
            ->firstOrFail();

        $btModel->update([
            'bt_modelname' => $request->input('BTModelName'),
            'bt_qty' => (int)$request->input('BTQty'),
            'bt_position' => (int)$request->input('BTPosition'),
            'bt_times' => $timesJson,
        ]);

        return redirect()->back()->with(['msg' => 'The Model has been updated successfully', 'status' => 'success']);
    }

    /**
     * Delete a model from the specific set.
     */
    public function deleteModel(Request $request, $id)
    {
        $request->validate([
            'DeleteBTModelID' => 'required|integer',
        ]);

        BtModel::where('bt_modelid', $request->input('DeleteBTModelID'))
            ->where('bt_setid', $id)
            ->delete();

        return redirect()->back()->with(['msg' => 'The Model has been deleted successfully', 'status' => 'success']);
    }

    /**
     * Apply quick times range to multiple selected models.
     */
    public function applyToAll(Request $request, $id)
    {
        $request->validate([
            'TimeAppliedModelName' => 'required|array',
        ]);

        $timesInput = $request->input('QuickTimeAddValue', '');
        $timesArray = array_filter(explode('#$$#', $timesInput));
        $timesJson = json_encode(array_values($timesArray));

        $appliedModelIds = $request->input('TimeAppliedModelName', []);

        BtModel::where('bt_setid', $id)
            ->whereIn('bt_modelid', $appliedModelIds)
            ->update([
                'bt_times' => $timesJson
            ]);

        return redirect()->back()->with(['msg' => 'The Quick Time has been Applied successfully', 'status' => 'success']);
    }
}
