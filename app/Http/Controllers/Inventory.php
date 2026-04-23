<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\StockRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use App\Models\HRM;
use App\Models\PurchaseOrder;
use App\Models\Requisition;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MaterialRequest;




class Inventory extends Controller
{
    public function index()
    {
       
    return view('Inventory.index');
      
    }

    
public function create()
{
    // Fetch all categories from the database
    $categories = Category::all();

    // Get all files from the 'photos/21/thumbs' directory on the public disk
    $files = Storage::disk('public')->files('photos/21/thumbs');

    // Return the view with categories and files
    return view('Inventory.create', compact('categories', 'files'));
}

public function store(Request $request)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
        'image' => 'nullable', // Change from 'required' to 'nullable'
        'price' => 'nullable|numeric|min:0',
        'description' => 'nullable|string',
    ]);

    // Only process images if provided
    $imagePaths = $request->has('image') ? explode(',', $request->image) : [];

    \Log::info('Stored image paths: ', $imagePaths);

    $product = Product::create([
        'name' => $request->product_name,
        'category_id' => $request->category_id,
        'price' => $request->price ?? 0,
        'description' => $request->description,
        'images' => !empty($imagePaths) ? json_encode($imagePaths) : null, // Store JSON or null
    ]);

    return redirect()->route('products.create')->with('success', 'Item added successfully!');
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'nullable|string|max:255', 
        'category_id' => 'nullable|exists:categories,id', 
        'price' => 'nullable|numeric|min:0', 
        'description' => 'nullable|string', 
        'type' => 'nullable|string', 
    ]);

    // Retain old values if new ones are not provided
    $product->name = $request->filled('name') ? $request->name : $product->name;
    $product->category_id = $request->filled('category_id') ? $request->category_id : $product->category_id;
    $product->price = $request->filled('price') ? $request->price : $product->price;
    $product->description = $request->filled('description') ? $request->description : $product->description;
    $product->type = $request->filled('type') ? $request->type : $product->type;

    // Save updates
    $product->save();

    return redirect()->route('inventory')->with('success', 'Item updated successfully!');
} 

public function stock()
{
    // Use the correct relationship names
    $stocks = Stock::with('product')->get(); 
    $products = Product::all(); 

    return view('Inventory.add_stock', compact('stocks', 'products'));
}

public function category()
{
    $categories = Category::with('Products')->get();

    return view('Inventory.create_category', compact('categories'));
}

public function storeCategory(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'type' => 'required|in:general,raw_material,finished_goods',
    ]);

    // Create a new category in the database
    Category::create([
        'name' => $validatedData['name'],
        'parent_id' => $validatedData['parent_id'],
        'type' => $validatedData['type'],
    ]);

    // Redirect with success message
    return redirect()->route('category')->with('success', 'Category created successfully!');
}

public function productindex()
{
    $products = Product::with('category')->get(); 
    return view('Inventory.products', compact('products'));
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('Inventory')->with('success', 'Product deleted successfully!');
}


public function storestock(Request $request)
{
    // Validate request input
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
        'cost'       => 'required|numeric|min:0', 
        'grn_number' => 'required|string|max:191', 
    ]);

    $location = $request->location ?? 'Rideve Media Warehouse';

    // Check if stock already exists for this product at this location
    $stock = Stock::where('product_id', $request->product_id)
                  ->where('location', $location)
                  ->first();

    if ($stock) {
        // Update existing stock quantity
        $stock->increment('quantity', $request->quantity);

        // Update with the latest purchase cost and GRN
        $stock->cost = $request->cost; 
        $stock->grn_number = $request->grn_number;
        $stock->save();
    } else {
        // Create new stock record
        $stock = Stock::create([
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity,
            'cost'       => $request->cost, // Added Cost
            'location'   => $location,
            'added_by'   => auth()->user()->name,
            'grn_number' => $request->grn_number,
        ]);
    }

    // Log the event in StockHistory
    StockHistory::create([
        'product_id'     => $request->product_id,
        'quantity_added' => $request->quantity,
        'cost'           => $request->cost, // Ensure your StockHistory model also has this field
        'location'       => $location,
        'added_by'       => auth()->user()->name,
        'type'           => 'Addition',
        'grn_number'     => $request->grn_number,
    ]);

    return redirect()->back()->with('success', 'Stock added successfully.');
}

