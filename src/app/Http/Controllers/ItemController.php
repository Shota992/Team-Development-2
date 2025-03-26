<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('items.index', compact('user'));
    }
}
