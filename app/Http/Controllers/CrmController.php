<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class CrmController extends Controller
{
    /**
     * Display the CRM Dashboard.
     */
public function index()
{
    // Fetch all leads along with their creators
    $leads = Lead::with('creator')->get();

    // Group by the creator's name instead of their ID
    $groupedLeads = $leads->groupBy(function ($lead) {
        return optional($lead->creator)->name ?? 'Unknown';
    });

    // Fetch clients
    $clients = Client::orderBy('created_at', 'desc')->get();

    return view('CRM.index', compact('groupedLeads', 'clients'));
}


    /**
     * Show a single lead.
     */
    public function show($id)
    {
        $lead = Lead::with('creator')->findOrFail($id);
        return view('CRM.show', compact('lead'));
    }

    /**
     * Store a new lead.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $lead = new Lead();
        $lead->name = $request->name;
        $lead->company = $request->company;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->notes = $request->notes;
        $lead->status = $request->status;
        $lead->created_by = Auth::id(); // assuming user is logged in
        $lead->save();

        return redirect()->route('crm.index')->with('success', 'New lead added successfully!');
    }

    public function updateStatus(Request $request, $id)
{
    $lead = \App\Models\Lead::findOrFail($id);
    $lead->status = $request->status;
    $lead->save();

    return response()->json(['success' => true, 'status' => $lead->status]);
}


public function clientstore(Request $request)
    {
        $request->validate([
            'client_name'   => 'required|string|max:255',
            'contact_name'  => 'required|string|max:255',
            'interests'     => 'nullable|string',
            'phone_number'  => 'nullable|string|max:50',
            'email_address' => 'nullable|email|max:255',
        ]);

        Client::create($request->all());

        return redirect()->back()->with('success', 'Client added successfully!');
    }

}
