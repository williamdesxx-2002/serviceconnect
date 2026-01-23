<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TestRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user registration process';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing user registration...');
        
        // Test data
        $testData = [
            'name' => 'Test User ' . time(),
            'email' => 'test' . time() . '@example.com',
            'phone' => '+24112345678',
            'role' => 'client',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'whatsapp_notifications' => true,
        ];
        
        // Validate data
        $validator = Validator::make($testData, [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'role' => ['required', 'in:client,provider'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);
        
        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  - ' . $error);
            }
            return Command::FAILURE;
        }
        
        try {
            // Create user
            $user = User::create([
                'name' => $testData['name'],
                'email' => $testData['email'],
                'phone' => $testData['phone'],
                'whatsapp_number' => $testData['whatsapp_number'] ?? null,
                'whatsapp_notifications' => $testData['whatsapp_notifications'] ?? true,
                'role' => $testData['role'],
                'password' => Hash::make($testData['password']),
            ]);
            
            $this->info('✅ User created successfully!');
            $this->info('   ID: ' . $user->id);
            $this->info('   Name: ' . $user->name);
            $this->info('   Email: ' . $user->email);
            $this->info('   Role: ' . $user->role);
            $this->info('   Created at: ' . $user->created_at);
            
            // Test authentication
            $this->info('\nTesting authentication...');
            if (\Auth::attempt(['email' => $testData['email'], 'password' => $testData['password']])) {
                $this->info('✅ Authentication successful!');
                $this->info('   Authenticated user: ' . \Auth::user()->name);
                \Auth::logout();
            } else {
                $this->error('❌ Authentication failed!');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error creating user: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        // Show recent users
        $this->info('\nRecent users (last 5):');
        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'role', 'created_at']);
        foreach ($recentUsers as $user) {
            $this->info('   ID: ' . str_pad($user->id, 3) . ' | ' . 
                           str_pad($user->name, 20) . ' | ' . 
                           str_pad($user->email, 30) . ' | ' . 
                           str_pad($user->role, 10) . ' | ' . 
                           $user->created_at);
        }
        
        return Command::SUCCESS;
    }
}
