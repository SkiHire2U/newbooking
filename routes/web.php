<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', [PageController::class, 'getIndex']);
Route::get('/home', [PageController::class, 'redirectIndex']);
Route::post('/dateDetails', [PageController::class, 'postDateDetails'])->name('dateDetails');
Route::get('/equipments', [PageController::class, 'getEquipments'])->name('equipments');
Route::post('/addToRack', [PageController::class, 'postAddToRack'])->name('addToRack');
Route::post('/removeFromRack/{name}', [PageController::class, 'postRemoveFromRack'])->name('removeFromRack');
Route::get('/rentals', [PageController::class, 'getRentals'])->name('rentals');
Route::post('/saveRenter/{name}', [PageController::class, 'postSaveRenter'])->name('saveRenter');
Route::post('/editRenter/{name}', [PageController::class, 'postEditRenter'])->name('editRenter');
Route::post('/removeFromRental/{name}', [PageController::class, 'postRemoveFromRental'])->name('removeFromRental');
Route::get('/details', [PageController::class, 'getPartyDetails'])->name('details');

/* REFERENCE */
Route::post('/reference-details/{id}', [PageController::class, 'postUpdateDateDetails'])->name('reference.updateDetails');
Route::post('/reference/', [PageController::class, 'postReference'])->name('reference');
Route::post('/reference/{id}', [BookingController::class, 'updateBooking'])->name('reference.update');
Route::get('/updated/', [BookingController::class, 'updated'])->name('updated');

Route::get('/admin/packages', [AdminController::class, 'getPackages'])->name('packages');
Route::put('/admin/package', [AdminController::class, 'storePackage'])->name('package.store');
Route::put('/admin/package/{id}', [AdminController::class, 'updatePackage'])->name('package.update');
Route::post('/admin/package/{id}', [AdminController::class, 'deletePackage'])->name('package.delete');
Route::get('/admin/accommodations', [AdminController::class, 'getAccommodations'])->name('accommodations');
Route::get('/admin/accommodations/{id}', [AdminController::class, 'showAccommodation'])->name('accommodation');
Route::put('/admin/accommodations/', [AdminController::class, 'storeAccommodation'])->name('accommodation.store');
Route::put('/admin/accommodations/{id}', [AdminController::class, 'updateAccommodation'])->name('accommodation.update');
Route::post('/admin/accommodations/{id}', [AdminController::class, 'deleteAccommodation'])->name('accommodation.delete');
Route::put('/admin/operators/', [AdminController::class, 'storeOperator'])->name('operator.store');
Route::put('/admin/operators/{id}', [AdminController::class, 'updateOperator'])->name('operator.update');
Route::post('/admin/operators/{id}', [AdminController::class, 'deleteOperator'])->name('operator.delete');
Route::get('/admin/bookings', [AdminController::class, 'getBookings'])->name('bookings');
Route::post('/admin/bookings-filter', [AdminController::class, 'filterBookings'])->name('bookings.filter');
Route::get('/admin/bookings/{id}', [AdminController::class, 'showBooking'])->name('booking');
Route::post('/admin/booking-reference', [AdminController::class, 'findBookingReference'])->name('booking.reference');
Route::put('/admin/update-booking/{id}', [AdminController::class, 'updateBooking'])->name('bookings.update');
Route::post('/admin/delete-booking/{id}', [AdminController::class, 'deleteBooking'])->name('booking.delete');
Route::post('/admin/booking/{id}', [AdminController::class, 'notifyCustomer'])->name('booking.email');
Route::put('/admin/rental/{id}', [AdminController::class, 'updateRental'])->name('rental.update');
Route::post('/admin/rental/{id}', [AdminController::class, 'postRemoveFromList'])->name('rental.delete');
Route::get('/admin/picking-list/{id}', [AdminController::class, 'getPickingList'])->name('picking');
Route::get('/admin/invoice/{id}', [AdminController::class, 'getInvoice'])->name('invoice');
Route::get('/admin/invoiceEdit/{id}', [AdminController::class, 'editInvoice'])->name('invoice.edit');
Route::put('/admin/invoiceUpdate/{id}', [AdminController::class, 'updateInvoice'])->name('invoice.update');
Route::get('/admin', [AdminController::class, 'index']);

/* BOOKING */
Route::get('/booking/showAccommodationSelection.action', [PageController::class, 'redirectIndex']);
Route::resource('booking', BookingController::class);

/* IMPORT */
Route::get('/import', [BookingController::class, 'runImport'])->name('import');
Route::get('/import-alt', [BookingController::class, 'runImportAlt']);

/* ERROR */
Route::get('/session-expired/', [ErrorController::class, 'getSessionExpired'])->name('error.expired');

/* SUPERADMIN */
Route::get('/generate-invoices/', [AdminController::class, 'generateInvoicesAll']);
