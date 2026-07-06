<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserRequest;
use App\Models\User;
use App\Models\UserType;
use App\Models\ReportRegion;
use App\Models\Event;
use App\Models\Country;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hondaUsers = User::select([
                'users.*',
                'usertypes.usertypetitle'
            ])
            ->leftJoin('usertypes', 'users.userlevel', '=', 'usertypes.usertypeid')
            ->get();

        foreach ($hondaUsers as $user) {
            $user->UserID = $user->userid;
            $user->UserFullName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
            $user->UserName = $user->username;
            $user->UserPhone = $user->userphone;
            $user->UserTypeTitle = $user->usertypetitle;
            $user->UserLevel = $user->userlevel;
            $user->UserPass = $user->userpass;
            
            // Format allowed arrays as JSON strings representing array values
            $user->AllowRegion = $this->safeUnserialize($user->allowregion);
            $user->AllowEvents = $this->safeUnserialize($user->allowevents);
            $user->AllowCountry = $this->safeUnserialize($user->allowcountry);
        }

        $users = (object)[
            'Success' => 1,
            'Users' => $hondaUsers,
        ];

        $usertypes = (object)[
            'Success' => 1,
            'UserTypes' => UserType::all()->map(function($ut) {
                return (object)[
                    'UserTypeId' => $ut->usertypeid,
                    'UserTypeTitle' => $ut->usertypetitle
                ];
            })
        ];

        $regions = (object)[
            'Regions' => ReportRegion::all()->map(function($r) {
                return (object)[
                    'RegionID' => $r->regionid,
                    'RegionName' => $r->regionname
                ];
            })
        ];

        $events = (object)[
            'Events' => [
                'all' => Event::all()->map(function($e) {
                    return (object)[
                        'EventID' => $e->eventid,
                        'EventName' => $e->eventname
                    ];
                })
            ]
        ];

        $countries = (object)[
            'Country' => Country::all()->map(function($c) {
                return (object)[
                    'CountryID' => $c->countryid,
                    'CountryName' => $c->countryname
                ];
            })
        ];

        return view('backend.users.index', [
            'title' => 'Manage Users',
            'users' => $users,
            'usertypes' => $usertypes,
            'regions' => $regions,
            'events' => $events,
            'countries' => $countries,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create([
            'firstname' => $request->input('FirstName'),
            'lastname' => $request->input('LastName'),
            'username' => $request->input('UserName'),
            'userlevel' => $request->input('UserLevel'),
            'userphone' => $request->input('UserPhone'),
            'userpass' => $request->input('UserPass'),
            'allowregion' => serialize($request->input('Region', [])),
            'allowevents' => serialize($request->input('Events', [])),
            'allowcountry' => serialize($request->input('Country', [])),
            'clientid' => 1,
        ]);

        return redirect()->back()->with(['msg' => 'The User has been created successfully', 'status' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'firstname' => $request->input('FirstName'),
            'lastname' => $request->input('LastName'),
            'username' => $request->input('UserName'),
            'userlevel' => $request->input('UserLevel'),
            'userphone' => $request->input('UserPhone'),
            'userpass' => $request->input('UserPass'),
            'allowregion' => serialize($request->input('Region', [])),
            'allowevents' => serialize($request->input('Events', [])),
            'allowcountry' => serialize($request->input('Country', [])),
        ]);

        return redirect()->back()->with(['msg' => 'The User has been updated successfully', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRequest $request, string $id)
    {
        $userId = $request->input('DeleteUserID');
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect()->back()->with(['msg' => 'The User has been deleted successfully', 'status' => 'success']);
    }

    /**
     * Safely unserialize PHP string data.
     */
    private function safeUnserialize($data)
    {
        if (empty($data)) {
            return [];
        }
        $unserialized = @unserialize($data);
        if ($unserialized === false && $data !== 'b:0;') {
            return [];
        }
        return is_array($unserialized) ? array_values($unserialized) : [];
    }
}
