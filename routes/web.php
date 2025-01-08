<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\DataTableController;
use App\Http\Controllers\CloudwaysController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MailboxController;
use App\Http\Controllers\ConfigurationController;
use App\Mail\PaymentInstructions;
use App\Models\Customer;
use App\Models\Order;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/import-customers', [CustomerController::class, 'importFromFattura24'])->name('import.customers');
    // test apikey
    Route::get('/test-api-key', [OrderController::class, 'testApiKey'])->name('test.api.key');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/preview-payment-email/{orderId}', function ($orderId) {
    // Carica l'ordine con il cliente associato
    $order = Order::with('customer')->find($orderId);

    if (!$order) {
        abort(404, 'Ordine non trovato.');
    }

    $customer = $order->customer;

    if (!$customer) {
        abort(404, 'Cliente non trovato.');
    }

    return new \App\Mail\PaymentInstructions($customer, $order);
});

// rotte protette per l'admin
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::post('/orders/{order}/sendMail', [OrderController::class, 'sendMail'])->name('orders.sendMail');


    Route::get('/configuration/edit', [ConfigurationController::class, 'edit'])->name('configuration.edit');
    Route::post('/configuration/update', [ConfigurationController::class, 'update'])->name('configuration.update');

    Route::resource('mailboxes', MailboxController::class);
    Route::get('/datatable/mail-boxes', [MailboxController::class, 'getMailBoxesData']);

    // Rotta per l'importazione da Fattura24
    Route::post('/cloudways/import', [CloudwaysController::class, 'importServersAndApps'])->name('cloudways.import');

    Route::get('/servers', [ServerController::class, 'index'])->name('servers.index');
    Route::get('/servers/data', [ServerController::class, 'getServersData'])->name('servers.data');
    Route::get('/servers/{server}/edit', [ServerController::class, 'edit'])->name('servers.edit');
    Route::post('/servers/{server}', [ServerController::class, 'update'])->name('servers.update');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/data', [ApplicationController::class, 'getApplicationsData'])->name('applications.data');
    Route::get('/applications/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
    Route::post('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::put('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');


    // Rotte CRUD per i clienti
    Route::resource('customers', CustomerController::class);
    // services

    Route::resource('services', ServiceController::class);
    Route::get('/datatable/service', [ServiceController::class, 'getServices'])->name('services.datatable');

    Route::post('/customers/{id}/addApplication', [CustomerController::class, 'addApplication'])->name('customers.addApplication');
    Route::get('/import-customers-form', function () {
        return view('import_customers_form');
    })->name('import.customers.form');

    Route::get('/orders/in-progress', [OrderController::class, 'inProgress'])->name('orders.in_progress');
    Route::get('/orders/complete', [OrderController::class, 'complete'])->name('orders.complete');
    
    // Rotte resource (dopo quelle specifiche)
    Route::resource('orders', OrderController::class);
    
    // Pulsante invio a Fattura24
    Route::post('/orders/{order}/sendToFattura24', [OrderController::class, 'sendToFattura24'])->name('orders.sendToFattura24');
    Route::post('/order-items/{item}/updatePrice', [OrderController::class, 'updatePrice'])->name('orderItems.update');
    
    // test configurazione api
    Route::post('/test/cloudways-api', [ConfigurationController::class, 'testCloudwaysApi'])->name('test.cloudways.api');
    Route::post('/test/fattura24-api', [ConfigurationController::class, 'testFattura24Api'])->name('test.fattura24.api');


    Route::get('/datatable/{model}', function ($model) {
        $controllerClass = '\\App\\Http\\Controllers\\' . ucfirst($model) . 'Controller';

        if (substr($model, -1) === 'x') {
            $method = 'get' . ucfirst($model) . 'es'; // getMailBoxesData
        } else {
            $method = 'get' . ucfirst($model) . 's'; // getCustomersData
        }
        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controllerInstance = app($controllerClass); // Crea un'istanza del controller
            return app()->call([$controllerInstance, $method]); // Chiama il metodo sull'istanza
        }

        abort(404, 'Controller ' . $controllerClass . ' o metodo ' . $method . ' non trovato');
    })->name('datatable.generic');

    // associazioni servizi con clienti
    Route::get('/applications/{application}', [ApplicationController::class, 'associate'])->name('applications.show');
    Route::post('/applications/{application}/associate-customer', [ApplicationController::class, 'associateCustomerToApplication'])->name('applications.associateCustomer');

    Route::get('/mailboxes/{id}', [MailBoxController::class, 'show'])->name('mailboxes.show');
    Route::post('/mailboxes/{id}/associate', [MailBoxController::class, 'associateCustomerToMailBox'])->name('mailboxes.associate');


    // api e import
    Route::post('/import-customers-csv', [CustomerController::class, 'importFromCsv'])->name('import.customers.csv');
    Route::get('/cloudways/servers', [CloudwaysController::class, 'getServers']);
});

require __DIR__ . '/auth.php';
