<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;
use App\Services\SlackService;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\HRM;
use App\Models\StockRequest;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Project;
use App\Models\Approval;
use App\Models\MaterialRequest;




class Production extends Controller
{
    public function index()
    {
        $ongoingProjects = Project::where('status', '!=', 'Complete')->latest()->take(5)->get();
        $pendingProjects = Project::where('status', 'Delivery / collection')->latest()->take(5)->get();
        $recentProjects = Project::latest()->take(5)->get();
    
            return view('Production.index', compact('ongoingProjects', 'pendingProjects', 'recentProjects'));

      
    }

public function create()
{
    $employees = DB::table('employees')
        ->join('departments', 'employees.department_id', '=', 'departments.id')
        ->where('departments.name', 'Production')
        ->select('employees.*', 'departments.name as department_name')
        ->get();

    $products = Product::all(); 

    return view('Production.CreateProject', compact('employees', 'products'));
}

 
        public function store(Request $request)
        {

            // dd($request->all()); 
            // Validate the incoming request
            $validated = $request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
                'client_name' => 'required|string|max:255',
                'sizes' => 'required|array',
                'quantities' => 'required|array',
                'assigned_to' => 'required|array',
                'products' => 'required|array',
                'product_quantities' => 'required|array',
                // 'product_quantities.*' => 'required|integer|min:1', 
'project_type' => 'required|string|in:Embroidery,DTF Printing,PVC/Flex Printing,Vinyl Printing,Paper Printing,Video Flex Printing,Screen Printing,Pull up Banner Print,Backdrop Banner,Telescopic Flags,Cut and Print',
                    
                'estimated_minutes' => 'required|numeric|min:0|max:59',
                'estimated_seconds' => 'required|numeric|min:0|max:59',

            ]);
    
            // Handle the artwork file upload
            $artworkPath = null;
            if ($request->hasFile('artwork')) {
                $artworkPath = $request->file('artwork')->store('artworks', 'public');
            }
            $estimatedHours = $request->input('estimated_hours');
            $estimatedMinutes = $request->input('estimated_minutes');
            $estimatedSeconds = $request->input('estimated_seconds');
            
            // Round seconds to the nearest minute
            $roundedSeconds = round($estimatedSeconds / 60);
            
            // Convert everything to minutes
            $totalEstimatedTimeInMinutes = ($estimatedHours * 60) + $estimatedMinutes + $roundedSeconds;
            
            // Create the project
            $project = Project::create([
                'artwork' => $artworkPath,
                'client_name' => $request->input('client_name'),
                'creator' => auth()->user()->name,
                'sizes' => json_encode($request->input('sizes')), 
                'quantities' => json_encode($request->input('quantities')),
                'assigned_employees' => json_encode($request->input('assigned_to')),
                'products' => json_encode($request->input('products')),
                'project_type' => $request->input('project_type'), 
                'status' => 'Client Approval Pending',
                'estimated_time' => $totalEstimatedTimeInMinutes, 

            ]);

       

            // if (count($request->products) !== count($request->product_quantities)) {
            //     return back()->withErrors(['Product and quantity counts do not match.']);
            // }


            $stockRequest = StockRequest::create([
                'products' => json_encode($request->input('products')),
                'quantities' => json_encode($request->input('product_quantities')),
                'status' => 'Pending',
                'request_by' => auth()->user()->name,
            ]);
        

            $this->sendSlackProjectNotification($project);
            // Fetch the Inventory Manager from the HRM model (employees table)
         // Fetch the Inventory Manager from the HRM model (employees table)
                $inventoryManager = HRM::where('position', 'Inventory_manager')->first();

                if ($inventoryManager) {
                    // Extract the email from the inventory manager
                    $inventoryManagerEmail = $inventoryManager->email;

                    // Send Slack notification to the inventory manager
                    $this->sendSlackStockRequestNotification($inventoryManagerEmail, $stockRequest);
                }
            // Redirect back with success message
            return redirect()->route('CreateProject')->with('success', 'Project created successfully!');
        }
    
    
