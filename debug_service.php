<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;

echo "=== DEBUG SERVICE CREATION ===\n";

// Check if we can create a service manually
try {
    $testService = [
        'user_id' => 27, // pierre.provider@example.com
        'category_id' => 1,
        'title' => 'Test Service Debug',
        'description' => 'This is a test service for debugging',
        'price' => 5000,
        'price_type' => 'fixed',
        'duration' => 60,
        'city' => 'Libreville',
        'country' => 'Gabon',
        'neighborhood' => 'centre-ville',
        'status' => 'approved',
        'is_active' => true,
        'tags' => json_encode(['test', 'debug']),
    ];
    
    echo "Attempting to create service with data:\n";
    print_r($testService);
    
    $service = Service::create($testService);
    
    echo "SUCCESS: Service created with ID: " . $service->id . "\n";
    
    // Verify it was saved
    $savedService = Service::find($service->id);
    echo "Verification - Service found in DB: " . ($savedService ? "YES" : "NO") . "\n";
    
    if ($savedService) {
        echo "Service details:\n";
        echo "- Title: " . $savedService->title . "\n";
        echo "- Status: " . $savedService->status . "\n";
        echo "- Active: " . ($savedService->is_active ? "YES" : "NO") . "\n";
        echo "- City: " . $savedService->city . "\n";
        echo "- Neighborhood: " . $savedService->neighborhood . "\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TOTAL SERVICES COUNT ===\n";
echo "Total services in DB: " . Service::count() . "\n";

echo "\n=== LATEST 5 SERVICES ===\n";
$latestServices = Service::latest()->take(5)->get(['id', 'title', 'status', 'is_active', 'created_at']);
foreach ($latestServices as $service) {
    echo "ID: {$service->id} - {$service->title} - {$service->status} - " . ($service->is_active ? "ACTIVE" : "INACTIVE") . " - {$service->created_at}\n";
}
