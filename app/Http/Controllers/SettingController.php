<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class SettingController extends Controller
{
    
  public function index()
    {
        $settings = Setting::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::first();
        
        $data = $request->all();

        // Handle file uploads
        if ($request->hasFile('og_image')) {
            if ($settings->og_image) {
                Storage::delete($settings->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('settings');
        }

        if ($request->hasFile('twitter_image')) {
            if ($settings->twitter_image) {
                Storage::delete($settings->twitter_image);
            }
            $data['twitter_image'] = $request->file('twitter_image')->store('settings');
        }

        if ($request->hasFile('site_logo')) {
            if ($settings->site_logo) {
                Storage::delete($settings->site_logo);
            }
            $data['site_logo'] = $request->file('site_logo')->store('settings');
        }

        if ($request->hasFile('site_favicon')) {
            if ($settings->site_favicon) {
                Storage::delete($settings->site_favicon);
            }
            $data['site_favicon'] = $request->file('site_favicon')->store('settings');
        }

        $settings->update($data);

        return back()->with('success', 'Settings updated successfully.');
    }
}
