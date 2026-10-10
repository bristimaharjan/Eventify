<?php

namespace App\Http\Controllers;

use App\Models\VendorKyc;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorKycController extends Controller
{
    /**
     * Display the vendor KYC submission & status page.
     */
    public function index()
    {
        $user = Auth::user();
        $kyc = $user->kyc;

        // If rejected, take the vendor directly to the resubmission form
        if ($kyc && $kyc->isRejected()) {
            return redirect()->route('vendor.kyc.resubmit');
        }

        return view('vendor.kyc.index', compact('user', 'kyc'));
    }

    /**
     * Store or update KYC submission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $existingKyc = $user->kyc;

        $hasExistingFront = $existingKyc
            && $existingKyc->document_front
            && Storage::disk('public')->exists($existingKyc->document_front);
        $hasExistingBack = $existingKyc
            && $existingKyc->document_back
            && Storage::disk('public')->exists($existingKyc->document_back);

        $request->validate([
            'business_name' => 'required|string|max:255',
            'pan_vat_number' => 'nullable|string|max:50',
            'document_type' => 'required|string|in:Citizenship,Passport,Business Registration,National ID,Driving License',
            'document_front' => [
                $hasExistingFront ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:5120',
            ],
            'document_back' => [
                $hasExistingBack ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:5120',
            ],
            'company_registration_doc' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'document_front.required' => 'Please upload the front page of your identity or business document.',
            'document_front.mimes' => 'Document front must be an image (JPEG, PNG) or PDF.',
            'document_front.max' => 'Document front cannot exceed 5MB.',
            'document_back.required' => 'Please upload the back page of your identity or business document.',
            'document_back.mimes' => 'Document back must be an image (JPEG, PNG) or PDF.',
            'document_back.max' => 'Document back cannot exceed 5MB.',
            'company_registration_doc.mimes' => 'Company registration document must be an image (JPEG, PNG) or PDF.',
            'company_registration_doc.max' => 'Company registration document cannot exceed 5MB.',
        ]);

        $data = [
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'pan_vat_number' => $request->pan_vat_number,
            'document_type' => $request->document_type,
            'status' => 'pending',
            'rejection_reason' => null,
            'approved_at' => null,
            'rejected_at' => null,
            'reviewed_by' => null,
        ];

        // Handle front document
        if ($request->hasFile('document_front')) {
            if ($existingKyc && $existingKyc->document_front) {
                Storage::disk('public')->delete($existingKyc->document_front);
            }
            $data['document_front'] = $request->file('document_front')->store('kyc_documents', 'public');
        }

        // Handle back document
        if ($request->hasFile('document_back')) {
            if ($existingKyc && $existingKyc->document_back) {
                Storage::disk('public')->delete($existingKyc->document_back);
            }
            $data['document_back'] = $request->file('document_back')->store('kyc_documents', 'public');
        }

        // Handle company registration document
        if ($request->hasFile('company_registration_doc')) {
            if ($existingKyc && $existingKyc->company_registration_doc) {
                Storage::disk('public')->delete($existingKyc->company_registration_doc);
            }
            $data['company_registration_doc'] = $request->file('company_registration_doc')->store('kyc_documents', 'public');
        }

        if ($existingKyc) {
            $existingKyc->update($data);
            $kyc = $existingKyc;
            ActivityLogger::log('kyc_resubmitted', 'Resubmitted KYC verification details for "' . $kyc->business_name . '"', $kyc, $user);
        } else {
            $kyc = VendorKyc::create($data);
            ActivityLogger::log('kyc_submitted', 'Submitted KYC verification details for "' . $kyc->business_name . '"', $kyc, $user);
        }

        return redirect()->route('vendor.kyc.success')->with('success', 'KYC Documents Submitted Successfully');
    }

    /**
     * Show the dedicated KYC Resubmission page for rejected applications.
     */
    public function resubmitForm(Request $request)
    {
        $user = Auth::user();
        $kyc = $user->kyc;

        // If no KYC submitted yet, go to initial KYC submission
        if (!$kyc || $kyc->isNotSubmitted()) {
            return redirect()->route('vendor.kyc.index')
                ->with('warning', 'Please submit your initial KYC documents.');
        }

        // Render the dedicated resubmission page
        return view('vendor.kyc.resubmit', compact('user', 'kyc'));
    }

    /**
     * Process KYC resubmission with replacement or preserved documents.
     */
    public function processResubmit(Request $request)
    {
        $user = Auth::user();
        $kyc = $user->kyc;

        if (!$kyc) {
            return redirect()->route('vendor.kyc.index')
                ->with('error', 'No existing KYC application found to resubmit.');
        }

        if ($kyc->isApproved()) {
            return redirect()->route('vendor.dashboard')
                ->with('success', 'Your KYC verification is already approved.');
        }

        // Check if user is retaining existing documents or replacing
        $hasExistingFront = $kyc->document_front
            && Storage::disk('public')->exists($kyc->document_front);
        $replaceFront = $request->hasFile('document_front');
        
        // If they don't have an existing front and didn't upload a new one, require it
        if (!$hasExistingFront && !$replaceFront) {
            return back()->withErrors([
                'document_front' => 'Please upload a valid front page of your identity or business document.'
            ])->withInput();
        }

        $hasExistingBack = $kyc->document_back
            && Storage::disk('public')->exists($kyc->document_back);
        $replaceBack = $request->hasFile('document_back');

        if (!$hasExistingBack && !$replaceBack) {
            return back()->withErrors([
                'document_back' => 'Please upload a valid back page of your identity or business document.'
            ])->withInput();
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'pan_vat_number' => 'nullable|string|max:50',
            'document_type' => 'required|string|in:Citizenship,Passport,Business Registration,National ID,Driving License',
            'document_front' => [
                $hasExistingFront ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:5120',
            ],
            'document_back' => [
                $hasExistingBack ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:5120',
            ],
            'company_registration_doc' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'document_front.required' => 'Please upload the front page of your identity or business document.',
            'document_front.mimes' => 'Document front must be a valid image (JPG, PNG) or PDF.',
            'document_front.max' => 'Document front may not be greater than 5MB.',
            'document_back.required' => 'Please upload the back page of your identity or business document.',
            'document_back.mimes' => 'Document back must be a valid image (JPG, PNG) or PDF.',
            'document_back.max' => 'Document back may not be greater than 5MB.',
            'company_registration_doc.mimes' => 'Company registration document must be a valid image (JPG, PNG) or PDF.',
            'company_registration_doc.max' => 'Company registration document may not be greater than 5MB.',
        ]);

        $oldReason = $kyc->rejection_reason ?? 'Incorrect document submission';

        $data = [
            'business_name' => $request->business_name,
            'pan_vat_number' => $request->pan_vat_number,
            'document_type' => $request->document_type,
            'status' => 'pending',
            'rejection_reason' => null, // Reset rejection reason upon resubmission
            'approved_at' => null,
            'rejected_at' => null,
            'reviewed_by' => null,
        ];

        // 1. Replace Front Document
        if ($request->hasFile('document_front')) {
            if ($kyc->document_front && Storage::disk('public')->exists($kyc->document_front)) {
                Storage::disk('public')->delete($kyc->document_front);
            }
            $data['document_front'] = $request->file('document_front')->store('kyc_documents', 'public');
        }

        // 2. Replace Back Document (Compulsory)
        if ($request->hasFile('document_back')) {
            if ($kyc->document_back && Storage::disk('public')->exists($kyc->document_back)) {
                Storage::disk('public')->delete($kyc->document_back);
            }
            $data['document_back'] = $request->file('document_back')->store('kyc_documents', 'public');
        }

        // 3. Replace or Remove Company Registration Document
        if ($request->boolean('remove_company_registration_doc')) {
            if ($kyc->company_registration_doc && Storage::disk('public')->exists($kyc->company_registration_doc)) {
                Storage::disk('public')->delete($kyc->company_registration_doc);
            }
            $data['company_registration_doc'] = null;
        } elseif ($request->hasFile('company_registration_doc')) {
            if ($kyc->company_registration_doc && Storage::disk('public')->exists($kyc->company_registration_doc)) {
                Storage::disk('public')->delete($kyc->company_registration_doc);
            }
            $data['company_registration_doc'] = $request->file('company_registration_doc')->store('kyc_documents', 'public');
        }

        $kyc->update($data);

        ActivityLogger::log(
            'kyc_resubmitted',
            'Resubmitted KYC documents for "' . $kyc->business_name . '". Previous rejection reason: "' . $oldReason . '"',
            $kyc,
            $user
        );

        return redirect()->route('vendor.kyc.success')
            ->with('success', 'KYC Documents Submitted Successfully');
    }

    /**
     * Display success confirmation state after KYC submission or resubmission.
     */
    public function success()
    {
        $user = Auth::user();
        $kyc = $user->kyc;

        return view('vendor.kyc.success', compact('user', 'kyc'));
    }

    /**
     * Download / View a submitted KYC document securely.
     */
    public function downloadDocument(VendorKyc $kyc, string $type)
    {
        $user = Auth::user();

        // Must be the owner vendor or an admin
        if ($user->id !== $kyc->user_id && $user->role !== 'admin') {
            abort(403, 'Unauthorized access to KYC document.');
        }

        $filePath = match ($type) {
            'front' => $kyc->document_front,
            'back' => $kyc->document_back,
            'company' => $kyc->company_registration_doc,
            default => null,
        };

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('public')->response($filePath);
    }

    /**
     * Direct 1-click email access for rejected KYC to vendor dashboard.
     */
    public function emailAccess(Request $request, \App\Models\User $user)
    {
        // Authenticate the vendor immediately
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('vendor.dashboard')
            ->with('warning', 'Your KYC verification requires correction. Please review the feedback and click Resubmit KYC.');
    }
}
