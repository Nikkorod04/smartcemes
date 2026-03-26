<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecretaryDashboardController extends Controller
{
    /**
     * Display the secretary dashboard.
     */
    public function index()
    {
        return view('dashboards.secretary');
    }
}
