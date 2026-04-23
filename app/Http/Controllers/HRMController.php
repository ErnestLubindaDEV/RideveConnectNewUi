<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HRM;
use App\Models\User;
use App\Models\Department;
use App\Models\LeaveApplication;
use App\Models\MothersDaySubmission;
use Illuminate\Support\Facades\Http;
use App\Models\CompanyAsset;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Mail\LeaveApplicationMail;
use App\Mail\LeaveApprovalMail;
use App\Mail\LeaveApprovalWithPdfMail;
use App\Models\Memo;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\SlackAlert;
use Illuminate\Support\Facades\Notification;
use App\Services\SlackService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class HRMController extends Controller
{
    public function index()
    {
        // dd(Auth::user()->profile_picture);

        $employee = HRM::where('user_id', Auth::id())->first(); 
    
        // if (!$employee) {
        //     return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        // }
    
        return view('HRM.index', compact('employee'));
    }

    public function AddEmployee()
    {
        $departments = \App\Models\Department::all();

        return view('HRM.AddEmployee', compact('departments'));
    }

public function ManageEmployees()
{
    $employees = DB::table('employees')
        ->join('departments', 'employees.department_id', '=', 'departments.id')
        ->select('employees.*', 'departments.name as department_name') 
        ->get();

    return view('HRM.ManageEmployees', compact('employees'));
}
    
    public function destroyemployee($employee_id)
    {
        // Find the employee using employee_id instead of the default 'id'
        $employee = HRM::findOrFail($employee_id);
        
        // Delete the employee record
        $employee->delete();
    
        // Redirect back with a success message
        return redirect()->route('ManageEmployees')->with('success', 'Employee deleted successfully.');
    }
    
    public function editEmployee($employee_id)
    {
        $employees = DB::table('employees')
        ->join('departments', 'employees.department_id', '=', 'departments.id')
        ->select('employees.*', 'departments.name as department_name') 
        ->get();

        $departments = \App\Models\Department::all();

        return view('HRM.ManageEmployees', compact('employees', 'departments'));
    }

    public function updateEmployee(Request $request, $employee_id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email,' . $employee_id . ',employee_id', 
            'phone_number' => 'required|string|max:15',
            'nrc' => 'required|string|max:255|unique:employees,nrc,' . $employee_id . ',employee_id', 
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contract_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'address' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'leave_days' => 'required|integer',
            'driver_license' => 'nullable|file|mimes:pdf|max:2048',
        ]);
    
        $employee = HRM::findOrFail($employee_id);
    
        // Handle file upload
        if ($request->hasFile('driver_license')) {
            $driverLicensePath = $request->file('driver_license')->store('uploads/driver_licenses', 'public');
$validated['driver_license'] = $driverLicensePath;

        }
    
        // Update the employee record
        $employee->update($validated);
    
        return redirect()->route('editEmployee', ['employee_id' => $employee_id])->with('success', 'Employee record updated Successfully.');
    }
    
 public function motherday()
{
    $user = auth()->user();
    if ($user->gender !== 'female' && $user->id !== 101) {
        abort(403);
    }

    // FIX: Define the missing variable
    $currentMonth = now()->format('F Y');

    // Define a list of nice colors for the randomization
    $colors = ['#727cf5', '#0acf97', '#fa5c7c', '#ffbc00', '#39afd1', '#6c757d', '#e83e8c'];

    $events = \App\Models\MothersDaySubmission::all()->map(function($submission) use ($colors) {
        return [
            'title' => $submission->employee_name,
            'start' => $submission->selected_date,
            'description' => $submission->reason,
            // RANDOMIZE: Pick a color from the array
            'backgroundColor' => $colors[array_rand($colors)],
            'borderColor' => 'transparent',
            'allDay' => true
        ];
    });

    return view('HRM.mothersform', compact('events', 'currentMonth'));
}
  public function storemothersday(Request $request)
{
    $request->validate([
        'selected_date' => 'required|date',
        'reason' => 'required|string',
    ]);

    // THE FIX: Check if anyone has already claimed this date
    $exists = \App\Models\MothersDaySubmission::where('selected_date', $request->selected_date)->exists();

    if ($exists) {
        return redirect()->back()
            ->withInput() // Keeps their reason typed in
            ->withErrors(['selected_date' => 'Sorry, this date is already fully booked for Mother\'s Day!']);
    }

    // If no one has claimed it, proceed to save
    \App\Models\MothersDaySubmission::create([
        'user_id' => auth()->id(),
        'employee_name' => auth()->user()->name,
        'selected_date' => $request->selected_date,
        'reason' => $request->reason,
    ]);

    return redirect()->back()->with('success', 'Date successfully reserved on the calendar!');
}

    public function ManageAssets()
    {
        $Assets = CompanyAsset::all(); 
        $totalCost = CompanyAsset::sum('asset_cost'); // Sum all asset costs
    
        return view('HRM.ManageAssets', compact('Assets', 'totalCost')); 
    }


   public function store(Request $request)
{
    \Log::info('Form data:', $request->all());

    // 1. Validate the incoming request data
    try {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:15',
            'email' => 'nullable|email|unique:users,email',
            'nrc' => 'required|string|max:255',
            'license' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|in:Male,Female',
            'nationality' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'contract_type' => 'required|string|max:255',
            'start' => 'required|date',
            'address' => 'required|string|max:255',
            'national_id' => 'required|file|mimes:pdf|max:10048',
            'driver_license' => 'nullable|file|mimes:pdf|max:2048',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Errors: ' . json_encode($e->errors()));
        return redirect()->back()->withErrors($e->errors())->withInput();
    }

    $department = Department::find($validatedData['department_id']);

    // 2. Handle file uploads
    $nationalIdPath = $request->hasFile('national_id')
        ? $request->file('national_id')->store('uploads/national_ids', 'public')
        : null;

    $driverLicensePath = $request->hasFile('driver_license')
        ? $request->file('driver_license')->store('uploads/driver_licenses', 'public')
        : null;

    // 3. Generate credentials
    $password = Str::random(10);
    $email = $request->input('email');

    // Generate internal email if none provided
    if (!$email) {
        $email = strtolower($request->input('firstname') . '.' . $request->input('lastname'))
            . rand(100, 999) . '@ridevemedia.com';
    }  

    // 4. Create the User (The login account)
    $user = User::create([
        'name' => $request->input('firstname') . ' ' . $request->input('lastname'),
        'email' => $email,
        'phone_number' => $request->input('phonenumber'), // CRITICAL: Added for phone login
        'password' => Hash::make($password),
    ]);
    
    // 5. Send welcome email
    $isFallback = !$request->filled('email');
    $recipientEmail = $isFallback ? 'ernest@ridevemedia.com' : $user->email;
    Mail::to($recipientEmail)->send(new WelcomeEmail($user, $password, $isFallback));
        
    // 6. Create the Employee Profile (The HRM record)
    HRM::create([
        'user_id' => $user->id,
        'full_name' => $request->input('firstname') . ' ' . $request->input('lastname'),
        'phone_number' => $request->input('phonenumber'),
        'email' => $email, // Use the same email variable (generated or provided)
        'NRC' => $request->input('nrc'),
        'gender' => $request->input('gender'),
        'license_number' => $request->input('license'),
        'dob' => $request->input('dob'),
        'nationality' => $request->input('nationality'),
        'department_id' => $department->id,
        'department' => $department->name,
        'position' => $request->input('position'),
        'contract_type' => $request->input('contract_type'),
        'start_date' => $request->input('start'),
        'address' => $request->input('address'),
        'national_id' => $nationalIdPath,
        'driver_license' => $driverLicensePath,
    ]);

    return redirect()->route('AddEmployee')->with('success', 'Employee and user added successfully.');
}
        

    public function Applyforleave($employeeId)
    {
        $employee = HRM::find($employeeId); 
        return view('HRM.Applyforleave', compact('employee'));
    }

    public function AddAsset()
    {
        $employees = HRM::all();

        return view('HRM.AddAsset', compact('employees'));
    }

    public function storeAsset(Request $request)
{
    \Log::info('Form data:', $request->all());

    // Validate the incoming request data
    $request->validate([
        'asset_name' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255',
        'condition' => 'required|string',
        'asset_cost' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'purchase_date' => 'required|date',
        'collection_date' => 'required|date',
        'asset_type' => 'required|string',
        'warranty_expiry' => 'nullable|date',
        'assigned_to' => 'required|string', // Assuming this is employee's name
        'signature' => 'required|string', // Base64 signature string
    ]);

    // Retrieve the employee's email by their name
    $employee = HRM::where('full_name', $request->input('assigned_to'))->first();

    if (!$employee) {
        return back()->withErrors(['assigned_to' => 'Employee not found.']);
    }

    // Create a new company asset
    $asset = new CompanyAsset();
    $asset->asset_name = $request->input('asset_name');

    $asset->asset_number = $request->input('serial_number');
    $asset->condition = $request->input('condition');
    $asset->description = $request->input('description');
    $asset->purchase_date = $request->input('purchase_date');
    $asset->collection_date = $request->input('collection_date');
    $asset->asset_type = $request->input('asset_type');
    $asset->assigned_by = Auth::user()->name; // Get the name of the logged-in user
    $asset->warranty_expiry = $request->input('warranty_expiry');
    $asset->assigned_to = $request->input('assigned_to');
    $asset->signature = $request->input('signature');
    $asset->asset_cost = $request->input('asset_cost');
    
    
    // Save the asset to the database
    $asset->save();

    // Generate PDF for the asset details
    $pdf = Pdf::loadView('emails.asset', compact('asset')); // Generate the PDF for the asset
    $pdfContent = $pdf->output();

    // Send email to the assigned employee
    Mail::send([], [], function ($mail) use ($asset, $pdfContent,  $employee) {
        $mail->to( $employee->email)
            ->subject("New Asset Assigned: {$asset->asset_name}")
            ->from('it@ridevemedia.com', 'Asset Management')
            ->html("
            <p>Dear {$asset->assigned_to},</p>
            <p>We are pleased to inform you that a new asset has been assigned to you. Please find the attached your asset agreement form containing the full details of the assigned asset for your reference.</p>
            <p>Should you have any questions or require further information, please do not hesitate to reach out to human resource department.</p>
            <p>Best regards,</p>
            <p><strong>Rideve Connect</strong><br />
            Rideve Media<br />
           it@ridevemedia.com</p>
        ")            ->attachData($pdfContent, 'asset_details.pdf', ['mime' => 'application/pdf']);
    });

    // Send Slack notification
    $this->sendSlackAssetNotification($asset,  $employee);

    // Redirect back with a success message
    return redirect()->route('AddAsset')->with('success', 'Asset added successfully and notification sent!');
}


private function sendSlackAssetNotification($asset,  $employee)
{
    $slackToken = env('SLACK_BOT_TOKEN');
    $assignedToEmail = $employee->email;

    // Step 1: Get Slack User ID from Email
    $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
        'email' => $assignedToEmail
    ]);

    $userData = $userResponse->json();

    if (!$userData['ok']) {
        Log::error("Failed to fetch Slack User ID for $assignedToEmail: " . json_encode($userData));
        return;
    }

    $userId = $userData['user']['id'];

    // Step 2: Open a DM with the User
    $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
        'users' => $userId
    ]);

    $dmData = $dmResponse->json();

    if (!$dmData['ok']) {
        Log::error("Failed to open DM with $assignedToEmail ($userId): " . json_encode($dmData));
        return;
    }

    $dmChannel = $dmData['channel']['id'];

    // Step 3: Send Slack message with details
    $slackMessage = "*New Asset Assigned:* {$asset->asset_name}\n\n"
        . "*Asset Number:* {$asset->asset_number}\n"
        . "*Condition:* {$asset->condition}\n\n"
        . "You have been assigned a new company asset. Please check your email for your asset agreement form.";

    $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $dmChannel,
        'text' => $slackMessage
    ]);

    $slackData = $slackResponse->json();

    if (!$slackData['ok']) {
        Log::error("Failed to send Slack message to $assignedToEmail: " . json_encode($slackData));
    }
}

