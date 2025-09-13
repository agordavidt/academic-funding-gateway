<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Carbon\Carbon;

class SettingController extends Controller
{
    public function index()
    {
        $deadline = Setting::where('key', 'application_deadline')->first();
        return view('admin.settings.deadline', compact('deadline'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deadline' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:today',
        ]);

        Setting::updateOrCreate(
            ['key' => 'application_deadline'],
            ['value' => $request->input('deadline')]
        );

        return redirect()->back()->with('success', 'Application deadline updated successfully! ✅');
    }
}