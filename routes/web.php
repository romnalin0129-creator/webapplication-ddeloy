<?php

    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\AppointmentBookingController;
    use App\Http\Controllers\MassageServiceController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\StoreProductController;
    use App\Http\Controllers\StorefrontController;
    use Illuminate\Support\Facades\Route;

    Route::redirect('/', '/home')->name('welcome');
    Route::get('/home', [StorefrontController::class, 'index'])->name('home');
    Route::post('/orders', [OrderController::class, 'store'])
        ->middleware(['auth', 'throttle:10,1'])->name('orders.store');
    Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{orderNumber}/payment', [OrderController::class, 'reportPayment'])
        ->middleware('throttle:10,1')->name('orders.report-payment');
});
    Route::get('/treatments', [StorefrontController::class, 'services'])->name('storefront.services');
    Route::get('/about', [StorefrontController::class, 'about'])->name('storefront.about');
    Route::get('/contact', [StorefrontController::class, 'contact'])->name('storefront.contact');
    Route::get('/booking/availability', [AppointmentBookingController::class, 'availability'])->name('booking.availability');
    Route::get('/booking', [AppointmentBookingController::class, 'create'])
        ->middleware('auth')->name('booking.create');
    Route::post('/booking', [AppointmentBookingController::class, 'store'])
     ->middleware(['auth', 'throttle:10,1'])->name('booking.store');

    Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [AppointmentBookingController::class, 'mine'])->name('bookings.mine');
    Route::get('/my-bookings/{booking}/edit', [AppointmentBookingController::class, 'edit'])->name('bookings.edit');
    Route::patch('/my-bookings/{booking}', [AppointmentBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/my-bookings/{booking}', [AppointmentBookingController::class, 'cancel'])->name('bookings.cancel');
});

    Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::patch('/admin/orders/{orderNumber}', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
    Route::patch('/admin/orders/{orderNumber}/payment', [OrderController::class, 'confirmPayment'])->name('admin.orders.payment');
    Route::get('/admin/products', [StoreProductController::class, 'index'])->name('admin.products.index');
    Route::post('/admin/products', [StoreProductController::class, 'store'])->name('admin.products.store');
    Route::put('/admin/products/{product}', [StoreProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [StoreProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/admin/members', [DashboardController::class, 'members'])->name('admin.members');
    Route::patch('/bookings/{booking}', [DashboardController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::get('/services', [MassageServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [MassageServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [MassageServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [MassageServiceController::class, 'destroy'])->name('services.destroy');
});

    Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
});
