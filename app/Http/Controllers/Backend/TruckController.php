<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TruckRequest;
use App\Models\Truck;
use App\Models\Vehicle;
use App\Models\BtSet;
use App\Models\VehicleGroup;
use App\Models\HondaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trucks = Truck::leftJoin('btsets', 'trucks.bt_setid', '=', 'btsets.btset_id')
            ->select([
                'trucks.truckid as TruckID',
                'trucks.truckname as TruckName',
                'trucks.bt_setid as BTSetID',
                'btsets.btset_name as BTSetName',
            ])
            ->get();

        foreach ($trucks as $truck) {
            $truck->TruckInventory = Vehicle::where('truckid', $truck->TruckID)
                ->pluck('vehicleid')
                ->toArray();
        }

        $inventory = Vehicle::select('vehicleid as VehicleID', 'vehiclenickname as VehicleNickName')->get();
        $btsets = BtSet::select('btset_id as BTSetID', 'btset_name as BTSetName')->get();

        return view('backend.trucks.index', [
            'title' => 'Manage Trucks',
            'trucks' => $trucks,
            'inventory' => $inventory,
            'btsets' => $btsets,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TruckRequest $request)
    {
        $truck = Truck::create([
            'truckname' => $request->input('TruckName'),
            'bt_setid' => $request->input('SetID'),
            'clientid' => auth()->user()?->clientid ?? 1,
        ]);

        if ($request->has('TruckInventory')) {
            Vehicle::whereIn('vehicleid', $request->input('TruckInventory'))
                ->update(['truckid' => $truck->truckid]);
        }

        return redirect()->back()->with(['msg' => 'The Truck has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TruckRequest $request, string $id)
    {
        $truck = Truck::findOrFail($id);
        $truck->update([
            'truckname' => $request->input('TruckName'),
            'bt_setid' => $request->input('SetID'),
        ]);

        // Reset existing vehicle associations for this truck
        Vehicle::where('truckid', $truck->truckid)->update(['truckid' => null]);

        // Associate newly selected vehicles
        if ($request->has('TruckInventory')) {
            Vehicle::whereIn('vehicleid', $request->input('TruckInventory'))
                ->update(['truckid' => $truck->truckid]);
        }

        return redirect()->back()->with(['msg' => 'The Truck has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TruckRequest $request, string $id)
    {
        // Disassociate vehicles first
        Vehicle::where('truckid', $id)->update(['truckid' => null]);

        // Delete truck
        $truck = Truck::findOrFail($id);
        $truck->delete();

        return redirect()->back()->with(['msg' => 'The Truck has been deleted successfully', 'status' => 'success']);
    }

    /**
     * Handle AJAX file upload and parsing for vehicle import.
     */
    public function import(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'upload') {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            // Create temporary uploads directory in public disk if it doesn't exist
            if (!Storage::disk('public')->exists('uploads')) {
                Storage::disk('public')->makeDirectory('uploads');
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');

            return response($path); // Plain text return as expected by frontend
        }

        if ($action === 'uploadCOV') {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            $file = $request->file('file');
            $filePath = $file->getRealPath();

            try {
                $spreadsheet = IOFactory::load($filePath);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to parse Excel file: ' . $e->getMessage()], 400);
            }

            $covs = [];
            $j = 0;
            foreach ($sheetData as $row) {
                if ($j > 0) {
                    $val = trim($row['A'] ?? '');
                    if (!empty($val)) {
                        $covs[] = $val;
                    }
                }
                $j++;
            }

            $vehicleIds = Vehicle::whereIn('cov', $covs)
                ->pluck('vehicleid')
                ->toArray();

            return response()->json(['data' => $vehicleIds]);
        }

        if ($action === 'read') {
            $fileName = $request->input('fileName');
            $truckId = $request->input('truckId');

            if (empty($fileName) || !Storage::disk('public')->exists($fileName)) {
                return response()->json(['error' => 'Invalid file path'], 400);
            }

            $filePath = Storage::disk('public')->path($fileName);

            try {
                $spreadsheet = IOFactory::load($filePath);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to parse Excel file: ' . $e->getMessage()], 400);
            }

            $headers = $sheetData[1] ?? [];
            $indexCovNumber = '';
            $indexNickname = '';
            $indexLicensePlate = '';
            $indexVIN = '';
            $indexBikeFamilyName = '';
            $indexBikeModelName = '';
            $indexBikeColor = '';
            $indexVehicleType = '';

            foreach ($headers as $index => $headerName) {
                $headerName = trim($headerName);
                if ($headerName == "Plate #") $indexLicensePlate = $index;
                elseif ($headerName == "COV #") $indexCovNumber = $index;
                elseif ($headerName == "Nickname") $indexNickname = $index;
                elseif ($headerName == "VIN") $indexVIN = $index;
                elseif ($headerName == "Model Family") $indexBikeFamilyName = $index;
                elseif ($headerName == "Model Name") $indexBikeModelName = $index;
                elseif ($headerName == "Color") $indexBikeColor = $index;
                elseif ($headerName == "Demo or Display") $indexVehicleType = $index;
            }

            $skippedCovs = [];
            $insertedCount = 0;
            $j = 0;

            foreach ($sheetData as $key => $row) {
                if ($j === 0) {
                    $j++;
                    continue; // Skip header row
                }

                $cov = trim($row[$indexCovNumber] ?? '');
                if (empty($cov)) {
                    $j++;
                    continue;
                }

                // Check if vehicle exists
                $vehicleExists = Vehicle::where('cov', $cov)->exists();
                if ($vehicleExists) {
                    $skippedCovs[] = $cov;
                    $j++;
                    continue;
                }

                // Get or create Group
                $groupName = trim($row[$indexBikeFamilyName] ?? 'General');
                $group = VehicleGroup::firstOrCreate([
                    'groupname' => $groupName,
                    'clientid' => auth()->user()?->clientid ?? 1
                ]);

                // Get or create Model
                $modelName = trim($row[$indexBikeModelName] ?? 'Default');
                $model = HondaModel::firstOrCreate([
                    'modelname' => $modelName,
                    'groupid' => $group->groupid,
                    'clientid' => auth()->user()?->clientid ?? 1
                ]);

                // Create Vehicle
                Vehicle::create([
                    'cov' => $cov,
                    'vehiclenickname' => trim($row[$indexNickname] ?? ''),
                    'vehiclecolor' => trim($row[$indexBikeColor] ?? ''),
                    'vehiclelicplate' => trim($row[$indexLicensePlate] ?? ''),
                    'vehiclevin' => trim($row[$indexVIN] ?? ''),
                    'vehicletype' => strtolower(trim($row[$indexVehicleType] ?? 'demo')),
                    'groupid' => $group->groupid,
                    'modelid' => $model->modelid,
                    'truckid' => !empty($truckId) && $truckId != 0 ? $truckId : null,
                    'clientid' => auth()->user()?->clientid ?? 1,
                    'recordstatus' => 1,
                    'archive' => 0,
                ]);

                $insertedCount++;
                $j++;
            }

            // Cleanup imported Excel file
            Storage::disk('public')->delete($fileName);

            // Set session message matching exact format expected by view
            $msg = 'The file has been loaded successfully...<br>';
            foreach ($skippedCovs as $skipCov) {
                $msg .= 'Bike import skipped - ' . $skipCov . ' already exists<br>';
            }
            $msg .= $insertedCount . ' vehicles created successfully';
            session()->flash('msg', $msg);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Invalid action'], 400);
    }
}
