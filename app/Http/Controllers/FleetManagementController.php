<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\ServiceSchedule;
use App\Models\RepairLog;
use App\Models\FuelLog;
use App\Models\AccidentHistory;
use App\Models\VehicleCompliance;
use Illuminate\Http\Request;

class FleetManagementController extends Controller
{
public function index()
    {
        // 1. Fetch the main data collections
        $vehicles = Vehicle::orderBy('created_at', 'desc')->get();
        $repairLogs = RepairLog::with('vehicle')->orderBy('report_date', 'desc')->get();
        $services = ServiceSchedule::orderBy('next_service_date', 'asc')->get();
        $drivers = \App\Models\User::all(); 
        $fuelLogs = FuelLog::with('vehicle')
                ->orderBy('date', 'desc')
                ->get();

                $accidents = AccidentHistory::orderBy('incident_date', 'desc')->get();

        // --- THE FIX: Load compliance data for the table ---
        $compliances = $this->getVehicleCompliance(); 

        $monthlyRepairTotal = RepairLog::whereMonth('report_date', now()->month)
                                       ->whereYear('report_date', now()->year)
                                       ->sum('cost');

        // 3. Return everything to the view (added 'compliances')
        return view('fleet.index', compact(
            'vehicles', 
            'services', 
            'repairLogs', 
            'fuelLogs',
            'drivers', 
            'monthlyRepairTotal',
            'accidents',
            'compliances' 
        ));
    }

    public function store(Request $request)
{
    // 1. Validate the "Asset" fields (The Package Labels)
    $validated = $request->validate([
        'registration_number' => 'required|string|max:255|unique:vehicles,registration_number',
        'make'                => 'required|string|max:255',
        'model'               => 'required|string|max:255',
        'engine_type'         => 'nullable|string|max:255', 
        'current_mileage'     => 'required|integer|min:0',
        'transmission'        => 'required|in:Manual,Automatic',
        'status'              => 'required|in:Active,Repair,Disposed',
        'assigned_driver'     => 'required|string',
        'purchase_date'       => 'required|date'
    ]);

    // 2. Create the record in the 'vehicles' table
    Vehicle::create($validated);

    // 3. Redirect back to the index with a clean success message
    return redirect()->back()->with('success', 'New vehicle added to inventory successfully.');
}
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            // Use the ignore rule so it doesn't fail when saving the SAME plate
            'registration_number' => 'nullable|string|max:255|unique:vehicles,registration_number,'.$id,
            'engine_type'         => 'nullable|string',
            'assigned_driver'     => 'nullable|string',
            'transmission'        => 'nullable|in:Manual,Automatic',
            'current_mileage'     => 'nullable|numeric|min:0',
            'status'              => 'nullable|string',
            'make'                => 'nullable|string',
            'model'               => 'nullable|string',
        ]);
        $compliances = $this->getVehicleCompliance(); 

    $vehicle->update($validated);

        return redirect()->back()->with('success', 'Vehicle inventory updated!');
    }

 public function StoreServiceSchedule(Request $request)
{
    // 1. Validation (Remove 'status' from required if you don't want it from the form)
    $validated = $request->validate([
        'vehicle_id'           => 'required|exists:vehicles,id',
        'service_type'         => 'required|string|max:255',
        'last_service_date'    => 'required|date',
        'last_service_mileage' => 'required|numeric|min:0',
        'next_service_date'    => 'required|date|after:last_service_date',
        'next_service_mileage' => 'required|numeric|gt:last_service_mileage',
        'service_provider'     => 'required|string|max:255',
        'estimated_cost'       => 'required|numeric|min:0',
        'service_status'       => 'required|in:up-to-date,pending,overdue',
        'remarks'              => 'required|string|max:255',
    ]);

    // 2. Fetch the actual Vehicle to get its current status
    $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);

    // 3. Update or Create the schedule
    $schedule = \App\Models\ServiceSchedule::firstOrNew(['vehicle_id' => $vehicle->id]);
    
    $schedule->fill($validated);
    
    // Set the status from the Vehicle model, not the form input
    $schedule->vehicle_status = $vehicle->status; 
    
    $schedule->save();

    return redirect()->back()->with('success', 'Service record for ' . $vehicle->registration_number . ' Updated.');
}


    public function destroy($id)
    {
        Vehicle::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Vehicle deleted successfully.');
    }
   public function storeRepair(Request $request)
{
    // 1. Validate exactly what is in your DB now
    $validated = $request->validate([
        'vehicle_id'         => 'required|exists:vehicles,id',
        'report_date'        => 'required|date',
        'reported_by'        => 'required|string|max:255',
        'repair_description' => 'required|string',
        'service_provider'   => 'required|string',
        'cost'               => 'required|numeric|min:0',
        'downtime_status' => 'required|in:Not Started,Ongoing,Repaired',  
        'report'             => 'nullable|string', // Your new manual column
        'remarks'            => 'nullable|string', 
    ]);

    // 2. Create the record using ONLY validated data
    RepairLog::create($validated);

  return redirect()->route('fleet.index')->with([
        'success' => 'Breakdown successfully logged.',
        'active_tab' => 'repairs-history' // Changed from 'breakdowns' to match your HTML ID
    ]);
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'downtime_status' => 'required|in:Not Started,Ongoing,Repaired'
    ]);

    $log = RepairLog::findOrFail($id);
    $log->update([
        'downtime_status' => $request->downtime_status
    ]);

    return response()->json(['success' => true, 'message' => 'Status updated!']);
}

