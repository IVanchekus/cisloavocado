<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use Illuminate\Http\Request;

class NavController extends Controller
{
    public function getNavs()
    {
        return Nav::where('is_active', true)->get();
    }
}
