<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\HondaModel;
use App\Models\VehicleGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportVehiclesController extends Controller
{
    /**
     * Display a listing.
     */
    public function index()
    {
        return view('backend.import-vehicles.index', [
            'title' => 'Manage Import Vehicles',
        ]);
    }

    /**
     * Upload vehicle spreadsheet.
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            
            $destDir = public_path('uploads');
            if (!File::exists($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }
            
            $file->move($destDir, $filename);
            
            @chmod($destDir . DIRECTORY_SEPARATOR . $filename, 0755);
            
            return response($filename, 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('Error uploading file', 400);
    }

    /**
     * Delete uploaded file.
     */
    public function deleteFile(Request $request)
    {
        $filename = $request->input('fileName');
        if ($filename) {
            $path = public_path('uploads/' . $filename);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        return response()->json(true);
    }

    /**
     * Parse spreadsheet and import data.
     */
    public function readExcel(Request $request)
    {
        $filename = $request->input('fileName');
        if (!$filename) {
            return response()->json(['Status' => 'Failed', 'Message' => 'No file specified.']);
        }

        $inputFileName = public_path('uploads/' . $filename);
        if (!File::exists($inputFileName)) {
            return response()->json(['Status' => 'Failed', 'Message' => 'File not found.']);
        }

        try {
            $objPHPExcel = IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'Failed', 'Message' => $e->getMessage()]);
        }

        $indexModelYear = "";
        $indexLicensePlate = "";
        $indexLicenseState = "";
        $indexNickname = "";
        $indexVIN = "";
        $indexIsTrike = "";
        $indexBarcode = "";
        $indexBikeFamilyName = "";
        $indexBikeModelName = "";
        $indexBikeColor = "";
        $indexCovNumber = "";
        $indexVehicleType = "";

        $uVehicles = [];
        $uModels = [];
        $uGroups = [];
        $GroupModel = [];
        $j = 0;

        foreach ($sheetData as $key => $value) {
            if ($j == 0) {
                $j++;
                foreach ($value as $index => $headerName) {
                    if ($headerName == "ModelYear") {
                        $indexModelYear = $index;
                    } elseif ($headerName == "Plate #") {
                        $indexLicensePlate = $index;
                    } elseif ($headerName == "COV #") {
                        $indexCovNumber = $index;
                    } elseif ($headerName == "LicenseState") {
                        $indexLicenseState = $index;
                    } elseif ($headerName == "Nickname") {
                        $indexNickname = $index;
                    } elseif ($headerName == "VIN") {
                        $indexVIN = $index;
                    } elseif ($headerName == "IsTrike") {
                        $indexIsTrike = $index;
                    } elseif ($headerName == "Model Family") {
                        $indexBikeFamilyName = $index;
                    } elseif ($headerName == "Model Name") {
                        $indexBikeModelName = $index;
                    } elseif ($headerName == "Barcode") {
                        $indexBarcode = $index;
                    } elseif ($headerName == "Color") {
                        $indexBikeColor = $index;
                    } elseif ($headerName == "Demo or Display") {
                        $indexVehicleType = $index;
                    }
                }
                continue;
            }

            if (isset($value[$indexBikeModelName]) && !empty($value[$indexBikeModelName])) {
                $uModels[] = $value[$indexBikeModelName];
                $uVehicles[$j]['BikeModelName'] = $value[$indexBikeModelName];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
                $GroupModel[$value[$indexBikeModelName]] = trim(preg_replace('/\s+/', ' ', $value[$indexBikeFamilyName]));
            }
            if (isset($value[$indexBikeFamilyName]) && !empty($value[$indexBikeFamilyName])) {
                $uGroups[]  = $value[$indexBikeFamilyName];
                $uVehicles[$j]['BikeFamilyName'] = trim(preg_replace('/\s+/', ' ', $value[$indexBikeFamilyName]));
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexBarcode]) && !empty($value[$indexBarcode])) {
                $uVehicles[$j]['Barcode'] = $value[$indexBarcode];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexModelYear]) && !empty($value[$indexModelYear])) {
                $uVehicles[$j]['ModelYear'] = $value[$indexModelYear];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexLicensePlate]) && !empty($value[$indexLicensePlate])) {
                $uVehicles[$j]['LicensePlate'] = $value[$indexLicensePlate];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexLicenseState]) && !empty($value[$indexLicenseState])) {
                $uVehicles[$j]['LicenseState'] = $value[$indexLicenseState];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexNickname]) && !empty($value[$indexNickname])) {
                $uVehicles[$j]['Nickname'] = $value[$indexNickname];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexVIN]) && !empty($value[$indexVIN])) {
                $uVehicles[$j]['VIN'] = $value[$indexVIN];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexIsTrike]) && !empty($value[$indexIsTrike])) {
                $uVehicles[$j]['IsTrike'] = $value[$indexIsTrike];
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }
            if (isset($value[$indexBikeColor]) && !empty($value[$indexBikeColor])) {
                $uVehicles[$j]['BikeColor'] = trim(preg_replace('/\s+/', ' ', $value[$indexBikeColor]));
                $uVehicles[$j]['COVNumber'] = $value[$indexCovNumber];
                $uVehicles[$j]['VehicleType'] = $value[$indexVehicleType] ?? 'demo';
            }

            $j++;
        }

        $uModels = array_filter(array_unique($uModels));
        $uGroups = array_filter(array_unique($uGroups));
        
        $truckId = $request->input('truckId', null);

        // Perform local database operations
        // 1. Save Groups
        foreach ($uGroups as $groupName) {
            VehicleGroup::firstOrCreate([
                'groupname' => $groupName,
            ], [
                'clientid' => 1,
            ]);
        }

        // 2. Save Models
        foreach ($GroupModel as $modelName => $familyName) {
            $group = VehicleGroup::where('groupname', $familyName)->first();
            $groupId = $group ? $group->groupid : null;

            HondaModel::firstOrCreate([
                'modelname' => $modelName,
            ], [
                'groupid' => $groupId,
                'clientid' => 1,
            ]);
        }

        // 3. Save Vehicles
        $skippedCovs = [];
        $insertedCount = 0;

        foreach ($uVehicles as $vehicleData) {
            if (empty($vehicleData['COVNumber'])) {
                continue;
            }

            $cov = $vehicleData['COVNumber'];
            $exists = Vehicle::where('cov', $cov)->exists();
            if ($exists) {
                $skippedCovs[] = $cov;
                continue;
            }

            $group = VehicleGroup::where('groupname', $vehicleData['BikeFamilyName'] ?? null)->first();
            $groupId = $group ? $group->groupid : null;

            $model = HondaModel::where('modelname', $vehicleData['BikeModelName'] ?? null)->first();
            $modelId = $model ? $model->modelid : null;

            Vehicle::create([
                'vehiclevin' => $vehicleData['VIN'] ?? null,
                'vehiclenickname' => $vehicleData['Nickname'] ?? null,
                'groupid' => $groupId,
                'modelid' => $modelId,
                'truckid' => $truckId,
                'vehiclelicplate' => $vehicleData['LicensePlate'] ?? null,
                'vehiclecolor' => $vehicleData['BikeColor'] ?? null,
                'clientid' => '1',
                'recordstatus' => true,
                'cov' => $cov,
                'vehicletype' => strtolower($vehicleData['VehicleType'] ?? 'demo'),
            ]);

            $insertedCount++;
        }

        // Delete processed file
        if (File::exists($inputFileName)) {
            File::delete($inputFileName);
        }

        // Generate success message
        $msg = 'The file has been loaded successfully...<br>';
        if (!empty($skippedCovs)) {
            foreach ($skippedCovs as $skip) {
                $msg .= 'Bike import skipped - ' . $skip . ' already exists<br>';
            }
        }
        $msg .= $insertedCount . ' vehicles created successfully';

        session()->flash(['msg' => $msg, 'status' => 'success']);

        return response()->json([
            'Message' => 'Success',
            'COVNumber' => $skippedCovs,
            'TotalRecordInserted' => $insertedCount,
        ]);
    }
}