public function getVehicleCompliance()
    {
        return \App\Models\VehicleCompliance::with('vehicle')->get();
    }

public function storeCompliance(Request $request)
{
    // 1. Validate based on your specific requirements
    $validated = $request->validate([
        'vehicle_id'                 => 'required|exists:vehicles,id',
        'insurance_provider'         => 'nullable|string',
        'insurance_policy_number'    => 'nullable|string',
        'insurance_expiry_date'      => 'required|date',
        'road_tax_expiry'            => 'required|date',
        'fitness_certificate_expiry' => 'required|date',
        'compliance_status'          => 'required|in:Valid,Expired,Pending',
        'reminder_sent'              => 'required|in:YES,NO',
    ]);

    // 2. Create the record
    \App\Models\VehicleCompliance::create($validated);

    // 3. Redirect back to the dashboard and open the compliance tab
    return redirect()->route('fleet.index')->with([
        'success' => 'Compliance data successfully recorded.',
        'active_tab' => 'compliance_tab'
    ]);
}


public function updateCompliance(Request $request, $id)
{
    // 1. Validate the incoming dates
    $validated = $request->validate([
        'insurance_provider'         => 'nullable|string|max:255',
        'insurance_policy_number'    => 'nullable|string|max:255',
        'insurance_expiry_date'      => 'required|date',
        'road_tax_expiry'            => 'required|date',
        'fitness_certificate_expiry' => 'required|date',
    ]);

    // 2. Find the vehicle
    $vehicle = Vehicle::findOrFail($id);

    // 3. Update or Create the compliance record
    $vehicle->compliance()->updateOrCreate(
        ['vehicle_id' => $id], // Search criteria
        [
            'insurance_provider'         => $validated['insurance_provider'],
            'insurance_policy_number'    => $validated['insurance_policy_number'],
            'insurance_expiry_date'      => $validated['insurance_expiry_date'],
            'road_tax_expiry'            => $validated['road_tax_expiry'],
            'fitness_certificate_expiry' => $validated['fitness_certificate_expiry'],
            // Reminders are reset when dates are updated
            'reminder_sent'              => false, 
        ]
    );

    // 4. Redirect back with a success message
    return redirect()->back()->with('success', 'Compliance records for ' . $vehicle->registration_number . ' updated successfully.');
}


public function storefuel(Request $request)
{
    $validated = $request->validate([
        'vehicle_id' => 'required',
        'date' => 'required|date',
        'litres' => 'required|numeric',
        'cost' => 'required|numeric',
        'odometer_reading' => 'required|numeric',
        'fuel_station' => 'nullable|string',
        'driver' => 'required|string',
    ]);

    // 1. Calculate KM per Litre (Efficiency)
    $lastLog = FuelLog::where('vehicle_id', $request->vehicle_id)
        ->orderBy('odometer_reading', 'desc')
        ->first();

    $kmPerLitre = 0;
    if ($lastLog && $request->odometer_reading > $lastLog->odometer_reading) {
        $distance = $request->odometer_reading - $lastLog->odometer_reading;
        $kmPerLitre = $distance / $request->litres;
    }

    // 2. Save Log
    FuelLog::create(array_merge($validated, [
        'km_per_litre' => $kmPerLitre,
        'fuel_type' => $request->fuel_type ?? 'Petrol'
    ]));

    return redirect()->back()->with('success', 'Fuel log added and efficiency calculated!');
}

public function updatefuel(Request $request, $id)
{
    $log = FuelLog::findOrFail($id);

    $validated = $request->validate([
        'date' => 'required|date',
        'litres' => 'required|numeric',
        'cost' => 'required|numeric',
        'odometer_reading' => 'required|numeric',
        'fuel_station' => 'nullable|string',
    ]);

    // Recalculate KM per Litre based on updated values
    $prevLog = FuelLog::where('vehicle_id', $log->vehicle_id)
        ->where('odometer_reading', '<', $request->odometer_reading)
        ->orderBy('odometer_reading', 'desc')
        ->first();

    $kmPerLitre = 0;
    if ($prevLog) {
        $distance = $request->odometer_reading - $prevLog->odometer_reading;
        $kmPerLitre = $distance / $request->litres;
    }

    $log->update(array_merge($validated, [
        'km_per_litre' => $kmPerLitre
    ]));

    return redirect()->back()->with('success', 'Fuel record updated and efficiency recalculated.');
}

public function storeaccident(Request $request)
{
    $validated = $request->validate([
        'vehicle_id' => 'required|string',
        'driver_name' => 'required|string',
        'incident_date' => 'required|date',
        'location' => 'required|string',
        'severity' => 'required|string',
        'description' => 'required|string',
        'police_report_number' => 'nullable|string',
        'estimated_repair_cost' => 'nullable|numeric',
    ]);

    AccidentHistory::create($validated);

    return redirect()->back()->with('success', 'Accident record logged successfully.');
}

public function updateaccident(Request $request, $id)
{
    $accident = AccidentHistory::findOrFail($id);

    $validated = $request->validate([
        'incident_date' => 'required|date',
        'location' => 'required|string',
        'severity' => 'required|string',
        'description' => 'required|string',
        'police_report_number' => 'nullable|string',
        'insurance_status' => 'required|string',
        'estimated_repair_cost' => 'nullable|numeric',
    ]);

    $accident->update($validated);

    return redirect()->back()->with('success', 'Incident record updated successfully.');
}

}