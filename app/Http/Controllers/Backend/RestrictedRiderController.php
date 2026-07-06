<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RestrictedRiderRequest;
use App\Models\RestrictedRider;
use App\Models\Customer;
use Illuminate\Http\Request;

class RestrictedRiderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $riders = RestrictedRider::all();

        foreach ($riders as $rider) {
            // Lookup matching customer by drivers license or card number
            $customer = null;
            if (!empty($rider->restrictlic)) {
                $customer = Customer::where('custdriverslicense', $rider->restrictlic)
                    ->orWhere('cardnumber', $rider->restrictlic)
                    ->first();
            }

            if ($customer) {
                $rider->RiderFirstName = $customer->custfname;
                $rider->RiderLastName = $customer->custlname;
            } else {
                $rider->RiderFirstName = '';
                $rider->RiderLastName = '';
            }
        }

        $restrictedriders = (object)[
            'Success' => 1,
            'RestrictedRiders' => $riders,
        ];

        return view('backend.restricted-rider.index', [
            'title' => 'Manage Restricted Riders',
            'restrictedriders' => $restrictedriders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestrictedRiderRequest $request)
    {
        RestrictedRider::create([
            'restrictlic' => $request->input('RestrictLic'),
            'restrictcomment' => $request->input('RestrictComment'),
            'servertime' => now(),
            'restricttime' => now(),
        ]);

        return redirect()->back()->with(['msg' => 'The Restricted Rider has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestrictedRiderRequest $request, string $id)
    {
        $rider = RestrictedRider::findOrFail($id);
        $rider->update([
            'restrictlic' => $request->input('RestrictLic'),
            'restrictcomment' => $request->input('RestrictComment'),
        ]);

        return redirect()->back()->with(['msg' => 'The Restricted Rider has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestrictedRiderRequest $request, string $id)
    {
        $rider = RestrictedRider::findOrFail($id);
        $rider->delete();

        return redirect()->back()->with(['msg' => 'The Restricted Rider has been deleted successfully', 'status' => 'success']);
    }
}
