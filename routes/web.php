<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\RideveConnectController; 
use App\Http\Controllers\LeadController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\HRMController;
use App\Http\Controllers\MarkertingController;
use App\Http\Controllers\Inventory;
use App\Http\Controllers\Production;
use Barryvdh\DomPDF\Facade as PDF;
use UniSharp\LaravelFileManager\Lfm;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\MothersDayController;
use App\Http\Controllers\FleetManagementController;



Route::middleware(['web'])->group(function () {
    Auth::routes();
});

// Public website routes

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
// Route::get('/', function () { return view('auth.auth-signin');})->name('home');

Route::get('/services', [WebsiteController::class, 'services'])->name('services');
Route::get('/events', [WebsiteController::class, 'events'])->name('events');
Route::get('/blog', [WebsiteController::class, 'blog'])->name('blog');
Route::get('/blog_details', [WebsiteController::class, 'blog_details'])->name('blog_details');

// Route::get('/test-pdf', function () {
//     $pdf = PDF::loadHTML('<h1>Hello, PDF!</h1>');
//     return $pdf->download('test.pdf');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/Portal', [RideveConnectController::class, 'dashboard'])->name('dashboard');

    Route::get('/Administration', [HRMController::class, 'index'])->name('HRM');
    Route::get('/Administration/add-employee', [HRMController::class, 'AddEmployee'])->name('AddEmployee');
    Route::get('/Administration/apply-for-leave/{employee_id}', [HRMController::class, 'Applyforleave'])->name('applyforleave');
    Route::post('/Administration/submitLeaveApplication', [HRMController::class, 'submitLeaveApplication'])->name('submitLeaveApplication');

    // Leave Applications Routes
    Route::get('/leave-applications/{employee_id}', [HRMController::class, 'leave_index'])->name('leave.index');
    Route::get('/leave-applications/supervisor', [HRMController::class, 'supervisorView'])->name('leave-applications.supervisor');
    Route::get('/leave-applications/hr', [HRMController::class, 'hrView'])->name('leave-applications.hr');
    Route::get('/leave/details/{id}', [HRMController::class, 'view_leave'])->name('leave.details');
