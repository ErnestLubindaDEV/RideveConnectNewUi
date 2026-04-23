<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Project;
use App\Models\LeaveApplication;
use App\Models\StockHistory;
use App\Models\AttendanceLog;


class RideveConnectController extends Controller
{
    public function index()
    {
        return view('HRM.index');
    }

    public function dashboard()
    {
        // 1. Employee analytics
        $totalEmployees = DB::table('employees')->count();
        $departmentsCount = DB::table('departments')->count();
    
        // 2. Deal analytics
        // $totalDeals = DB::table('deals')->count();
        // $totalDealValue = DB::table('deals')->sum('value');
        // $averageDealValue = DB::trable('deals')->avg('value');
    
        // 3. Leads
        // $totalLeads = DB::table('leads')->count();
        // $newLeads = DB::table('leads')->where('status', 'New')->count();
    
        // 4. Products & Categories
        $totalProducts = DB::table('products')->count();
        $finishedGoods = DB::table('products')->where('type', 'finished_goods')->count();
        $rawMaterials = DB::table('products')->where('type', 'raw_material')->count();
        $totalCategories = DB::table('categories')->count();
    
        // 5. Company assets
        $totalAssets = DB::table('company_assets')->count();
        $goodAssets = DB::table('company_assets')->where('condition', 'Good')->count();
    
        // 6. Leave applications
        $pendingLeaves = DB::table('leave_applications')->where('status', 'Pending')->count();
        $approvedLeaves = DB::table('leave_applications')->where('status', 'Approved')->count();
    
        // 7. Production orders
        $totalProductionOrders = DB::table('production_orders')->count();
        $completedOrders = DB::table('production_orders')->where('status', 'completed')->count();
    
        // 8. Projects
        $totalProjects = DB::table('projects')->count();
        $activeProjects = DB::table('projects')->where('status', '!=', 'completed')->count();

        $ongoingProjects = Project::where('status', '!=', 'Complete')->latest()->take(7)->get();
$ongoingLeave = LeaveApplication::whereNotIn('status', ['Completed', 'Pending'])
    ->latest()
    ->take(7)
    ->get();
        $recentStockActivities = StockHistory::with('product')->latest()->take(7)->get();

$averageReportingTime = AttendanceLog::where('employee_id', '!=', 0)
        ->select(DB::raw('SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(event_time)))) as avg_time'))
        ->value('avg_time');

    $formattedAvgTime = $averageReportingTime 
        ? \Carbon\Carbon::createFromFormat('H:i:s', explode('.', $averageReportingTime)[0])->format('H:i') 
        : '00:00';

       $dailyQuote = Cache::remember('agency_quote', now()->endOfDay(), function () {
        try {
            $response = Http::get('https://zenquotes.io/api/today');
            return $response->successful() ? $response->json()[0] : null;
        } catch (\Exception $e) {
            return ['q' => "Design is intelligence made visible.", 'a' => "Alina Wheeler"];
        }
    });

        return view('dashboard', compact(
            'totalEmployees',
            'departmentsCount',
            'totalProducts',
            'finishedGoods',
            'rawMaterials',
            'totalCategories',
            'totalAssets',
            'goodAssets',
            'pendingLeaves',
            'approvedLeaves',
            'totalProductionOrders',
            'completedOrders',
            'totalProjects',
            'ongoingProjects',
            'activeProjects',
            'ongoingLeave',
            'dailyQuote',
            'formattedAvgTime',
            'recentStockActivities'
        ));
    }

     public function documentation()
    {
        return view('HRM.documentation');
    }


}   