private function sendSlackLeaveNotification($supervisor_email, $leaveApplication)
{
    $slackToken = env('SLACK_BOT_TOKEN'); // Slack Bot Token
    $supervisorEmail = $supervisor_email; // Assuming you have supervisor email in leave application

    // Debug: Check the supervisor email before making the API request
    \Log::info('Supervisor Email for Slack:', ['email' => $supervisorEmail]);

    // Step 1: Get Slack User ID from Email
    $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
        'email' => $supervisorEmail
    ]);

    $userData = $userResponse->json();

    if (!$userData['ok']) {
        Log::error("Failed to fetch Slack User ID for $supervisorEmail: " . json_encode($userData));
        return;
    }

    $userId = $userData['user']['id'];

    // Step 2: Open a DM with the User
    $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
        'users' => $userId
    ]);

    $dmData = $dmResponse->json();

    if (!$dmData['ok']) {
        Log::error("Failed to open DM with $supervisorEmail ($userId): " . json_encode($dmData));
        return;
    }

    $dmChannel = $dmData['channel']['id'];

    // Step 3: Send Slack message with leave application details
    $slackMessage = "*New Leave Application Submitted by an employee in your department\n"
        . "Please review it at https://ridevemedia.com/portal";
  
    $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $dmChannel,
        'text' => $slackMessage
    ]);

    $slackData = $slackResponse->json();

    if (!$slackData['ok']) {
        Log::error("Failed to send Slack message to $supervisorEmail: " . json_encode($slackData));
    }

}

