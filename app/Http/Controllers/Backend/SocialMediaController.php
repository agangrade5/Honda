<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SocialMediaRequest;
use App\Models\SocialMedia;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socialmedias = SocialMedia::all();

        return view('backend.social-media.index', [
            'title' => 'Manage Social Media',
            'socialmedias' => $socialmedias,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialMediaRequest $request)
    {
        $blob = serialize([
            'Facebook' => $request->input('Facebook') ?? '',
            'Twitter' => $request->input('Twitter') ?? '',
            'Instagram' => $request->input('Instagram') ?? '',
        ]);

        SocialMedia::create([
            'socialname' => $request->input('SocialName'),
            'socialblob' => $blob,
            'clientid' => auth()->user()?->clientid ?? 1,
        ]);

        return redirect()->back()->with('msg', 'The Social Media has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialMediaRequest $request, string $id)
    {
        $socialmedia = SocialMedia::findOrFail($id);

        $blob = serialize([
            'Facebook' => $request->input('Facebook') ?? '',
            'Twitter' => $request->input('Twitter') ?? '',
            'Instagram' => $request->input('Instagram') ?? '',
        ]);

        $socialmedia->update([
            'socialblob' => $blob,
        ]);

        return redirect()->back()->with('msg', 'The Social Media has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMediaRequest $request, string $id)
    {
        $socialmedia = SocialMedia::findOrFail($id);
        $socialmedia->delete();

        return redirect()->back()->with('msg', 'The Social Media has been deleted successfully');
    }
}
