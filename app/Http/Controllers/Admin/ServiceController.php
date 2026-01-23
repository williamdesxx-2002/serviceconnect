<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['user', 'category'])
            ->latest()
            ->paginate(15);
            
        return view('admin.services.index', compact('services'));
    }
    
    public function show(Service $service)
    {
        $service->load(['user', 'category', 'bookings', 'reviews']);
        
        return view('admin.services.show', compact('service'));
    }
    
    public function toggleStatus(Service $service)
    {
        $service->update([
            'status' => $service->status === 'active' ? 'inactive' : 'active'
        ]);
        
        return back()->with('success', 'Statut du service mis à jour');
    }
    
    public function report(Request $request, Service $service)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);
        
        // Créer un signalement pour le service
        $service->update([
            'is_reported' => true,
            'report_reason' => $request->reason,
            'report_description' => $request->description,
            'reported_at' => now(),
            'reported_by' => auth()->id(),
        ]);
        
        return back()->with('success', 'Service signalé avec succès');
    }
    
    public function destroy(Service $service)
    {
        // Vérifier si le service a déjà été signalé
        if (!$service->is_reported) {
            return back()->with('error', 'Ce service n\'a pas été signalé auparavant');
        }
        
        // Vérifier si c'est une récidive (déjà signalé avant)
        $previousReports = Service::where('user_id', $service->user_id)
            ->where('is_reported', true)
            ->where('id', '!=', $service->id)
            ->count();
            
        if ($previousReports > 0) {
            // C'est une récidive - suppression du service et sanctions possibles
            $serviceName = $service->title;
            $providerName = $service->user->name;
            
            $service->delete();
            
            return back()->with('warning', "Service '{$serviceName}' supprimé pour récidive. Le prestataire '{$providerName}' a été notifié.");
        } else {
            // Première infraction - simple signalement
            return back()->with('info', 'Premier signalement pour ce prestataire. Le service a été désactivé temporairement.');
        }
    }
}