private function sendSlackNotification($subject, $message, $memoBy, $recipients, $sendToChannel)    
{
    $slackToken = env('SLACK_BOT_TOKEN');

    foreach ($recipients as $email) {
        // Step 1: Get Slack User ID from Email
        $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
            'email' => $email
        ]);

        $userData = $userResponse->json();

        if (!$userData['ok']) {
            Log::error("Failed to fetch Slack User ID for $email: " . json_encode($userData));
            continue;
        }

        $userId = $userData['user']['id'];

        // Step 2: Open a DM with the User
        $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
            'users' => $userId
        ]);

        $dmData = $dmResponse->json();

        if (!$dmData['ok']) {
            Log::error("Failed to open DM with $email ($userId): " . json_encode($dmData));
            continue;
        }

        $dmChannel = $dmData['channel']['id'];

        // Step 3: Send Slack message with details
        $slackMessage = "*Memo Subject:* {$subject}\n\n{$message}\n\n_Memo by: {$memoBy}_\n\nYou have received a memo via email, please check your email inbox .";

        $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
            'channel' => $dmChannel,
            'text' => $slackMessage
        ]);

        $slackData = $slackResponse->json();

        if (!$slackData['ok']) {
            Log::error("Failed to send Slack message to $email: " . json_encode($slackData));
        }
    }
}

private function sendleaveApprovalNotification($subject, $message, $memoBy, $recipients, $sendToChannel)    
{
    $slackToken = env('SLACK_BOT_TOKEN');

    foreach ($recipients as $email) {
        // Step 1: Get Slack User ID from Email
        $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
            'email' => $email
        ]);

        $userData = $userResponse->json();

        if (!$userData['ok']) {
            Log::error("Failed to fetch Slack User ID for $email: " . json_encode($userData));
            continue;
        }

        $userId = $userData['user']['id'];

        // Step 2: Open a DM with the User
        $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
            'users' => $userId
        ]);

        $dmData = $dmResponse->json();

        if (!$dmData['ok']) {
            Log::error("Failed to open DM with $email ($userId): " . json_encode($dmData));
            continue;
        }

        $dmChannel = $dmData['channel']['id'];

        // Step 3: Send Slack message with details
        $slackMessage = "*Memo Subject:* {$subject}\n\n{$message}\n\n_Memo by: {$memoBy}_\n\nYou have received a memo via email, please check your email inbox .";

        $slackResponse = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
            'channel' => $dmChannel,
            'text' => $slackMessage
        ]);

        $slackData = $slackResponse->json();

        if (!$slackData['ok']) {
            Log::error("Failed to send Slack message to $email: " . json_encode($slackData));
        }
    }
}

