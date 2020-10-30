<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4|max:255',
            'email' => 'required|email|min:10|max:255',
            'password' => 'nullable|min:4',
            'picture' => 'nullable|max:5096|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        if ( ! empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->has('picture') && $request->file('picture')->isValid()) {
            if (isset($user->media[0])) {
                $user->media[0]->delete();
            }

            $user->addMediaFromRequest('picture')
                ->toMediaCollection('user_profile_picture');
        }

        return redirect()
            ->back()
            ->withSuccess('Berhasil memperbarui profil');
    }
}
