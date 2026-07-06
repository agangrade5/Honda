<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\GroupRequest;
use App\Models\VehicleGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiclegroups = VehicleGroup::all();

        $groups = (object)[
            'Success' => 1,
            'Groups' => $vehiclegroups,
        ];

        return view('backend.groups.index', [
            'title' => 'Manage Groups',
            'groups' => $groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        VehicleGroup::create([
            'groupname' => $request->input('GroupName'),
        ]);

        return redirect()->back()->with(['msg' => 'The Group has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, string $id)
    {
        $group = VehicleGroup::findOrFail($id);
        $group->update([
            'groupname' => $request->input('GroupName'),
        ]);

        return redirect()->back()->with(['msg' => 'The Group has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupRequest $request, string $id)
    {
        $group = VehicleGroup::findOrFail($id);
        $group->delete();

        return redirect()->back()->with(['msg' => 'The Group has been deleted successfully', 'status' => 'success']);
    }
}