public function edit($id)
{
    $asset = CompanyAsset::findOrFail($id);
    $employees = HRM::all();

    return view('HRM.EditAsset', compact('asset', 'employees'));
}

public function destroy($id)
{
    $asset = CompanyAsset::findOrFail($id);
    $asset->delete();
    return redirect()->route('ManageAssets')->with('success', 'Asset deleted successfully.');
}

public function update(Request $request, $id)
{
    // Fetch the asset by ID
    $asset = CompanyAsset::findOrFail($id);

    // Store the previous assigned_to value
    $previousAssignedTo = $asset->assigned_to;

    // Validate the request data
    $validatedData = $request->validate([
        'asset_name' => 'required|string|max:255',
        'serial_number' => 'required|string|max:255',
        'asset_cost' => 'required|integer|min:1',
        'condition' => 'required|string|max:255',
        'description' => 'nullable|string',
        'purchase_date' => 'required|date',
        'collection_date' => 'nullable|date',
        'asset_type' => 'required|string|max:255',
        'assigned_by' => 'nullable|string|max:255',
        'warranty_expiry' => 'nullable|date',
        'assigned_to' => 'nullable|string|max:255',
        'signature' => 'nullable|string',
    ]);

    // If 'assigned_to' is changed, update the email of the new assignee
    if ($request->input('assigned_to') && $request->input('assigned_to') !== $previousAssignedTo) {
        $newEmployee = HRM::where('full_name', $request->input('assigned_to'))->first();

        if (!$newEmployee) {
            return back()->withErrors(['assigned_to' => 'New employee not found.']);
        }

        $validatedData['assigned_to'] = $newEmployee->email; // Store new assignee's email
    }

    // Update the asset
    $asset->update($validatedData);

    // If 'assigned_to' has changed, notify the new assignee
    // if ($request->input('assigned_to') && $request->input('assigned_to') !== $previousAssignedTo) {
    //     $this->notifyNewAssignee($asset);
    // }

    return redirect()->route('ManageAssets')->with('success', 'Asset updated successfully!');
}


public function viewEmployee($employee_id)
{
    // Fetch the employee record along with the associated user
    $employee = HRM::with('user')->find($employee_id);
     $departments = \App\Models\Department::all();


    if (!$employee) {
        return redirect()->back()->with('error', 'Employee not found.');
    }

    return view('HRM.ViewEmployee', compact('employee', 'departments'));
}


public function show($id)
{
    $asset =  CompanyAsset::findOrFail($id);
    return view('HRM.ViewAsset', compact('asset'));
}
 

public function submitLeaveApplication(Request $request)
{
    \Log::info('Leave Application Data Received:', $request->all());

    try {
        // Validate input data
        $validatedData = $request->validate([
            'full_name' => 'required|string',
            'employment_date' => 'required|date',
            'phone_number' => 'required|string',
            'emergency_contact' => 'required|string',
            'leave_type' => 'required|string',
            'leave_duration' => 'required|integer',
            'leave_from' => 'required|date',
            'leave_to' => 'required|date|after_or_equal:leave_from',
            'additional_notes' => 'nullable|string',
            'contract_type' => 'nullable|string', 
            'signature' => 'nullable|string',
        ]);

        // Find the employee submitting the leave
        $employee = HRM::where('full_name', $validatedData['full_name'])->first();

        // Initialize supervisor details as null
        $supervisor_id = null;
        $supervisor_name = null;
        $supervisor_email = null;

        if ($employee) {
            // Find the supervisor in the same department
            $supervisor = HRM::where('department_id', $employee->department_id)
                                  ->where('position', 'Supervisor')
                                  ->first();

            if ($supervisor) {
                $supervisor_id = $supervisor->id; 
                $supervisor_name = $supervisor->full_name; // Store supervisor's name
                $supervisor_email = $supervisor->email; // Store email for later use
            } else {
                \Log::warning('No Supervisor Found for Employee Department');
            }
        } else {
            \Log::warning('Employee Not Found');
        }

        // ✅ First, create the leave application with supervisor name
      // Assuming supervisor_email should be stored in the LeaveApplication
        $leaveApplication = LeaveApplication::create(array_merge($validatedData, [
            'supervisor_id' => $supervisor_id,
            'supervisor_name' => $supervisor_name, // Store supervisor name in DB
            'supervisor_email' => $supervisor_email // Store supervisor email
        ]));


        // ✅ Then, send the email (only if a supervisor was found)
        if ($supervisor_email) {
            Mail::to($supervisor_email)->send(new LeaveApplicationMail($leaveApplication));
            \Log::info('Leave application email sent to Supervisor:', ['email' => $supervisor_email]);
        }

        // ✅ Send Slack Notification to Supervisor (if supervisor exists)
        if ($supervisor_name) {
            $this->sendSlackLeaveNotification($supervisor_email, $leaveApplication);
        }

        return redirect()->back()->with('success', 'Leave application submitted successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Error:', $e->errors());
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        \Log::error('Error creating leave application:', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'An error occurred while submitting the leave application.');
    }
}

