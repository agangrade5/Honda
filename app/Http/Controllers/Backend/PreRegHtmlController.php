<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreRegHtmlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pre-reg-html.index', [
            'title' => 'Manage Pre-Reg Html',
        ]);
    }
}
