<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['data' => User::with('roles')->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:4', 'max:255'],
            'email' => ['required', 'unique:users,email', 'email', 'min:10', 'max:255'],
            'password' => ['required', 'min:4'],
            'role' => ['required']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();

        $user->assignRole($request->role);

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil menambah user baru'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->roles;

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:4', 'max:255'],
            'email' => ['required', 'unique:users,email,'. $user->id, 'email', 'min:10', 'max:255'],
            'password' => ['nullable', 'min:4'],
            'role' => ['required']
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'error' => true,
                    'validations' => $validator->errors()
                ], 422);
        }

        $currentRole = $user->roles[0]->name;

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();

        $user->removeRole($currentRole);
        $user->assignRole($request->role);

        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil memperbarui data user'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (isset($user->media[0])) {
            $user->media[0]->delete();
        }

        $user->delete();
        return response()
            ->json([
                'success' => true,
                'message' => 'Berhasil menghapus user'
            ]);
    }
}
