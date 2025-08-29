<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        return view('dashboard', [
            'user' => $user,
            'progress' => $user->getProgressPercentage(),
            'canSubmitApplication' => $user->canSubmitApplication(),
            'latestPayment' => $user->latestPayment,
            'application' => $user->application
        ]);
    }
}