private function sendSlackProjectNotification($project)
{
    $slackToken = env('SLACK_BOT_TOKEN');
    $productionChannelId = 'C08JE71H2T0';

    // Step 1: Prepare project details to send
    $projectDetails = "*New Project Created:* {$project->client_name}\n\n"
        . "*Project Type:* {$project->project_type}\n"
        . "*Assigned Employees:* " . implode(', ', json_decode($project->assigned_employees)) . "\n"
        . "*Sizes:* " . implode(', ', json_decode($project->sizes)) . "\n"
        . "*Quantities:* " . implode(', ', json_decode($project->quantities)) . "\n"
        . "*Status:* {$project->status}";

    // Step 2: Add the link to the artwork approval form
    $artworkApprovalLink = route('Production', ['project' => $project->id]);
    $projectDetails .= "\n*View Approval Form:* {$artworkApprovalLink}";

    // Step 3: Send Slack message to the production channel
    $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $productionChannelId,
        'text' => $projectDetails,
    ]);

    $slackData = $slackResponse->json();

    if (!$slackData['ok']) {
        Log::error("Failed to send Slack message to production channel: " . json_encode($slackData));
    }
}


public function sendSlackStockRequestNotification($inventoryManagerEmail, $stockRequest)
{
    $slackToken = env('SLACK_BOT_TOKEN'); // Slack Bot Token

    // Debug: Check the supervisor email before making the API request
    \Log::info('Inventory Manager Email for Slack:', ['email' => $inventoryManagerEmail]);

    // Step 1: Get Slack User ID from Email
    $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
        'email' => $inventoryManagerEmail 
    ]);

    $userData = $userResponse->json();

    if (!$userData['ok']) {
        Log::error("Failed to fetch Slack User ID for $inventoryManagerEmail: " . json_encode($userData));
        return;
    }

    $userId = $userData['user']['id'];

    // Step 2: Open a DM with the User
    $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
        'users' => $userId
    ]);

    $dmData = $dmResponse->json();

    if (!$dmData['ok']) {
        Log::error("Failed to open DM with $inventoryManagerEmail ($userId): " . json_encode($dmData));
        return;
    }

    $dmChannel = $dmData['channel']['id'];

    // Step 3: Send Slack message with stock request details
    // Step 3: Send Slack message with stock request details
$slackMessage = "*New Stock Request Submitted*\n"
. "Request By: " . $stockRequest->request_by . "\n"
. "Product details: " . implode(', ', json_decode($stockRequest->products, true)) . "\n"
. "Quantities: " . implode(', ', json_decode($stockRequest->quantities, true)) . "\n"
. "Cleaning Solution: " . $stockRequest->cleaning_solution . "\n"
. "Please review it at https://your-portal-url.com/stock-requests/{$stockRequest->id}";


    $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $dmChannel,
        'text' => $slackMessage
    ]);

    $slackData = $slackResponse->json();

    if (!$slackData['ok']) {
        Log::error("Failed to send Slack message to $inventoryManagerEmail: " . json_encode($slackData));
    }
}

public function Manage()
{
    $projects = Project::where('status', '!=', 'Completed')->get();
    return view('Production.ManageProjects', compact('projects'));
}

public function History()
{
    $completedProjects = Project::where('status', 'Completed')->get();
    return view('Production.ProjectHistory', compact('completedProjects'));
}
/**
 * Show the form for editing a project.
 */
public function edit($id)
{
    $project = Project::findOrFail($id);
    return view('backend.projects.edit', compact('project'));
}

/**
 * Update the project in storage.
 */
public function update(Request $request, $id)
{
    $project = Project::findOrFail($id);
    $project->update($request->all());

    return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
}

/**
 * Remove the project from storage.
 */
public function destroy($id)
{
    $project = Project::findOrFail($id);
    $project->delete();

    return redirect()->route('projects.manage')->with('success', 'Project deleted successfully!');
}

/**
 * Show the details of a project.
 */
public function show($id)
{
    $project = Project::findOrFail($id);
    return view('Production.ViewProject', compact('project'));
}


