<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignedWaiverController extends Controller
{
    /**
     * Display a listing.
     */
    public function index()
    {
        return view('backend.signed-waivers.index', [
            'title' => 'Manage Signed Waiver',
        ]);
    }
}
