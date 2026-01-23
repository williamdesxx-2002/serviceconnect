<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Test pour vérifier que le controller est bien appelé
        if (app()->environment('local')) {
            logger('ServiceController::index called');
        }
        
        $query = Service::with(['user', 'category'])->where('is_active', true);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Category filter
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Price filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Geolocation
        if ($request->latitude && $request->longitude) {
            $query->nearby($request->latitude, $request->longitude, $request->radius ?? 10);
        }

        // Sort
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $services = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        // Debug: Log de la méthode
        if (app()->environment('local')) {
            logger('ServiceController::create called');
        }
        
        // Si l'utilisateur n'est pas connecté, rediriger vers login
        if (!auth()->check()) {
            logger('User not authenticated, redirecting to login');
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour créer un service.');
        }
        
        // Debug: Log utilisateur connecté
        logger('User authenticated: ' . auth()->user()->email . ' Role: ' . auth()->user()->role);
        
        // Vérifier si l'utilisateur est un prestataire, sinon rediriger avec message
        if (!auth()->user()->isProvider()) {
            logger('User is not a provider, redirecting');
            return redirect()->route('services.index')
                ->with('error', 'Seuls les prestataires peuvent créer des services. Veuillez vous inscrire en tant que prestataire pour accéder à cette fonctionnalité.');
        }
        
        logger('User is provider, loading categories');
        $categories = Category::where('is_active', true)->get();
        logger('Categories loaded: ' . $categories->count());
        
        logger('Returning services.create_simple view');
        return view('services.create_simple', compact('categories'));
    }

    /**
     * Get or create a custom category for "Autre" selection
     */
    private function getOrCreateOtherCategory($categoryName)
    {
        // Vérifier si une catégorie personnalisée existe déjà
        $existingCategory = \App\Models\Category::where('name', 'like', 'autre: ' . $categoryName . '%')->first();
        
        if ($existingCategory) {
            return $existingCategory->id;
        }
        
        // Créer une nouvelle catégorie personnalisée
        $newCategory = \App\Models\Category::create([
            'name' => 'autre: ' . $categoryName,
            'slug' => 'autre-' . Str::slug($categoryName),
            'description' => 'Catégorie personnalisée: ' . $categoryName,
            'icon' => '📝',
            'is_active' => true,
        ]);
        
        return $newCategory->id;
    }

    public function store(Request $request)
    {
        // Debug: Log method entry
        if (app()->environment('local')) {
            logger('=== SERVICE CONTROLLER STORE METHOD CALLED ===');
            logger('Request method: ' . $request->method());
            logger('Request URL: ' . $request->fullUrl());
            logger('Request headers: ' . json_encode($request->headers->all()));
        }
        
        // Check if this is actually a POST request
        if (!$request->isMethod('post')) {
            logger('ERROR: Store method called with non-POST method: ' . $request->method());
            return redirect()->route('services.create')
                ->with('error', 'Méthode HTTP incorrecte. Veuillez réessayer.');
        }
        
        // Debug: Log request data
        if (app()->environment('local')) {
            logger('Request data: ' . json_encode($request->all()));
            logger('Request files: ' . json_encode($request->files->all()));
        }
        
        // Check if user is authenticated
        if (!auth()->check()) {
            logger('User not authenticated');
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour créer un service.');
        }
        
        logger('User authenticated: ' . auth()->user()->email);
        
        try {
            $validated = $request->validate([
                'category_id' => 'required|string|in:other,' . \App\Models\Category::pluck('id')->implode(','),
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'price_type' => 'required|in:fixed,hourly,daily',
                'duration' => 'nullable|integer',
                'tags' => 'nullable|string',
                'neighborhood' => 'required|string|in:centre-ville,nkembo,owendo,akanda,angondjé,batterie-iv,batterie-viii,glass,mont-bouet,nzeng-ayong,sablière,sogara,tollé,autre',
                'other_neighborhood' => 'required_if:neighborhood,autre|string|max:255',
                'other_category' => 'required_if:category_id,other|string|max:255',
                'images' => 'nullable|array|max:5',
                'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120', // 5MB max
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger('Validation failed: ' . json_encode($e->errors()));
            logger('Request data that failed validation: ' . json_encode($request->all()));
            logger('Available categories: ' . \App\Models\Category::pluck('id')->implode(','));
            throw $e;
        }

        // Debug: Log validation success
        if (app()->environment('local')) {
            logger('Validation passed. Validated data: ' . json_encode($validated));
        }

        // Si "autre" est sélectionné pour le quartier, utiliser le champ personnalisé
        if ($request->neighborhood === 'autre') {
            $validated['neighborhood'] = 'autre: ' . $validated['other_neighborhood'];
        }

        // Si "autre" est sélectionné pour la catégorie, créer ou récupérer la catégorie personnalisée
        if ($request->category_id === 'other') {
            $validated['category_id'] = $this->getOrCreateOtherCategory($validated['other_category']);
        }

        // Ajouter automatiquement les valeurs par défaut pour Libreville
        $validated['city'] = 'Libreville';
        $validated['country'] = 'Gabon';

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'approved';
        $validated['is_active'] = true;

        // Gestion des tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = explode(',', $validated['tags']);
            $validated['tags'] = array_map('trim', $validated['tags']);
            $validated['tags'] = array_filter($validated['tags']);
        } else {
            $validated['tags'] = [];
        }

        // Création du service
        try {
            logger('About to create service with data: ' . json_encode($validated));
            $service = Service::create($validated);
            
            // Debug: Log service creation success
            if (app()->environment('local')) {
                logger('Service created successfully with ID: ' . $service->id);
                logger('Service data: ' . json_encode($service->toArray()));
            }
            
            logger('Service creation completed, about to redirect');
            return redirect()->route('services.index')
                ->with('success', 'Service créé avec succès !');
                
        } catch (\Exception $e) {
            // Debug: Log service creation error
            if (app()->environment('local')) {
                logger('Service creation failed: ' . $e->getMessage());
                logger('Exception trace: ' . $e->getTraceAsString());
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du service: ' . $e->getMessage());
        }

        // Gestion des images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    // Génération d'un nom unique
                    $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Stockage de l'image
                    $path = $image->storeAs('services/images', $fileName, 'public');
                    
                    // Ajout au tableau des images
                    $images[] = $path;
                }
            }
            
            // Sauvegarde des images dans le service
            if (!empty($images)) {
                $service->images = $images;
                $service->save();
            }
        }

        logger('Skipping image management, going directly to redirect');
        return redirect()->route('services.index')
            ->with('success', 'Service créé avec succès !');
    }

    public function show(Service $service)
    {
        $service->load(['user', 'category', 'reviews.client']);
        $relatedServices = Service::where('category_id', $service->id)
            ->where('id', '!=', $service->id)
            ->active()
            ->limit(4)
            ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }

    public function myServices()
    {
        $services = auth()->user()->services()
            ->with(['category', 'bookings'])
            ->latest()
            ->paginate(10);

        return view('services.my', compact('services'));
    }

    public function edit(Service $service)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur a le droit de modifier ce service
        // Le propriétaire du service ou l'admin peut modifier
        if ($user->id !== $service->user_id && !$user->isAdmin()) {
            abort(403, 'This action is unauthorized.');
        }
        
        $categories = Category::where('is_active', true)->get();

        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur a le droit de modifier ce service
        // Le propriétaire du service ou l'admin peut modifier
        if ($user->id !== $service->user_id && !$user->isAdmin()) {
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'category_id' => 'required|string|in:other,' . \App\Models\Category::pluck('id')->implode(','),
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:fixed,hourly,daily',
            'duration' => 'nullable|integer',
            'tags' => 'nullable|string',
            'neighborhood' => 'required|string|in:centre-ville,nkembo,owendo,akanda,angondjé,batterie-iv,batterie-viii,glass,mont-bouet,nzeng-ayong,sablière,sogara,tollé,autre',
            'other_neighborhood' => 'required_if:neighborhood,autre|string|max:255',
            'other_category' => 'required_if:category_id,other|string|max:255',
            'is_active' => 'boolean',
            'new_images' => 'nullable|array|max:5',
            'new_images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120', // 5MB max
            'removed_images' => 'nullable|array',
        ]);

        // Si "autre" est sélectionné pour le quartier, utiliser le champ personnalisé
        if ($request->neighborhood === 'autre') {
            $validated['neighborhood'] = 'autre: ' . $validated['other_neighborhood'];
        }

        // Si "autre" est sélectionné pour la catégorie, créer ou récupérer la catégorie personnalisée
        if ($request->category_id === 'other') {
            $validated['category_id'] = $this->getOrCreateOtherCategory($validated['other_category']);
        }

        // Ajouter automatiquement les valeurs par défaut pour Libreville
        $validated['city'] = 'Libreville';
        $validated['country'] = 'Gabon';

        // Gestion des tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = explode(',', $validated['tags']);
            $validated['tags'] = array_map('trim', $validated['tags']);
            $validated['tags'] = array_filter($validated['tags']);
        } else {
            $validated['tags'] = [];
        }

        // Gestion des images existantes
        $currentImages = $service->images ?? [];
        if (is_string($currentImages)) {
            $currentImages = json_decode($currentImages, true) ?? [];
        }

        // Suppression des images existantes
        $removedImages = $validated['removed_images'] ?? [];
        if (!empty($removedImages)) {
            $currentImages = array_filter($currentImages, function($index) use ($removedImages) {
                return !in_array($index, $removedImages);
            });
        }

        // Ajout des nouvelles images
        if ($request->hasFile('new_images')) {
            $newImages = [];
            foreach ($request->file('new_images') as $image) {
                if ($image && $image->isValid()) {
                    // Génération d'un nom unique
                    $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Stockage de l'image
                    $path = $image->storeAs('services/images', $fileName, 'public');
                    
                    // Ajout au tableau des nouvelles images
                    $newImages[] = $path;
                }
            }
            
            // Fusion des images existantes et nouvelles
            $currentImages = array_merge($currentImages, $newImages);
        }

        // Mise à jour du service avec les images
        $validated['images'] = $currentImages;

        // Suppression des champs de gestion d'images
        unset($validated['new_images']);
        unset($validated['removed_images']);

        $service->update($validated);

        return redirect()->route('services.my')
            ->with('success', 'Service mis à jour avec succès.');
    }

    public function destroy(Service $service)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur a le droit de supprimer ce service
        // Le propriétaire du service ou l'admin peut supprimer
        if ($user->id !== $service->user_id && !$user->isAdmin()) {
            abort(403, 'This action is unauthorized.');
        }
        
        $service->delete();

        return redirect()->route('services.my')
            ->with('success', 'Service supprimé avec succès.');
    }
}
