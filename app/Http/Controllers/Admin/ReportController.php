<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'total_services' => Service::count(),
            'total_bookings' => Booking::count(),
            'total_categories' => Category::count(),
            'active_users' => User::where('is_active', true)->count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),
            'reported_services' => Service::where('is_reported', true)->count(),
            'reported_bookings' => Booking::where('is_reported', true)->count(),
        ];
        
        // Revenus mensuels
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Services par catégorie
        $servicesByCategory = Category::withCount('services')
            ->orderBy('services_count', 'desc')
            ->limit(10)
            ->get();
            
        // Utilisateurs récents
        $recentUsers = User::latest()
            ->limit(10)
            ->get();
            
        // Réservations récentes
        $recentBookings = Booking::with(['service', 'client', 'service.user'])
            ->latest()
            ->limit(10)
            ->get();
            
        return view('admin.reports.index', compact(
            'stats', 
            'monthlyRevenue', 
            'servicesByCategory', 
            'recentUsers', 
            'recentBookings'
        ));
    }
    
    public function revenue()
    {
        // Revenus par période
        $period = request('period', 'month');
        
        if ($period == 'year') {
            $revenue = Booking::selectRaw('YEAR(created_at) as period, SUM(total_amount) as revenue')
                ->where('status', 'completed')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } elseif ($period == 'week') {
            $revenue = Booking::selectRaw('WEEK(created_at) as period, SUM(total_amount) as revenue')
                ->where('status', 'completed')
                ->whereYear('created_at', date('Y'))
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } else {
            $revenue = Booking::selectRaw('MONTH(created_at) as period, SUM(total_amount) as revenue')
                ->where('status', 'completed')
                ->whereYear('created_at', date('Y'))
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }
        
        // Top prestataires
        $topProviders = User::where('role', 'provider')
            ->withCount(['services' => function($query) {
                $query->where('status', 'active');
            }])
            ->withSum(['services.bookings' => function($query) {
                $query->where('status', 'completed');
            }], 'total_revenue')
            ->orderBy('services_bookings_sum_total_amount', 'desc')
            ->limit(10)
            ->get();
            
        return view('admin.reports.revenue', compact('revenue', 'topProviders', 'period'));
    }
    
    public function users()
    {
        $users = User::withCount(['services', 'clientBookings', 'providerBookings'])
            ->withSum(['clientBookings' => function($query) {
                $query->where('status', 'completed');
            }], 'total_spent')
            ->withSum(['providerBookings' => function($query) {
                $query->where('status', 'completed');
            }], 'total_earned')
            ->latest()
            ->paginate(20);
            
        return view('admin.reports.users', compact('users'));
    }
    
    public function services()
    {
        $services = Service::with(['user', 'category'])
            ->withCount(['bookings' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['bookings' => function($query) {
                $query->where('status', 'completed');
            }], 'total_revenue')
            ->latest()
            ->paginate(20);
            
        return view('admin.reports.services', compact('services'));
    }
    
    public function bookings()
    {
        $bookings = Booking::with(['service', 'client', 'service.user'])
            ->latest()
            ->paginate(20);
            
        return view('admin.reports.bookings', compact('bookings'));
    }
    
    public function activity()
    {
        $activities = DB::table('user_activities')
            ->join('users', 'user_activities.user_id', '=', 'users.id')
            ->leftJoin('users as admins', 'user_activities.admin_id', '=', 'admins.id')
            ->select('user_activities.*', 'users.name as user_name', 'users.email as user_email', 
                   'admins.name as admin_name')
            ->latest('user_activities.created_at')
            ->paginate(50);
            
        return view('admin.reports.activity', compact('activities'));
    }
    
    public function warnings()
    {
        $warnings = DB::table('warnings')
            ->join('users', 'warnings.user_id', '=', 'users.id')
            ->leftJoin('users as admins', 'warnings.admin_id', '=', 'admins.id')
            ->select('warnings.*', 'users.name as user_name', 'users.email as user_email',
                   'admins.name as admin_name')
            ->latest('warnings.created_at')
            ->paginate(20);
            
        return view('admin.reports.warnings', compact('warnings'));
    }
}
