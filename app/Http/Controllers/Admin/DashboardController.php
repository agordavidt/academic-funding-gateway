<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Application;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index()
    {
        $stats = [
            'total_students' => User::where('is_admin', false)->count(),
            'completed_profiles' => User::where('is_admin', false)
                ->where('profile_completion_status', 'completed')->count(),
            'paid_students' => User::where('is_admin', false)
                ->where('payment_status', 'paid')->count(),
            'total_applications' => Application::count(),
            'pending_applications' => User::where('is_admin', false)
                ->where('application_status', 'pending')->count(),
            'accepted_applications' => User::where('is_admin', false)
                ->where('application_status', 'accepted')->count(),
            'total_revenue' => Payment::where('status', 'success')->sum('amount')
        ];
        
        $recentApplications = User::where('is_admin', false)
            ->whereIn('application_status', ['pending', 'reviewing'])
            ->with(['application.trainingInstitution'])
            ->latest('application_submitted_at')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentApplications'));
    }
}
