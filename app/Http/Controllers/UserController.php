<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users', compact('roles'));
    }
}
