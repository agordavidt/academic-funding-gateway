<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        $stats = [
            'total_students' => User::count(),
            'completed_profiles' => User::where('registration_stage', 'completed')->count(),
            'pending_payments' => User::where('payment_status', 'pending')->count(),
            'total_payments' => Payment::where('status', 'success')->sum('amount'),
            'accepted_applications' => User::where('application_status', 'accepted')->count(),
            'pending_applications' => User::where('application_status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'admin'));
    }
}