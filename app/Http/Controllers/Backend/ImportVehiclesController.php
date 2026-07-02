<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