public function leave_index($employee_id)
{
    $employee = HRM::where('employee_id', $employee_id)->first();

    if (!$employee) {
        abort(404, 'Employee not found.');
    }

    // Exclude rejected & completed everywhere
    $excludedStatuses = ['Rejected', 'Completed'];

    // Admin & HR: see all except rejected & completed
    if ($employee->position === 'admin' || $employee->position === 'HR') {
        $leaveApplications = LeaveApplication::whereNotIn('status', $excludedStatuses)->get();
    }

    // Supervisors: see department employees except rejected & completed
    elseif ($employee->position === 'Supervisor') {
        $leaveApplications = LeaveApplication::whereNotIn('status', $excludedStatuses)
            ->where(function ($query) use ($employee) {
                $query->where('supervisor_name', $employee->full_name)
                      ->orWhere('full_name', $employee->full_name);
            })
            ->get();
    }

    // Employees: see only their own except rejected & completed
    elseif ($employee->employee_id == $employee_id) {
        $leaveApplications = LeaveApplication::where('full_name', $employee->full_name)
            ->whereNotIn('status', $excludedStatuses)
            ->get();
    }

    else {
        abort(403, 'Unauthorized action.');
    }

    return view('HRM.manage_leave_applications', compact('leaveApplications'));
}



public function view_leave($id)
{
    $leaveApplication = LeaveApplication::where('id', $id)->firstOrFail();

    return view('HRM.leave_details', compact('leaveApplication'));
}


public function memo()
{
    $memos = Memo::all(); // Fetch memos from the database
    return view('HRM/view_memos', compact('memos'));
}

public function showmemo($id)

{
    $memo = Memo::findOrFail($id);
    return view('HRM/view_memo', compact('memo'));
}
public function creatememo()
{
    $employees = HRM::all();
    return view('HRM.memo', compact('employees'));
}


public function sendMemo(Request $request)
{
    // Validate form inputs
    $validated = $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'memo_to' => 'required|array',
        'memo_to.*' => 'email',
        'memo_by' => 'required|string|max:255',
        'signature' => 'nullable|string',
    ]);

    $subject = $validated['subject'];
    $message = $validated['message'];
    $memoBy = $validated['memo_by'];
    $recipients = $validated['memo_to'];
    $signature = $validated['signature'];

    // If "All" is selected, send to all employees
    if (in_array('All', $recipients)) {
        $recipients = Employee::pluck('email')->toArray();
        $sendToChannel = true;
    } else {
        $sendToChannel = false;
    }

    // Store the memo
    $memo = Memo::create([
        'subject' => $subject,
        'message' => $message,
        'memo_by' => $memoBy,
        'memo_to' => json_encode($recipients),
        'signature' => $signature,
    ]);

    $pdf = Pdf::loadView('emails.memo', compact('subject', 'message', 'memoBy', 'signature'));
    $pdfContent = $pdf->output();

    // Send email to each recipient with the memo
    foreach ($recipients as $email) {
        Mail::send([], [], function ($mail) use ($email, $subject, $pdfContent, $memoBy) {
            $mail->to($email)
                ->subject($subject)
                ->from('it@ridevemedia.com', $memoBy)
                ->html("
                <p>Dear {$email},</p>
                <p>Please find the attached PDF document containing the details of the memo. Kindly review it at your earliest convenience and confirm that you have recieved it on Rideve Connect.</p>
                <p>If you have any questions or require further information, please feel free to reach out to the human resource department.</p>
                <p>Best regards,</p>
                <p><strong>Rideve Connect</strong><br />
                Rideve Media<br />
                it@ridevemedia.com</p>
            ")                ->attachData($pdfContent, 'memo.pdf', ['mime' => 'application/pdf']);
        });
    }

    // Send Slack notification without attaching the memo file
    $this->sendSlackNotification($subject, $message, $memoBy, $recipients, $sendToChannel);

    return redirect()->route('memos.create')->with('success', 'Memo sent successfully via email and Slack!');
}
    
public function attendance()
{
    // 'with' pulls the linked employee data in one single, fast query
    $attendances = \App\Models\DailyAttendance::with('employee')
        ->orderBy('work_date', 'desc')
        ->get();

    return view('HRM.attendance', compact('attendances'));
}

public function syncDevice()
{
    try {
        // This triggers the exact same logic as your terminal command
        \Illuminate\Support\Facades\Artisan::call('attendance:sync');
        
        $output = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Log::info("Manual Sync Triggered: " . $output);

        return redirect()->back()->with('success', 'Syncing complete! Latest logs have been collected.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Sync failed: ' . $e->getMessage());
    }
}

public function approve(Request $request, $id)
{
    // Validate request inputs
    $request->validate([
        'approval_status' => 'required|string|in:approved,rejected',
        'signature' => 'required',
    ]);

    // Find the leave application by ID
    $leaveApplication = LeaveApplication::findOrFail($id);
    $user = Auth::user();

    // Find the employee record for the logged-in user
    $employee = HRM::where('user_id', $user->id)->first();

    if (!$employee) {
        return back()->withErrors(['Unauthorized action. Employee record not found.']);
    }

    // If Supervisor is approving/rejecting
    if ($employee->position === 'Supervisor') {
        $leaveApplication->supervisor_signature = $request->signature;
        $leaveApplication->status = $request->approval_status === 'approved' ? 'Supervisor Approved' : 'Rejected';

        if ($leaveApplication->status === 'Rejected') {
            // Save status before returning
            $leaveApplication->save();
            
            // Notify employee
            $this->notifyEmployee($leaveApplication, "Your leave application has been rejected by your Supervisor.");
            
            return redirect()->route('leave.index', ['employee_id' => $employee->employee_id])
                ->with('error', 'Leave application rejected.');
        }

        // Send to HR if approved
        $this->notifyHR($leaveApplication);
    } 
    
    // If HR is approving/rejecting
    elseif ($employee->position === 'HR') {
        if ($leaveApplication->status !== 'Supervisor Approved') {
            return back()->withErrors(['HR can only approve a leave application after it has been approved by a Supervisor.']);
        }

        $leaveApplication->hr_signature = $request->signature;
        $leaveApplication->status = $request->approval_status === 'approved' ? 'Approved' : 'Rejected';

        if ($leaveApplication->status === 'Rejected') {
            // Save status before returning
            $leaveApplication->save();
            
            // Notify employee
            $this->notifyEmployee($leaveApplication, "Your leave application has been rejected by HR.");
            
            return redirect()->route('leave.index', ['employee_id' => $employee->employee_id])
                ->with('error', 'Leave application rejected.');
        }

        // Notify employee of approval
        $this->notifyEmployee($leaveApplication, "Your leave application has been approved by HR.");
    } 

    // Unauthorized access
    else {
        return back()->withErrors(['Unauthorized action.']);
    }

    // Save the leave application status
    $leaveApplication->save();

    return redirect()->route('leave.index', ['employee_id' => $employee->employee_id])
        ->with('success', 'Leave approval updated successfully.');
}