public function inventory()
{
    $products = Product::with(['category', 'stock'])->get();
    $stocks = Stock::with('product')->get();

    return view('Inventory.InventoryTable', compact('stocks', 'products'));
}
    

public function edititem($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all(); 

    // Fetch ENUM values for the "type" column
    $typeEnumValues = DB::select("SHOW COLUMNS FROM products WHERE Field = 'type'");
    preg_match("/^enum\((.*)\)$/", $typeEnumValues[0]->Type, $matches);
    $types = str_getcsv($matches[1], ",", "'"); // Extract values

    return view('Inventory.EditItem', compact('product', 'categories', 'types'));
}
    

public function updateitem(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'type' => 'required|in:raw_material,finished_goods',
    ]);

    $product = Product::findOrFail($id);
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'type' => $request->type
    ]);

    return redirect()->route('Inventory')->with('success', 'Product updated successfully!');
}

public function stockHistory()
{
    $stockHistories = StockHistory::latest()->paginate(10);
    return view('Inventory.stock-history', compact('stockHistories'));
}


public function requests()
{
    $requests = StockRequest::where('status', 'Pending')->get();

    // Fetch all products at once and index by ID for quick lookup
    $productMap = Product::all()->pluck('name', 'id')->toArray();

    return view('Inventory.stockrequests', compact('requests', 'productMap'));
}

public function material()
{
    // Get all material requests that are not approved, eager-load items and their products
    $requests = MaterialRequest::with('items.product')
                ->where('status', '!=', 'approved')
                ->latest()
                ->get();

    return view('Inventory.material_requests_index', compact('requests'));
}

public function approveMaterialRequest(Request $request)
{
    $requestId = $request->input('request_id');
    $collectingStaff = $request->input('collecting_staff');
    $signature = $request->input('signature');

    $materialRequest = MaterialRequest::with('items.product')->findOrFail($requestId);

    DB::beginTransaction();

    try {
        foreach ($materialRequest->items as $item) {
            $product = $item->product;
            $quantityToDeduct = $item->quantity;

            // Find the stock record for this product
            $stockRecord = Stock::where('product_id', $product->id)->first();

            if (!$stockRecord) {
                throw new \Exception("No stock record found for product {$product->name}");
            }

            if ($stockRecord->quantity < $quantityToDeduct) {
                throw new \Exception("Not enough stock for product {$product->name}");
            }

            // Deduct from stock
            $stockRecord->quantity -= $quantityToDeduct;
            $stockRecord->save();

            // Record deduction in history
            StockHistory::create([
                'product_id' => $product->id,
                'stock_deducted' => $quantityToDeduct,
                'location' => 'Rideve Media Warehouse',
                'added_by' => auth()->user()->name,
                'type' => 'Deduction',
            ]);
        }

        // Approve the material request
        $materialRequest->status = 'approved';
        $materialRequest->approved_by = auth()->user()->name ?? 'System';
        $materialRequest->approved_at = now();
        $materialRequest->collecting_staff = $collectingStaff;
        $materialRequest->signature = $signature;
        $materialRequest->save();

        DB::commit();

        return redirect()->back()->with('success', 'Material request approved and stock updated.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage());
    }
}



















public function approve($id)
{
    $request = StockRequest::findOrFail($id);

    $productIds = json_decode($request->products, true);
    $quantities = json_decode($request->quantities, true);

    \Log::info('Approving stock request', [
        'request_id' => $id,
        'product_ids' => $productIds,
        'quantities' => $quantities
    ]);

    DB::beginTransaction();

    try {
        foreach ($productIds as $index => $productId) {
            $quantityToDeduct = isset($quantities[$index]) ? (int)$quantities[$index] : 0;

            // Find the stock record for this product
            $stockRecord = Stock::where('product_id', $productId)->first();

            if (!$stockRecord) {
                throw new \Exception("No stock record found for product ID $productId");
            }

            if ($stockRecord->quantity < $quantityToDeduct) {
                throw new \Exception("Not enough stock for product ID $productId");
            }

            // Deduct from stock
            $stockRecord->quantity -= $quantityToDeduct;
            $stockRecord->save();

            // Record deduction in history
            StockHistory::create([
                'product_id' => $productId,
                'stock_deducted' => $quantityToDeduct,
                'location' => 'Rideve Media Warehouse',
                'added_by' => auth()->user()->name,
                'type' => 'Deduction',
            ]);
        }

        $request->status = 'Approved';
        $request->approved_by = auth()->user()->name ?? 'System';
        $request->approved_at = now();
        $request->save();

        DB::commit();

        return redirect()->back()->with('success', 'Stock request approved and stock updated.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage());
    }
}


public function updateStatus(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'requisition_id' => 'required|exists:requisitions,id',
        'status' => 'required|in:Pending,Approved,Rejected,Fulfilled',
    ]);

    // Find the requisition
    $requisition = Requisition::findOrFail($request->requisition_id);
    $requisition->status = $request->status;
    $requisition->save();

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Requisition status updated successfully.',
        'status' => $requisition->status,  // Return updated status
    ]);
}

