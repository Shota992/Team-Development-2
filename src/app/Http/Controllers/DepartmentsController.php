<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DepartmentsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('departments/index', compact('user'));
    }
}