// Change controller parameter:
public function Approval(Request $request, $project_id)
{
    // Validate request inputs
    $request->validate([
        'checklist' => 'nullable|array',
        'proof_status' => 'nullable|array',
        'client_name' => 'required|string|max:255',
        'approval_date' => 'required|date',
        'signature' => 'required|string|starts_with:data:image/',
        'confirmation' => 'required|accepted'
    ], [
        'confirmation.accepted' => 'You must accept the color reproduction waiver',
    ]);

    // Store approval data
    $approval = Approval::create([
        'project_id' => $project_id,
        'checklist' => json_encode($request->input('checklist', [])), 
        'proof_status' => json_encode($request->input('proof_status', [])), 
        'client_name' => $request->input('client_name'),
        'approval_date' => $request->input('approval_date'),
        'signature' => $request->input('signature'),
        'confirmation' => $request->boolean('confirmation'),
    ]);

    // Update project status
    Project::where('id', $project_id)->update(['status' => 'Artwork Approved']);

    // Send Slack Notification
    $this->sendSlackApprovalNotification($project_id);

    return redirect()->route('viewProject', $project_id)->with('success', 'Approval saved successfully.');
}


public function sendSlackApprovalNotification($project_id)
{
    $slackToken = env('SLACK_BOT_TOKEN'); // Slack Bot Token

    // Find the Production Manager in HRM model
    $productionManager = HRM::where('position', 'Production_Manager')->first();

    if (!$productionManager) {
        Log::error("No Production Manager found in HRM.");
        return;
    }

    $managerEmail = $productionManager->email;

    // Step 1: Get Slack User ID from Email
    $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
        'email' => $managerEmail
    ]);

    $userData = $userResponse->json();

    if (!$userData['ok']) {
        Log::error("Failed to fetch Slack User ID for $managerEmail: " . json_encode($userData));
        return;
    }

    $userId = $userData['user']['id'];

    // Step 2: Open a DM with the User
    $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
        'users' => $userId
    ]);

    $dmData = $dmResponse->json();

    if (!$dmData['ok']) {
        Log::error("Failed to open DM with $managerEmail ($userId): " . json_encode($dmData));
        return;
    }

    $dmChannel = $dmData['channel']['id'];

    // Step 3: Fetch project details
    $project = Project::find($project_id);

    if (!$project) {
        Log::error("Project with ID $project_id not found.");
        return;
    }

    // Step 4: Send Slack message
    $slackMessage = "*Approval Notification*\n"
        . "Project: *{$project->name}*\n"
        . "Status: *Artwork Approved*\n"
        . "Approved By: {$project->approved_by}\n"
        . "Approval Date: {$project->updated_at}\n"
        . "View project: https://your-portal-url.com/projects/{$project_id}";

    $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $dmChannel,
        'text' => $slackMessage
    ]);

    $slackData = $slackResponse->json();

    if (!$slackData['ok']) {
        Log::error("Failed to send Slack message to $managerEmail: " . json_encode($slackData));
    }
}

public function updateStatus(Request $request)
{
    $request->validate([
        'project_id' => 'required|exists:projects,id',
        'status' => 'required|string',
    ]);

    $project = Project::findOrFail($request->project_id);
    $project->status = $request->status;
    $project->updated_by_name =  auth()->user()->name;
    $project->status_updated_at = now();
    $project->save();

    return response()->json(['status' => $project->status]);
}


public function CreateRequisition()
{
    $user = Auth::user();
    $employee = HRM::where('user_id', $user->id)->first();
    $items = Product::all(); 
    return view('Production.stockrequest', compact('employee', 'items'));
}


public function storerequest(Request $request)
{
    $validated = $request->validate([
        'requested_by' => 'required',
        'purpose' => 'required',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $materialRequest = MaterialRequest::create([
        'requested_by' => $validated['requested_by'],
        'purpose' => $validated['purpose'],
    ]);

    foreach ($validated['items'] as $item) {
        $materialRequest->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
        ]);
    }

    return redirect()->route('Production.request')
        ->with('success', 'You can now collect Material from  Stores Officer.');
}


        
}

