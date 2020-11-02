<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        $section = $request->section;

        switch ($section) {
            case 'general' :
                $request->validate([
                    'site_name' => 'required|min:4|max:255',
                    'site_description' => 'nullable|max:512',
                    'site_logo' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048'
                ]);

                updateSetting('siteName', $request->site_name);
                updateSetting('siteDescription', $request->site_description);

                if ($request->hasFile('site_logo') && $request->file('site_logo')->isValid()) {
                    $getLogo = Setting::where('key', 'siteLogo')->first();
                    if (isset($getLogo->media[0])) {
                        $getLogo->media[0]->delete();
                    }

                    $getLogo->addMediaFromRequest('site_logo')
                        ->toMediaCollection('site_logo');
                }

                return redirect()
                    ->back()
                    ->withSuccess('Berhasil memperbarui pengaturan situs');
            break;
            case 'contact' :
                $request->validate([
                    'email' => 'nullable|email|min:10|max:255'
                ]);

                updateSetting('siteEmail', $request->email);
                updateSetting('sitePhoneNumber', $request->phone_number);
                updateSetting('siteAddress', $request->address);

                return redirect()
                    ->back()
                    ->withSuccess('Berhasil memperbarui informasi kontak');
            break;
        }
    }
}
