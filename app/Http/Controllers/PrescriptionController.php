<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('staff')) {
            // Admin/Staff view all prescriptions (can filter by status)
            $status = $request->query('status');
            $query = Prescription::with('user')->orderBy('created_at', 'desc');
            
            if ($status) {
                $query->where('status', $status);
            }
            
            $prescriptions = $query->paginate(15);
            return view('prescriptions.admin_index', compact('prescriptions'));
        }

        // Customer view only their prescriptions
        $prescriptions = $user->prescriptions()->orderBy('created_at', 'desc')->paginate(10);
        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Store a newly created prescription in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'prescription_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ]);

        $user = auth()->user();
        $file = $request->file('prescription_file');
        
        // Define destination folder in public uploads
        $destinationPath = public_path('uploads/prescriptions');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);
        $filePath = 'uploads/prescriptions/' . $fileName;

        Prescription::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'patient_name' => $request->patient_name,
            'doctor_name' => $request->doctor_name,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('prescriptions.index')->with('success', 'Prescription uploaded successfully. Our pharmacists will review it shortly.');
    }

    /**
     * Update the prescription status (Admin/Staff only).
     */
    public function update(Request $request, Prescription $prescription)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$user->hasRole('staff')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $prescription->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('prescriptions.index')->with('success', 'Prescription status updated successfully.');
    }

    /**
     * Delete a prescription (Customer can delete their pending/rejected prescriptions).
     */
    public function destroy(Prescription $prescription)
    {
        $user = auth()->user();
        
        if ($prescription->user_id !== $user->id && !$user->hasRole('admin') && !$user->hasRole('staff')) {
            abort(403, 'Unauthorized action.');
        }

        // Delete physical file
        $physicalPath = public_path($prescription->file_path);
        if (file_exists($physicalPath)) {
            @unlink($physicalPath);
        }

        $prescription->delete();

        return redirect()->route('prescriptions.index')->with('success', 'Prescription deleted successfully.');
    }
}
