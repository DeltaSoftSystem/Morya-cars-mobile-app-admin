<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Service;

class ServiceRequestController extends Controller
{
     public function index(Request $request)
    {
        $services = Service::orderBy('name')->get();

        $query = ServiceRequest::with(['service', 'user', 'car'])
            ->latest();

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(20);
        $requests->appends($request->all());

        return view('admin.service_requests.index', compact(
            'requests',
            'services'
        ));
    }

    public function show($id)
    {
        $request = ServiceRequest::with(['service','item', 'user', 'car'])
            ->findOrFail($id);
        
        return view('admin.service_requests.show', compact('request'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled',
            'admin_comment' => 'nullable|string'
        ]);

        $sr = ServiceRequest::findOrFail($id);
        $sr->status = $request->status;
        $sr->admin_comment = $request->admin_comment;
        $sr->save();

        return redirect()
            ->route('service-requests.show', $sr->id)
            ->with('success', 'Service request updated successfully');
    }
}