private function notifyHR($leaveApplication)
{
    $hr = HRM::where('position', 'HR')->first();
    if (!$hr) return;

    // Generate PDF
    $pdf = Pdf::loadView('emails.leave-approval', compact('leaveApplication'));
    $pdfContent = $pdf->output();

    // Send email to HR
    Mail::send('emails.leave-approval', compact('leaveApplication'), function ($mail) use ($hr, $pdfContent) {
        $mail->to($hr->email)
            ->subject('Leave Application for Review')
            ->from('it@ridevemedia.com', 'Rideve HR')
            ->attachData($pdfContent, 'leave-approval.pdf', ['mime' => 'application/pdf']);
    });

    // Send Slack Notification to HR
    $this->sendSlackNotification(
        'Leave Application for Review',
        "{$leaveApplication->employee_name} has submitted a leave request that has been approved by the Supervisor. Please review and process the request.",
        $hr->full_name,
        [$hr->email],
        false
    );
}


private function notifyEmployee($leaveApplication, $message)
{
    $employeeUser = User::where('name', $leaveApplication->full_name)->first();
    if (!$employeeUser || !filter_var($employeeUser->email, FILTER_VALIDATE_EMAIL)) {
        return;
    }

    // Generate PDF
    $pdf = Pdf::loadView('emails.leave-approval', compact('leaveApplication'));
    $pdfContent = $pdf->output();

    // Send email
    Mail::send('emails.leave-approval', compact('leaveApplication'), function ($mail) use ($employeeUser, $pdfContent) {
        $mail->to($employeeUser->email)
            ->subject('Leave Application Update')
            ->from('it@ridevemedia.com', 'Rideve HR')
            ->attachData($pdfContent, 'leave-approval.pdf', ['mime' => 'application/pdf']);
    });

    // Send Slack Notification
    $this->sendSlackNotification(
        'Leave Application Update',
        $message,
        $leaveApplication->employee_name,
        [$employeeUser->email],
        false
    );
}
public function destroyleave($id)
{
    $leave = LeaveApplication::findOrFail($id);
    $leave->delete();

    return redirect()->back()->with('success', 'Leave application deleted successfully.');
}

public function showApprovalView($id)
{
    $leave = LeaveApplication::findOrFail($id); // Use singular name
    return view('HRM.approve_leave', compact('leave'));
}

public function departments()
{
    return view('HRM.createdepartment');
}

public function storedepartment(Request $request)
{
    $request->validate([
        'department_name' => 'required|string|max:255|unique:departments,name',
    ]);
    
    // Fetch a random employee_id from Employees table
    $randomEmployeeId = \App\Models\HRM::inRandomOrder()->first()->employee_id;

    // Create the department
    Department::create([
        'name' => $request->department_name, 
        'supervisor_id' => $randomEmployeeId,
    ]);

    // Redirect with success message
    return redirect()->route('department.create')->with('success', 'Department created successfully.');
}

public function deleteDepartment($id)
{
    // Find the department by id
    $department = Department::findOrFail($id);

    // Delete the department
    $department->delete();

    // Redirect back with success message
    return redirect()->route('departments.manage')->with('success', 'Department deleted successfully.');
}

public function ManageDepartment($id)
{
    $department = Department::findOrFail($id);

    // Get employees for this department
    $employees = HRM::where('department_id', $id)->get();

    return view('HRM.editdepartment', compact('department', 'employees'));
}

public function manageDepartments()
{
    // Get all departments
    $departments = Department::all();

    // Get all employees indexed by employee_id
    $employees = \App\Models\HRM::pluck('full_name', 'employee_id');

    // Attach supervisor name to each department
    foreach ($departments as $department) {
        $department->supervisor_name = $employees[$department->supervisor_id] ?? 'Not Assigned';
    }

    return view('HRM.manage-department', compact('departments'));
}

