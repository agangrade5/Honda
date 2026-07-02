<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ModelsRequest;
use App\Models\HondaModel;
use App\Models\VehicleGroup;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hondamodels = HondaModel::select([
                'models.modelid',
                'models.modelname',
                'models.groupid',
                'vehiclegroups.groupname as GroupName'
            ])
            ->leftJoin('vehiclegroups', 'models.groupid', '=', 'vehiclegroups.groupid')
            ->get();

        foreach ($hondamodels as $model) {
            $model->ModelID = $model->modelid;
            $model->ModelName = $model->modelname;
            $model->GroupID = $model->groupid;
            // GroupName is set by select
        }

        $models = (object)[
            'Success' => 1,
            'Models' => $hondamodels,
        ];

        $groups = (object)[
            'Success' => 1,
            'Groups' => VehicleGroup::all()->map(function($g) {
                return (object)[
                    'GroupID' => $g->groupid,
                    'GroupName' => $g->groupname
                ];
            })
        ];

        return view('backend.models.index', [
            'title' => 'Manage Models',
            'models' => $models,
            'groups' => $groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModelsRequest $request)
    {
        $groupId = $request->input('GroupID');
        if ($groupId == 0) {
            $groupId = null;
        }

        HondaModel::create([
            'modelname' => $request->input('ModelName'),
            'groupid' => $groupId,
            'clientid' => '1',
        ]);

        return redirect()->back()->with('msg', 'The Model has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModelsRequest $request, string $id)
    {
        $model = HondaModel::findOrFail($id);
        
        $groupId = $request->input('GroupID');
        if ($groupId == 0) {
            $groupId = null;
        }

        $model->update([
            'modelname' => $request->input('ModelName'),
            'groupid' => $groupId,
        ]);

        return redirect()->back()->with('msg', 'The Model has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelsRequest $request, string $id)
    {
        $modelId = $request->input('DeleteModelID');
        $model = HondaModel::findOrFail($modelId);
        $model->delete();

        return redirect()->back()->with('msg', 'The Model has been deleted successfully');
    }
}
