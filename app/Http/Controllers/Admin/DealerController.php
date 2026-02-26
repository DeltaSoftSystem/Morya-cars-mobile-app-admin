<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerKycDocument;
use App\Models\AppUser;
use App\Services\NotificationService;


class DealerController extends Controller
{
    public function index(Request $request)
    {
        $query = AppUser::where('role', 'dealer')
            ->with('dealerProfile');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $dealers = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.dealers.index', compact('dealers'));
    }



    /* ===============================
       Dealer KYC List
    =============================== */
    public function kyc(Request $request)
    {
        $query = DealerKycDocument::with('user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $documents = $query
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.dealers.kyc', compact('documents'));
    }



    /* ===============================
       Approve KYC
    =============================== */
    public function approveKyc($id)
    {
        $doc = DealerKycDocument::findOrFail($id);
        $doc->status = 'approved';
        $doc->admin_remark = null;
        $doc->save();

        $user = $doc->user;

        // 📧 KYC Approved email
        NotificationService::sendEmail(
            $user->email,
            'Dealer KYC Approved',
            [
                'name'    => $user->name,
                'message' => 'Your dealer KYC has been approved successfully.',
                'extra'   => 'You can now access all dealer features on Morya Auto Hub.'
            ]
        );

        return redirect()->back()->with('success', 'KYC approved successfully');
    }

    /* ===============================
       Reject KYC
    =============================== */
    public function rejectKyc(Request $request, $id)
    {
        $request->validate([
            'admin_remark' => 'required|string|max:500'
        ]);

        $doc = DealerKycDocument::findOrFail($id);
        $doc->status = 'rejected';
        $doc->admin_remark = $request->admin_remark;
        $doc->save();

        $user = $doc->user;

        // 📧 KYC Rejected email
        NotificationService::sendEmail(
            $user->email,
            'Dealer KYC Rejected',
            [
                'name'    => $user->name,
                'message' => 'Your dealer KYC submission has been rejected.',
                'extra'   => "Reason: {$request->admin_remark}"
            ]
        );

        return redirect()->back()->with('success', 'KYC rejected successfully');
    }
}
