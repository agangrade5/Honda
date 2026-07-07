<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DealerRequest;
use App\Models\Dealer;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dealers = Dealer::all();

        return view('backend.dealers.index', [
            'title' => 'Manage Dealers',
            'dealers' => $dealers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DealerRequest $request)
    {
        Dealer::create([
            'dealernumber' => $request->input('DealerNumber'),
            'dealername' => $request->input('DealerName'),
            'dealerlocation' => $request->input('DealerLocation'),
            'dealerregion' => $request->input('DealerRegion'),
            'dealerdistrict' => $request->input('DealerDistrict'),
        ]);

        return redirect()->back()->with(['msg' => 'The Dealer has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DealerRequest $request, string $id)
    {
        $dealer = Dealer::findOrFail($id);
        $dealer->update([
            'dealernumber' => $request->input('DealerNumber'),
            'dealername' => $request->input('DealerName'),
            'dealerlocation' => $request->input('DealerLocation'),
            'dealerregion' => $request->input('DealerRegion'),
            'dealerdistrict' => $request->input('DealerDistrict'),
        ]);

        return redirect()->back()->with(['msg' => 'The Dealer has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DealerRequest $request, string $id)
    {
        $dealer = Dealer::findOrFail($id);
        $dealer->delete();

        return redirect()->back()->with(['msg' => 'The Dealer has been deleted successfully', 'status' => 'success']);
    }
}
