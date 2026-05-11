<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\LogController;
use App\Models\Container;
use App\Models\LevelCase;
use App\Models\LevelPart;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $summary = [
        'containers' => Container::count(),
        'cases' => LevelCase::count(),
        'parts' => LevelPart::count(),
        'completedContainers' => Container::where('status', 'complete')->count(),
    ];

    return view('dashboard', compact('summary'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shipping Routes
    Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');
    Route::post('/shipping/import', [ShippingController::class, 'import'])->name('shipping.import');
    Route::get('/shipping/{id}', [ShippingController::class, 'show'])->name('shipping.show'); // List of containers
    Route::get('/shipping/{id}/export', [ShippingController::class, 'export'])->name('shipping.export'); // Export Excel
    Route::delete('/shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy');

    // Granular drill-down routes for massive scaling
    Route::get('/containers/{container}', [ShippingController::class, 'showContainer'])->name('containers.show'); // List cases
    Route::get('/cases/{case}', [ShippingController::class, 'showCase'])->name('cases.show'); // List parts

    // Status transition & sub-entity operations
    Route::post('/containers/{container}/complete', [ShippingController::class, 'completeContainer'])->name('containers.complete');
    Route::delete('/containers/{container}', [ShippingController::class, 'destroyContainer'])->name('containers.destroy');
    Route::delete('/parts/{part}', [ShippingController::class, 'destroyPart'])->name('parts.destroy');

    // Edit Part View & Update
    Route::get('/parts/{part}/edit', [ShippingController::class, 'editPart'])->name('parts.edit');
    Route::put('/parts/{part}', [ShippingController::class, 'updatePart'])->name('parts.update');
});

require __DIR__.'/auth.php';
