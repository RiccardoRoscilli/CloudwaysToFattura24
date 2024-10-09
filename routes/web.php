<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\CloudwaysController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\OrderController;
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
Route::middleware(['auth', 'role:admin'])->group(function () {
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
    // Rotte CRUD per i clienti
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/{id}/addApplication', [CustomerController::class, 'addApplication'])->name('customers.addApplication');
    Route::get('/import-customers-form', function () {
        return view('import_customers_form');
    })->name('import.customers.form');

    Route::resource('orders', OrderController::class);
    // pulsante invio a f24
    Route::post('/orders/{order}/sendToFattura24', [OrderController::class, 'sendToFattura24'])->name('orders.sendToFattura24');



    Route::get('/datatable/{model}', function ($model) {
        $controllerClass = '\\App\\Http\\Controllers\\' . ucfirst($model) . 'Controller';
        $method = 'get' . ucfirst($model) . 's';

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controllerInstance = app($controllerClass); // Crea un'istanza del controller
            return app()->call([$controllerInstance, $method]); // Chiama il metodo sull'istanza
        }

        abort(404, 'Controller o metodo non trovato');
    })->name('datatable.generic');

    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/associate-customer', [ApplicationController::class, 'associateCustomerToApplication'])->name('applications.associateCustomer');


    // api e import
    Route::post('/import-customers-csv', [CustomerController::class, 'importFromCsv'])->name('import.customers.csv');
    Route::get('/cloudways/servers', [CloudwaysController::class, 'getServers']);
});

require __DIR__ . '/auth.php';