public function updateDepartment(Request $request, $id)
{
    // Validate the form data
    $request->validate([
        'department_name' => 'required|string|max:255',
        'supervisor_id' => 'nullable|exists:employees,employee_id',
    ]);

    // Find the department
    $department = Department::findOrFail($id);
    
    // Reset the position of the previous supervisor, if any
    if ($department->supervisor_id) {
        $oldSupervisor = HRM::where('employee_id', $department->supervisor_id)->first();
        if ($oldSupervisor) {
            $oldSupervisor->position = 'Employee'; // Reset to default position
            $oldSupervisor->save();
        }
    }

    // Update the department
    $department->name = $request->department_name;
    $department->supervisor_id = $request->supervisor_id;
    $department->save();

    // Update the supervisor's position and notify via email & Slack
    if ($request->supervisor_id) {
        $newSupervisor = HRM::where('employee_id', $request->supervisor_id)->first();
        if ($newSupervisor) {
            // Assign supervisor position
            $newSupervisor->position = 'Supervisor';
            $newSupervisor->save();

            // Send email notification
            $subject = 'New Supervisor Appointment';
            $message = "Congratulations, {$newSupervisor->full_name}! You have been assigned the new role as the new Supervisor for the {$request->department_name} department on Rideve Connect.";
            if (filter_var($newSupervisor->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($newSupervisor->email)->send(new \App\Mail\NewSupervisorNotificationMail($subject, $message));
            }

            // Send Slack notification
            $this->sendSlackNotification(
                'New Supervisor Appointment',
                $message,
                $newSupervisor->full_name,
                [$newSupervisor->email],
                false
            );
        }
    }

    return redirect()->route('departments.manage')->with('success', 'Department updated and supervisor notified.');
}




public function CreateRequisition()
{
    $user = Auth::user();
    $employee = HRM::where('user_id', $user->id)->first();
    $items = Product::all(); 
    return view('HRM.procurementcreate', compact('employee', 'items'));
}


public function storeRequisition(Request $request)
{
    $validated = $request->validate([
        'department' => 'required',
        'priority' => 'required',
        'needed_by' => 'required|date',
        'requested_by' => 'required',
        'purpose' => 'required',
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $requisition = Requisition::create([
        'department' => $validated['department'],
        'priority' => $validated['priority'],
        'needed_by' => $validated['needed_by'],
        'requested_by' => $validated['requested_by'],
        'purpose' => $validated['purpose'],
    ]);

    foreach ($validated['items'] as $item) {
        $requisition->items()->create($item);
    }

    $employee = HRM::where('full_name', $validated['requested_by'])->first();
    $department = Department::where('name', $validated['department'])->first();

    $supervisor = $department && $department->supervisor_id
        ? HRM::find($department->supervisor_id)
        : null;

    // 📩 Email + Slack to Supervisor
    if ($supervisor && $supervisor->email) {
        Mail::send([], [], function ($mail) use ($supervisor, $requisition, $employee, $department) {
            $mail->to($supervisor->email)
                ->subject("New Requisition Submitted by {$employee->full_name}")
                ->from('it@ridevemedia.com', 'Rideve Connect Procurement')
                ->html("
                    <p>Dear {$supervisor->full_name},</p>
                    <p>A new requisition has been submitted by <strong>{$employee->full_name}</strong> in the <strong>{$department->name}</strong> department.</p>
                    <p><strong>Purpose:</strong> {$requisition->purpose}</p>
                    <p><strong>Needed By:</strong> {$requisition->needed_by}</p>
                    <p><strong>Priority:</strong> {$requisition->priority}</p>
                    <p>Please log in to RideveConnect to review the requisition.</p>
                    <br>
                    <p>Regards,<br><strong>Rideve Connect</strong></p>
                ");
        });

        $this->sendSlackRequisitionNotification($supervisor, $requisition, $employee, $department);
    }

    // 📩 Email + Slack to Procurement Officer
    $procurementOfficer = HRM::where('position', 'Procurement Officer')->first();
    // if ($procurementOfficer && $procurementOfficer->email) {
    //     Mail::send([], [], function ($mail) use ($procurementOfficer, $requisition, $employee, $department) {
    //         $mail->to($procurementOfficer->email)
    //             ->subject("Requisition Submitted: {$employee->full_name}")
    //             ->from('ernest@ridevemedia.com', 'Rideve Connect')
    //             ->html("
    //                 <p>Dear {$procurementOfficer->full_name},</p>
    //                 <p>A new requisition has been submitted by <strong>{$employee->full_name}</strong> in the <strong>{$department->name}</strong> department.</p>
    //                 <p><strong>Purpose:</strong> {$requisition->purpose}</p>
    //                 <p><strong>Needed By:</strong> {$requisition->needed_by}</p>
    //                 <p><strong>Priority:</strong> {$requisition->priority}</p>
    //                 <p>This is for your action as Procurement Officer.</p>
    //                 <br>
    //                 <p>Regards,<br><strong>Rideve Connect</strong></p>
    //             ");
    //     });

    //     $this->sendSlackRequisitionNotification($procurementOfficer, $requisition, $employee, $department);
    // }

    return redirect()->route('requisition.create')->with('success', 'Requisition submitted and notifications sent.');
}


private function sendSlackRequisitionNotification($supervisor, $requisition, $employee, $department)
{
    $slackToken = env('SLACK_BOT_TOKEN');
    $email = $supervisor->email;

    $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
        'email' => $email
    ]);

    $userData = $userResponse->json();

    if (!$userData['ok']) {
        Log::error("Failed to fetch Slack User ID for $email: " . json_encode($userData));
        return;
    }

    $userId = $userData['user']['id'];

    $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
        'users' => $userId
    ]);

    $dmData = $dmResponse->json();

    if (!$dmData['ok']) {
        Log::error("Failed to open DM with $email ($userId): " . json_encode($dmData));
        return;
    }

    $channel = $dmData['channel']['id'];

    $message = "*New Requisition Submitted*\n"
        . "Requested By: {$employee->full_name}\n"
        . "Department: {$department->name}\n"
        . "Purpose: {$requisition->purpose}\n"
        . "Needed By: {$requisition->needed_by}\n"
        . "Priority: {$requisition->priority}";

    $response = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $channel,
        'text' => $message
    ]);

    if (!$response->json()['ok']) {
        Log::error("Failed to send Slack message to $email: " . $response->body());
    }
}


