<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\TrainingInstitution;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }
    
    public function show()
    {
        $user = Auth::user();
        
        if (!$user->canSubmitApplication()) {
            return redirect()->route('dashboard')
                ->with('error', 'Please complete your profile and payment first.');
        }
        
        $institutions = TrainingInstitution::active()->get();
        
        return view('application.show', [
            'user' => $user,
            'institutions' => $institutions,
            'application' => $user->application
        ]);
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canSubmitApplication()) {
            return back()->with('error', 'Please complete your profile and payment first.');
        }
        
        if ($user->hasSubmittedApplication()) {
            return back()->with('error', 'Application already submitted.');
        }
        
        $request->validate([
            'training_institution_id' => 'required|exists:training_institutions,id',
            'need_assessment_text' => 'required|string|min:100|max:2000',
            'supporting_documents' => 'nullable|array|max:5',
            'supporting_documents.*' => 'file|max:2048|mimes:pdf,doc,docx,jpg,jpeg,png',
            'terms_agreed' => 'required|accepted'
        ]);
        
        try {
            DB::beginTransaction();
            
            $supportingDocs = [];
            
            // Handle file uploads
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $file) {
                    $path = $file->store('supporting_documents', 'public');
                    $supportingDocs[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ];
                }
            }
            
            // Create application
            Application::create([
                'user_id' => $user->id,
                'training_institution_id' => $request->training_institution_id,
                'need_assessment_text' => $request->need_assessment_text,
                'supporting_documents' => $supportingDocs,
                'terms_agreed_at' => now()
            ]);
            
            // Update user status
            $user->update([
                'application_status' => 'pending',
                'application_submitted_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('dashboard')
                ->with('success', 'Application submitted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to submit application. Please try again.');
        }
    }
    
    public function edit()
    {
        $user = Auth::user();
        $application = $user->application;
        
        if (!$application || $user->application_status !== 'pending') {
            return redirect()->route('dashboard')
                ->with('error', 'Application cannot be edited at this time.');
        }
        
        $institutions = TrainingInstitution::active()->get();
        
        return view('application.edit', [
            'user' => $user,
            'application' => $application,
            'institutions' => $institutions
        ]);
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $application = $user->application;
        
        if (!$application || $user->application_status !== 'pending') {
            return back()->with('error', 'Application cannot be edited at this time.');
        }
        
        $request->validate([
            'training_institution_id' => 'required|exists:training_institutions,id',
            'need_assessment_text' => 'required|string|min:100|max:2000',
            'supporting_documents' => 'nullable|array|max:5',
            'supporting_documents.*' => 'file|max:2048|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);
        
        try {
            DB::beginTransaction();
            
            $supportingDocs = $application->supporting_documents ?? [];
            
            // Handle new file uploads
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $file) {
                    $path = $file->store('supporting_documents', 'public');
                    $supportingDocs[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ];
                }
            }
            
            $application->update([
                'training_institution_id' => $request->training_institution_id,
                'need_assessment_text' => $request->need_assessment_text,
                'supporting_documents' => $supportingDocs,
            ]);
            
            DB::commit();
            
            return redirect()->route('dashboard')
                ->with('success', 'Application updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update application. Please try again.');
        }
    }
}