Route::delete('leave-applications/{id}', [HRMController::class, 'destroyleave'])->name('leave.destroy');

    Route::get('/Administration/Add-Asset', [HRMController::class, 'AddAsset'])->name('AddAsset');
    Route::get('/Administration/manage-employees', [HRMController::class, 'ManageEmployees'])->name('ManageEmployees');
    Route::post('/HRM/store', [HRMController::class, 'store'])->name('storeEmployee');
    Route::post('/HRM/storeAsset', [HRMController::class, 'storeAsset'])->name('storeAsset');
    Route::get('/Administration/Manage-Assets', [HRMController::class, 'ManageAssets'])->name('ManageAssets');

    // Asset Management Routes
    Route::get('/export-asset-pdf', [HRMController::class, 'exportAssetPDF'])->name('export.asset.pdf');
    Route::get('/assets/{id}/edit', [HRMController::class, 'edit'])->name('assets.edit');
    Route::delete('/assets/{id}', [HRMController::class, 'destroy'])->name('assets.destroy');
    Route::put('/update-asset/{id}', [HRMController::class, 'update'])->name('updateAsset');
    
    // Employee Management
    Route::delete('employee/{employee_id}', [HRMController::class, 'destroyemployee'])->name('employee.destroy');
    Route::get('/HRM/edit/{employee_id}', [HRMController::class, 'editEmployee'])->name('editEmployee');
    Route::post('update-employee/{employee_id}', [HRMController::class, 'updateEmployee'])->name('employee.update');
    Route::get('/HRM/view/{employee_id}', [HRMController::class, 'viewEmployee'])->name('viewEmployee');
    Route::get('/assets/view/{id}', [HRMController::class, 'show'])->name('assets.show');
    Route::get('/mothers-day', [HRMController::class, 'motherday'])->name('mothers-day.index');
    Route::post('/mothers-day-save', [HRMController::class, 'storemothersday'])->name('mothers-day.store');
    Route::post('/mothers-day/store', [HRMController::class, 'storemothersday'])->name('mothers-day.store');

    // Memo Routes
    Route::get('/administration/send-memo', [HRMController::class, 'sendMemo'])->name('memos.save');
    Route::get('/Administration/create', [HRMController::class, 'creatememo'])->name('create.memos');
    Route::post('/Administration/send-memo', [HRMController::class, 'sendMemo'])->name('send.memo');
    Route::get('/Administration/memos', [HRMController::class, 'memo'])->name('memos.index');
    Route::get('/Administration/memos/{id}', [HRMController::class, ' showmemo'])->name('memos.show');
   
    Route::prefix('attendance')->group(function () {
    // The main view we just created
    Route::get('/', [HRMController::class, 'attendance'])->name('attendance.index');

    // The manual sync route (triggered by the button in the UI)
    Route::get('/sync', [HRMController::class, 'syncDevice'])->name('attendance.sync');
});



    Route::delete('/Administration/memos/{id}', [HRMController::class, 'destroy'])->name('memos.destroy');

    Route::get('/Administration/create_department', [HRMController::class, 'departments'])->name('department.create')->middleware('web');
    Route::post('/Administration/store_department', [HRMController::class, 'storedepartment'])->name('departments.store')->middleware('web');
    Route::get('/department/manage/{id}', [HRMController::class, 'ManageDepartment'])->name('department.manage');
    Route::delete('/department/{id}/delete', [HRMController::class, 'deleteDepartment'])->name('department.delete');
    Route::get('/Administration/manage-department', [HRMController::class, 'ManageDepartments'])->name('departments.manage');
    Route::post('/Administration/update-department/{id}', [HRMController::class, 'updateDepartment'])->name('department.update');
    Route::get('/Administration/create', [HRMController::class, 'CreateRequisition'])->name('requisition.create');
    Route::post('Administration/requisition/store', [HRMController::class, 'storeRequisition'])->name('requisition.store');
    Route::get('Employee/documentation', [RideveConnectController::class, 'documentation'])->name('documentation.view');





    // Attendance Routes
    Route::get('/Administration/Attendance', [HRMController::class, 'attendance'])->name('attendance.view');

    // Route::get('/Administration/manageleave/{employee_id}', [HRMController::class, 'index'])->name('leave.index');


    Route::get('/leave/{id}/approval', [HRMController::class, 'showApprovalView'])->name('leave.approval.view');

    Route::post('/leave/{id}/approve', [HRMController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{id}/reject', [HRMController::class, 'rejectLeave'])->name('leave.reject');


    Route::get('/profile/settings', [UserController::class, 'edit'])->name('profile.settings');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');


    //Inventory Routes
 Route::get('/Inventory', [Inventory::class, 'index'])->name('Inventory');
Route::get('/inventory/products', [Inventory::class, 'productindex'])->name('allproducts');
Route::get('/inventory/table', [Inventory::class, 'inventory'])->name('inventorytable');
 Route::get('/Inventory/categories', [Inventory::class, 'category'])->name('category');
Route::post('/categories/store', [Inventory::class, 'storeCategory'])->name('categories.store');


// Product Routes
Route::resource('products', Inventory::class);
Route::get('/Inventory/create', [Inventory::class, 'create'])->name('products.create');
// Stock Routes
Route::get('/Inventory/stock', [Inventory::class, 'stock'])->name('stock');

// Inventory Overview
Route::get('inventory', [Inventory::class, 'index'])->name('inventory.index');
Route::get('/inventory/stocksequest', [Inventory::class, 'requests'])->name('stockrequest');
Route::get('/stock-requests/history', [Inventory::class, 'stockhistory'])->name('stock-requests.history');
Route::post('/requests/{id}/approve', [Inventory::class, 'approve'])->name('requests.approve');
Route::post('/stock-requests/add', [Inventory::class, 'storestock'])->name('storestock');

Route::delete('/inventory/delete/{id}', [Inventory::class, 'destroy'])->name('inventory.delete');

Route::get('/requisitions', [Inventory::class, 'Requisitionindex'])->name('requisitions.index');
Route::get('/requisitions/history', [Inventory::class, 'Requisitionhistory'])->name('requisitions.history');
    
// Route to handle the status update via AJAX
Route::post('requisition/update-status', [Inventory::class, 'updateStatus'])->name('requisition.updateStatus');

// Route to delete a requisition
Route::post('/requisition/reject', [Inventory::class, 'rejectreq'])->name('requisition.reject');
Route::get('/requisitions/approval', [HRMController::class, 'approvalIndex'])->name('requisitions.approval');
Route::post('requisition/update-status', [Inventory::class, 'updateStatus'])->name('requisition.updateStatus');
Route::post('/requisition/approve', [HRMController::class, 'reqapprove'])->name('requisition.approve');

Route::get('/requisitions/{id}/edit', [HRMController::class, 'editrequisition'])->name('purchase_orders.submit');
Route::post('/purchase_orders/{requisition}/submit', [Inventory::class, 'storePO'])->name('purchase_orders.submit');
Route::post('/requisition/{id}/confirm-receipt', [Inventory::class, 'confirmReceipt'])->name('requisition.confirmReceipt');



Route::prefix('vendors')->group(function () {
    Route::get('/', [Inventory::class, 'vendorindex'])->name('vendors.index');
    Route::get('/create', [Inventory::class, 'vendorcreate'])->name('vendors.create');
    Route::get('/vendors/{id}/documents', [Inventory::class, 'vendorshow'])->name('vendors.show');
    Route::get('/{id}/edit', [Inventory::class, 'vendoredit'])->name('vendors.edit');
    Route::post('/{id}/update', [Inventory::class, 'vendorupdate'])->name('vendors.update');
    Route::post('/', [Inventory::class, 'storesupplier'])->name('vendors.store');
    Route::post('/{id}/approve', [Inventory::class, 'vendorapprove'])->name('vendors.approve');
    Route::get('/category/{category}', [Inventory::class, 'categorize'])->name('vendors.categorize');
    Route::get('/vendors/modal/create', [Inventory::class, 'ajaxCreateModal'])->name('vendors.ajax.modal');
    Route::post('/{vendor}/update-status', [Inventory::class, 'updateSupplierStatus'])->name('vendors.updateStatus');

});


Route::post('/Production', [Production::class, 'updateStatus'])->name('updateStatus');


Route::get('/Production', [Production::class, 'index'])->name('Production');

Route::get('/CreateProject', [Production::class, 'create'])->name('CreateProject');

Route::post('/Project/store', [Production::class, 'store'])->name('projects.store');

Route::get('/projects', [Production::class, 'Manage'])->name('projects.manage');
Route::get('/projectsHistory', [Production::class, 'History'])->name('projects.history');
Route::get('/projects/{id}/edit', [Production::class, 'edit'])->name('editProject');
Route::put('/projects/{id}', [Production::class, 'update'])->name('projects.update');
Route::delete('/projects/{id}', [Production::class, 'destroy'])->name('project.destroy');
Route::get('/projects/{id}', [Production::class, 'show'])->name('viewProject');
Route::post('/approval/{project_id}', [Production::class, 'Approval'])->name('approval.store');

//

Route::get('/CRM', [MarkertingController::class, 'index'])->name('CRM');
Route::get('/CRM/Leads', [LeadController::class, 'index'])->name('leads');
Route::get('/CRM/Leads/Create', [LeadController::class, 'create'])->name('leads.create');

Route::get('/CRM/Leads/edit', [LeadController::class, 'edit'])->name('leads.edit');

Route::get('/CRM/Leads/delete', [LeadController::class, ''])->name('leads.destroy');
Route::get('/CRM/Leads/store', [LeadController::class, 'store'])->name('leads.store');



Route::resource('deals', DealController::class);

Route::resource('Markerting', MarkertingController::class);

Route::post('/leads/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');


Route::get('/customers', [MarkertingController::class, 'customers'])->name('Markerting.customers');



// Show form
Route::get('/Production/Request', [Production::class, 'CreateRequisition'])->name('Production.request');

// Handle form submission
Route::post('/Production/storeRequest', [Production::class, 'storerequest'])->name('ProductionRequest.store');

Route::get('/production/material-requests', [Inventory::class, 'material'])->name('ProductionRequest.material');

Route::post('/production/material-approve', [Inventory::class, 'approveMaterialRequest'])->name('ProductionRequest.approve');



Route::get('/crm', [CrmController::class, 'index'])->name('crm.index');
Route::get('/crm/lead/{id}', [CrmController::class, 'show'])->name('leads.show');
Route::post('/crm/lead/store', [CrmController::class, 'store'])->name('leads.store');
Route::post('/leads/{id}/update-status', [CrmController::class, 'updateStatus'])->name('leads.updateStatus');


Route::prefix('clients')->group(function () {


    // Store new client (from modal form)
    Route::post('/store', [CrmController::class, 'clientstore'])->name('clients.store');

    // Optional: Edit client
    Route::get('/{client}/edit', [CrmController::class, 'edit'])->name('clients.edit');
    Route::put('/{client}', [CrmController::class, 'update'])->name('clients.update');

    // Optional: Delete client
    Route::delete('/{client}', [CrmController::class, 'destroy'])->name('clients.destroy');
});

});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


 Route::get('/fleet-management', [FleetManagementController::class, 'index'])->name('fleet.index');
    Route::post('/fleet-management', [FleetManagementController::class, 'store'])->name('fleet.store');
    Route::put('/fleet/{id}', [FleetManagementController::class, 'update'])->name('fleet.update');
    Route::delete('/fleet/{id}', [FleetManagementController::class, 'destroy'])->name('fleet.destroy');

    Route::post('/fleet/store-service-schedule', [FleetManagementController::class, 'StoreServiceSchedule'])
         ->name('fleet.storeServiceSchedule');

 Route::post('/fleet/update-service-schedule', [FleetManagementController::class, 'updateServiceSchedule'])
         ->name('fleet.UpdateServiceSchedule');

    Route::post('/fleet/update-service-date', [FleetManagementController::class, 'updateServiceDate'])
        ->name('fleet.updateServiceDate');

    Route::post('/fleet/repair/store', [FleetManagementController::class, 'storeRepair'])->name('fleet.storeRepair');
    Route::patch('/repair-logs/{id}/status', [FleetManagementController::class, 'updateStatus']);

    // 3. COMPLIANCE ROUTES
    Route::post('/fleet/compliance/store', [FleetManagementController::class, 'storeCompliance'])->name('fleet.storeCompliance');

    Route::put('/fleet/compliance/{id}', [FleetManagementController::class, 'updateCompliance'])
    ->name('fleet.updateCompliance');

    Route::post('/fuel/store', [FleetManagementController::class, 'storefuel'])->name('fuel.store');
Route::put('/fuel/update/{id}', [FleetManagementController::class, 'updatefuel'])->name('fuel.update');

Route::post('/fleet/accidents', [FleetManagementController::class, 'storeaccident'])->name('accidents.store');

Route::put('/fleet/accidents/{id}', [FleetManagementController::class, 'updateaccident'])->name('accidents.update');