public function Requisitionindex()
{
    // Fetch all requisitions except those with 'Rejected' or 'Pending' status
    $requisitions = Requisition::with('items')
        ->whereNotIn('status', ['Rejected', 'Pending', 'Fulfilled'])
        ->get();

    // Fetch all vendors
    $vendors = Vendor::all(); // Add this line

    // Define color classes for status and priority
    $statusColors = [
        'Supervisor Approved' => 'bg-warning text-white',
        'Approved' => 'bg-success text-white',
        'Fullfilled' => 'bg-primary text-white',
    ];

    $priorityColors = [
        'Low' => 'bg-success text-white',
        'Medium' => 'bg-warning text-dark',
        'High' => 'bg-danger text-white',
    ];

    // Pass vendors to the view
    return view('Procurement.managerequisition', compact('requisitions', 'vendors', 'statusColors', 'priorityColors'));
}


public function rejectreq(Request $request)
{
    $request->validate([
        'requisition_id' => 'required|exists:requisitions,id',
    ]);

    $requisition = Requisition::findOrFail($request->requisition_id);
    $requisition->status = 'Rejected';
    $requisition->save();

    // Retrieve employee record from the employees table using the name
    $employee = HRM::where('full_name', $requisition->name)->first();

    if ($employee && $employee->email) {
        Mail::send([], [], function ($mail) use ($employee, $requisition) {
            $mail->to($employee->email)
                ->subject("Requisition Rejected: {$requisition->purpose}")
                ->from('admin@ridevemedia.com', 'Rideve Connect')
                ->html("
                    <p>Dear {$employee->full_name},</p>
                    <p>Your requisition request has been <strong>rejected</strong>.</p>
                    <p><strong>Purpose:</strong> {$requisition->purpose}</p>
                    <p><strong>Needed By:</strong> {$requisition->needed_by}</p>
                    <p><strong>Priority:</strong> {$requisition->priority}</p>
                    <p>If you have any questions, please contact the Procurement Office.</p>
                    <br>
                    <p>Regards,<br><strong>Rideve Connect</strong></p>
                ");
        });
    }

    return redirect()->back()->with('success', 'Requisition has been rejected and the employee has been notified via email.');
}


public function vendorindex()
{
    $vendors = Vendor::all();
    return view('Procurement.index', compact('vendors'));
}

public function ajaxCreateModal()
{
    return response()->json([
        'html' => '
        <div class="modal fade" id="ajaxAddSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
    
                    <!-- Top Border -->
                    <div class="card-header p-0" style="margin-top:10px; border:none;">
                        <img src="../assets/BG-02.jpeg" style="max-width: 100%; height: 15px;" alt="Pattern" class="img-fluid">
                    </div>
    
                    <div class="modal-body p-4">
                        <h4 class="text-center mb-4" style="color:#2ba6db;">Register New Supplier</h4>
                        <form action="'.route('vendors.store').'" method="POST" enctype="multipart/form-data" class="row g-3">
                            '.csrf_field().'
    
                            <div class="col-md-12">
                                <label class="form-label">Supplier / Company Name</label>
                                <input type="text" class="form-control" name="company_name" required>
                            </div>
    
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
    
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone">
                            </div>
    
                            <div class="col-md-12">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="" disabled selected>Select category</option>
                                    <option value="Raw Materials">Raw Materials</option>
                                    <option value="Office Supplies">Office Supplies</option>
                                    <option value="Machinery">Machinery</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
    
            
    
                            <div class="col-md-12">
                                <label class="form-label">PACRA Certificate</label>
                                <input type="file" class="form-control" name="PACRA_Certificate" accept=".pdf,.jpg,.png">
                            </div>
    
                            <div class="col-md-12">
                                <label class="form-label">ZRA Tax Clearance</label>
                                <input type="file" class="form-control" name="ZRA_Taxclearance" accept=".pdf,.jpg,.png">
                            </div>
    
                             <div class="col-md-12">
                                <label class="form-label">Company Profile</label>
                                <input type="file" class="form-control" name="company_profile" accept=".pdf,.jpg,.png">
                            </div>
    
                               <div class="col-md-12">
                                <label class="form-label">NAPSA Compliance Certificate</label>
                                <input type="file" class="form-control" name="NAPSA_Complaince_certificate" accept=".pdf,.jpg,.png">
                            </div>
    
                               <div class="col-md-12">
                                <label class="form-label">Bank Reference Letter</label>
                                <input type="file" class="form-control" name="Bank_reference_letter" accept=".pdf,.jpg,.png">
                            </div>
    
                            <div class="col-12 d-flex justify-content-center mt-4">
                                <button type="submit" class="btn btn-primary px-4" style="background-color:#1daeec; font-size:18px;">
                                    Add Supplier
                                </button>
                            </div>
                        </form>
                    </div>
    
                    <!-- Bottom Border -->
                    <div class="card-header p-0" style="border:none;">
                        <img src="../assets/BG-02.jpeg" style="max-width: 100%; height: 15px;" alt="Pattern" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>'
    ]);
    
}

public function storesupplier(Request $request)
{
    $data = $request->validate([
        'email' => 'required|email',
        'phone' => 'nullable',
        'company_name' => 'required',
        'category' => 'required',

        // File validations
        'PACRA_Certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'ZRA_Taxclearance' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'company_profile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'NAPSA_Complaince_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'Bank_reference_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Save files if uploaded
    foreach (['PACRA_Certificate', 'ZRA_Taxclearance', 'company_profile', 'NAPSA_Complaince_certificate', 'Bank_reference_letter'] as $field) {
        if ($request->hasFile($field)) {
            $data[$field] = $request->file($field)->store('documents/vendors', 'public');
        }
    }

    Vendor::create($data);

    return redirect()->route('vendors.index')->with('success', 'Supplier registered successfully!');
}

public function vendorshow($id)
{
    $vendor = Vendor::findOrFail($id);

    $documents = [
        'PACRA Certificate' => $vendor->PACRA_Certificate,
        'ZRA Tax Clearance' => $vendor->ZRA_Taxclearance,
        'Company Profile' => $vendor->company_profile,
        'NAPSA Compliance Certificate' => $vendor->NAPSA_Complaince_certificate,
        'Bank Reference Letter' => $vendor->Bank_reference_letter,
    ];

    return view('Procurement.documents', compact('vendor', 'documents'));
}

public function vendoredit($id)
{
    $vendor = Vendor::findOrFail($id);
    return view('Procurement.edit', compact('vendor'));
}

public function vendorupdate(Request $request, $id)
{
    $vendor = Vendor::findOrFail($id);
    $data = $request->validate([
        'company_name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:255',
        'address' => 'required|string|max:255',
    ]);

    $vendor->update($data);

    return redirect()->route('vendors.index')->with('success', 'Supplier updated successfully!');
}

public function updateSupplierStatus(Request $request, Vendor $vendor)
{
    $request->validate([
        'status' => 'required|in:Pending,Verified',
    ]);

    $vendor->status = $request->status;
    $vendor->save();

    return response()->json([
        'message' => 'Status updated successfully',
        'status' => $vendor->status
    ]);
}

public function storePO(Request $request, Requisition $requisition)
{
    $validated = $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'quotation' => 'required|array|min:2',
        'quotation.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10048',
        'signature' => 'required|string',
        'officer_name' => 'required|string',
    ]);

    // Save quotation files
    $quotationPaths = [];
    foreach ($request->file('quotation') as $file) {
        $quotationPaths[] = $file->store('quotations', 'public');
    }

    // Create Purchase Order
    $purchaseOrder = PurchaseOrder::create([
        'requisition_id' => $requisition->id,
        'vendor_id' => $validated['vendor_id'],
        'officer_name' => $validated['officer_name'],
        'procurement_signature' => $validated['signature'],
        'quotation_1_path' => $quotationPaths[0],
        'quotation_2_path' => $quotationPaths[1],
    ]);

    // ✅ Update Requisition Status
    $requisition->status = 'Approved';
    $requisition->save();

    // Get HRM & Department
    $employee = HRM::where('full_name', $requisition->requested_by)->first();

    // Get Finance Manager
    $financeManager = HRM::where('position', 'Finance Manager')->first();

    if ($financeManager && $financeManager->email) {
        // Generate PDF
        $pdf = Pdf::loadView('Procurement.po', [
            'purchaseOrder' => $purchaseOrder,
            'requisition' => $requisition,
            'employee' => $employee
        ]);

        $pdfPath = 'pdfs/PO-' . $purchaseOrder->id . '.pdf';
        Storage::put("public/{$pdfPath}", $pdf->output());

        // Send email with PDF & quotations
        Mail::send([], [], function ($mail) use ($financeManager, $requisition, $employee, $pdfPath, $quotationPaths) {
            $mail->to($financeManager->email)
                ->subject("Purchase Order - {$requisition->purpose}")
                ->from('admin@ridevemedia.com', 'Rideve Connect Procurement')
                ->html("
                    <p>Dear {$financeManager->full_name},</p>
                    <p>A new purchase order has been created for the requisition submitted by <strong>{$employee->full_name}.</strong></p>
                    <p><strong>Purpose:</strong> {$requisition->purpose}</p>
                    <p><strong>Priority:</strong> {$requisition->priority}</p>
                    <p>Please find the attached PDF and quotations for your review.</p>
                    <br>
                    <p>Regards,<br><strong>Rideve Connect</strong></p>
                ")
                ->attach(storage_path("app/public/{$pdfPath}"), [
                    'as' => 'Purchase_Order.pdf',
                    'mime' => 'application/pdf',
                ])
                ->attach(storage_path("app/public/{$quotationPaths[0]}"))
                ->attach(storage_path("app/public/{$quotationPaths[1]}"));
        });
    }

    return redirect()->back()->with('success', 'Purchase order submitted and emailed successfully.');
}

public function confirmReceipt(Request $request, $id)
{
    $request->validate([
        'recipient_signature' => 'required|string', // You can enhance this with regex if needed
    ]);

    $requisition = Requisition::findOrFail($id);

    $requisition->status = 'Fulfilled';
    $requisition->recipient_signature = $request->recipient_signature;
    $requisition->fulfilled_at = now();
    $requisition->save();

    return back()->with('success', 'Goods receipt confirmed successfully.');
}


}


