<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class KycVerificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // KYC submission is not enabled yet.
        // This is currently an informational dashboard.

        $kyc = [
            'status' => 'not_submitted',
            'status_label' => 'Not Submitted',
            'submitted_at' => null,
            'verified_at' => null,
            'remarks' => null,
        ];

        $steps = [
            [
                'number' => 1,
                'title' => 'Personal Information',
                'description' => 'Review your registered personal information.',
            ],
            [
                'number' => 2,
                'title' => 'Identity Verification',
                'description' => 'Identity verification will be available after secure KYC setup.',
            ],
            [
                'number' => 3,
                'title' => 'Verification Review',
                'description' => 'The authorized verification team will review submitted information.',
            ],
            [
                'number' => 4,
                'title' => 'Verification Complete',
                'description' => 'Your KYC status will be updated after successful verification.',
            ],
        ];

        return view('user.profile.kyc-verification', compact(
            'user',
            'kyc',
            'steps'
        ));
    }
}