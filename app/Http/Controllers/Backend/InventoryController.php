<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\InventoryRequest;
use App\Models\Vehicle;
use App\Models\Truck;
use App\Models\HondaModel;
use App\Models\VehicleGroup;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedArchive = request()->input('archive', 0);

        $vehicles = Vehicle::leftJoin('vehiclegroups', 'vehicles.groupid', '=', 'vehiclegroups.groupid')
            ->leftJoin('trucks', 'vehicles.truckid', '=', 'trucks.truckid')
            ->leftJoin('models', 'vehicles.modelid', '=', 'models.modelid')
            ->select([
                'vehicles.vehicleid as VehicleID',
                'vehicles.vehiclevin as VehicleVIN',
                'vehicles.vehiclenickname as VehicleNickName',
                'vehicles.groupid as VehicleGroupID',
                'vehicles.modelid as ModelID',
                'vehicles.truckid as VehicleTruckID',
                'vehicles.vehiclelicplate as VehicleLicPlate',
                'vehicles.vehiclecolor as VehicleColor',
                'vehicles.cov as VehicleCOV',
                'vehicles.vehicletype as VehicleType',
                'vehicles.archive as VehicleArchive',
                'vehiclegroups.groupname as VehicleGroup',
                'trucks.truckname as TruckName',
                'models.modelname as ModelName',
            ])
            ->where('vehicles.archive', $selectedArchive)
            ->get();

        $groups = VehicleGroup::all();
        $trucks = Truck::all();
        $models = HondaModel::all();

        return view('backend.manage-inventory.index', [
            'title' => 'Manage Inventory',
            'vehicles' => $vehicles,
            'groups' => $groups,
            'trucks' => $trucks,
            'models' => $models,
            'selectedArchive' => $selectedArchive,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryRequest $request)
    {
        Vehicle::create([
            'vehiclenickname' => $request->input('VehicleNickName'),
            'groupid' => $request->input('VehicleModel'),
            'vehiclecolor' => $request->input('VehicleColor'),
            'truckid' => $request->input('VehicleTruckID'),
            'vehiclelicplate' => $request->input('VehicleLicPlate'),
            'vehiclevin' => $request->input('VehicleVIN'),
            'cov' => $request->input('VehicleCOV'),
            'modelid' => $request->input('ModelID'),
            'vehicletype' => $request->input('VehicleType'),
            'archive' => $request->input('EventArchive'),
            'clientid' => auth()->user()?->clientid ?? 1,
            'recordstatus' => 1,
        ]);

        return redirect()->back()->with('msg', 'The Vehicle has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryRequest $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update([
            'vehiclenickname' => $request->input('VehicleNickName'),
            'groupid' => $request->input('VehicleModel'),
            'vehiclecolor' => $request->input('VehicleColor'),
            'truckid' => $request->input('VehicleTruckID'),
            'vehiclelicplate' => $request->input('VehicleLicPlate'),
            'vehiclevin' => $request->input('VehicleVIN'),
            'cov' => $request->input('VehicleCOV'),
            'modelid' => $request->input('ModelID'),
            'vehicletype' => $request->input('VehicleType'),
            'archive' => $request->input('EventArchive'),
        ]);

        return redirect()->back()->with('msg', 'The Vehicle has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryRequest $request, string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->back()->with('msg', 'The Vehicle has been deleted successfully');
    }
}