public function approvalIndex()
{
    $user = auth()->user();

    // Step 1: Get HRM employee record from user ID
    $employee = \App\Models\HRM::where('user_id', $user->id)->first();
    if (!$employee) {
        abort(403, 'Employee record not found.');
    }

    // Step 2: Get department using department_id
    $department = \App\Models\Department::find($employee->department_id);
    if (!$department) {
        abort(403, 'Department not found.');
    }

    // Step 3: Ensure this employee is the supervisor of the department
    if ($department->supervisor_id !== $employee->employee_id) {
        abort(403, 'Access denied: You are not the supervisor of this department.');
    }

    \Log::info('Supervisor ' . $employee->full_name . ' accessing requisitions for department: ' . $department->name);

    // Step 4: Fetch pending requisitions for the department
    $requisitions = \App\Models\Requisition::with('items')
        ->where('status', 'Pending')
        ->whereRaw('LOWER(department) = ?', [strtolower($department->name)])
        ->get();

    $statusColors = [
        'Pending' => 'bg-warning text-dark',
        'Approved' => 'bg-success text-white',
        'Rejected' => 'bg-danger text-white',
        'Fullfilled' => 'bg-primary text-white',
    ];

    $priorityColors = [
        'Low' => 'bg-success text-white',
        'Medium' => 'bg-warning text-dark',
        'High' => 'bg-danger text-white',
    ];

    return view('HRM.approverequisition', compact('requisitions', 'statusColors', 'priorityColors'));
}


public function reqapprove(Request $request)
{
    $request->validate([
        'requisition_id' => 'required|exists:requisitions,id',
        'signature' => 'required|string'
    ]);

    $requisition = Requisition::findOrFail($request->requisition_id);
    $requisition->status = 'Supervisor_approved';
    $requisition->supervisor_signature = $request->signature;
    $requisition->save();

    // 📩 Email + Slack to Procurement Officer
    $procurementOfficer = HRM::where('position', 'Procurement Officer')->first();

    // Now fetch HRM record by full_name
    $employee = HRM::where('full_name', $requisition->requested_by)->first();
    $department = $employee->department; 

    if ($procurementOfficer && $procurementOfficer->email && $employee) {
        Mail::send([], [], function ($mail) use ($procurementOfficer, $requisition, $employee, $department) {
            $mail->to($procurementOfficer->email)
                ->subject("Approved Supervisor Requisition: {$employee->full_name}")
                ->from('it@ridevemedia.com', 'Rideve Connect')
                ->html("
                    <p>Dear {$procurementOfficer->full_name},</p>
                    <p>A requisition has been approved by the supervisor for <strong>{$employee->full_name}</strong> in the <strong>{$department}</strong> department.</p>
                    <p><strong>Purpose:</strong> {$requisition->purpose}</p>
                    <p><strong>Needed By:</strong> {$requisition->needed_by}</p>
                    <p><strong>Priority:</strong> {$requisition->priority}</p>
                    <p>This is for your action as Procurement Officer.</p>
                    <br>
                    <p>Regards,<br><strong>Rideve Connect</strong></p>
                ");
        });

        $this->SlackRequisitionNotification($procurementOfficer, $requisition, $employee, $department);
    }

    return redirect()->back()->with('success', 'Requisition approved successfully.');
}



private function SlackRequisitionNotification($supervisor, $requisition, $employee, $department)
{
    $slackToken = env('SLACK_BOT_TOKEN');
    $email = $supervisor->email;

    $userResponse = Http::withToken($slackToken)->get("https://slack.com/api/users.lookupByEmail", [
        'email' => $email
    ]);

    $userData = $userResponse->json();

    if (!$userData['ok']) {
        Log::error("Failed to fetch Slack User ID for $email: " . json_encode($userData));
        return;
    }

    $userId = $userData['user']['id'];

    $dmResponse = Http::withToken($slackToken)->post("https://slack.com/api/conversations.open", [
        'users' => $userId
    ]);

    $dmData = $dmResponse->json();

    if (!$dmData['ok']) {
        Log::error("Failed to open DM with $email ($userId): " . json_encode($dmData));
        return;
    }

    $channel = $dmData['channel']['id'];

    $message = "*New Requisition Submitted*\n"
        . "Requested By: {$employee->full_name}\n"
        . "Department: {$department}\n"
        . "Purpose: {$requisition->purpose}\n"
        . "Needed By: {$requisition->needed_by}\n"
        . "Priority: {$requisition->priority}";

    $response = Http::withToken($slackToken)->post("https://slack.com/api/chat.postMessage", [
        'channel' => $channel,
        'text' => $message
    ]);

    if (!$response->json()['ok']) {
        Log::error("Failed to send Slack message to $email: " . $response->body());
    }
}


























public function rejectdepartmentrequisition($id)
{
    $requisition = Requisition::findOrFail($id);
    $requisition->status = 'Rejected';
    $requisition->save();

    return redirect()->back()->with('error', 'Requisition rejected.');
}



















}