<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        $stats = [
            'total_students' => User::count(),
            'completed_profiles' => User::where('registration_stage', 'completed')->count(),
            'pending_payments' => Payment::where('status', 'submitted')->count(),
            'total_payments' => Payment::where('status', 'success')->sum('amount'),
        ];

        // Get completed registrations by month for current year
        $currentYear = Carbon::now()->year;
        $completedByMonth = [];
        
        // Initialize all months to 0
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        foreach ($months as $month) {
            $completedByMonth[$month] = 0;
        }
        
        // Get actual data
        $completedRegistrations = User::whereYear('updated_at', $currentYear)
            ->where('registration_stage', 'completed')
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Fill in the actual data
        foreach ($completedRegistrations as $registration) {
            $monthName = Carbon::create()->month($registration->month)->format('F');
            $completedByMonth[$monthName] = $registration->count;
        }

        return view('admin.dashboard', compact('stats', 'admin', 'completedByMonth', 'currentYear'));
    }
}