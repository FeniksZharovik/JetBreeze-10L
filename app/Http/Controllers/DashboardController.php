<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard');
    }

    public function admin()
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function user()
    {
        return Inertia::render('User/Dashboard');
    }
}
