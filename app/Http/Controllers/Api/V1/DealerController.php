<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerProfile;
use App\Models\DealerKycDocument;
use App\Models\AppUser;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;


class DealerController extends Controller
{
    public function becomeDealer(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'gst_number'    => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
        ]);

        // auth user (NOT eloquent)
        $authUser = auth()->user();

        // FETCH REAL ELOQUENT MODEL
        $user = AppUser::findOrFail($authUser->id);

        if ($user->role === 'dealer') {
            return response()->json([
                'message' => 'Already registered as dealer'
            ], 400);
        }

        // ✅ NOW THIS WILL WORK
        $user->role = 'dealer';
        $user->save();

        DealerProfile::create([
            'user_id'       => $user->id,
            'business_name' => $request->business_name,
            'gst_number'    => $request->gst_number,
            'address'       => $request->address,
        ]);

        // 📧 Dealer registration email
        NotificationService::sendEmail(
            $user->email,
            'Dealer Registration Successful',
            [
                'name'    => $user->name,
                'message' => 'You have successfully registered as a dealer on Morya Auto Hub.',
                'extra'   => 'Please upload your KYC documents to activate dealer features.'
            ]
        );

        return response()->json([
            'message' => 'Dealer registration successful. Please upload KYC documents.'
        ]);
    }

    /* ===============================
       STEP 2: Upload KYC Document
    =============================== */
    public function uploadKyc(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:business_proof,gst,id_proof',
            'document'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $authUser  = auth()->user();
        $user = AppUser::findOrFail($authUser->id);

        if ($user->role !== 'dealer') {
            return response()->json([
                'message' => 'Only dealers can upload KYC'
            ], 403);
        }

        $path = $request->file('document')
                        ->store('dealer_kyc', 'public');

        DealerKycDocument::create([
            'user_id'       => $user->id,
            'document_type' => $request->document_type,
            'document_path' => $path,
            'status'        => 'pending'
        ]);

        return response()->json([
            'message' => 'KYC document uploaded successfully'
        ]);
    }

    /* ===============================
       STEP 3: Dealer Status (UI helper)
    =============================== */
    public function status(Request $request)
    {
        $authUser = $request->user(); // Sanctum user

        // Always fetch real Eloquent model
        $user = AppUser::find($authUser->id);

        return response()->json([
            'is_dealer' => $user->role === 'dealer',
            'profile'   => $user->dealerProfile,
            'kyc_docs'  => $user->dealerKycDocuments()
                                ->select('document_type', 'status')
                                ->get(),
        ]);
    }
}
