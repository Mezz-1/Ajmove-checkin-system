<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Display the Admin Check-in Dashboard
     */
    public function index(Request $request)
    {
        $query = Registration::query();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search)
                  ->orWhere('ecole', 'LIKE', "%{$search}%");
            });
        }
        if ($request->has('status') && $request->status !== null) {
            $query->where('is_present', $request->status);
        }

        $registrations = $query->orderBy('nom', 'asc')->paginate(20)->withQueryString();

        $stats = [
            'total'    => Registration::count(),
            'present'  => Registration::where('is_present', true)->count(),
            'absent'   => Registration::where('is_present', false)->count(),
        ];

        return view('admin.checkin.index', compact('registrations', 'stats'));
    }

    /**
     * Scan QR Code Route (or Manual ID Check-in)
     */
    public function scan($id)
    {
        $registration = Registration::findOrFail($id);

        // Mark as present
        $registration->update(['is_present' => true]);

        return redirect()->route('admin.checkin.index')
            ->with('success', "Check-in confirmé : {$registration->prenom} {$registration->nom} ({$registration->ecole}) est marqué(e) présent(e) !");
    }

    /**
     * Toggle Presence Status (Present / Absent)
     */
    public function togglePresence(Registration $registration)
    {
        $registration->update([
            'is_present' => !$registration->is_present
        ]);

        $statusText = $registration->is_present ? 'présent(e)' : 'absent(e)';

        return back()->with('success', "Statut mis à jour : {$registration->prenom} {$registration->nom} est désormais {$statusText}.");
    }
}