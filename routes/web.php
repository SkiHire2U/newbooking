<?php

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

Route::get('/', 'PageController@getIndex');
Route::get('/home', 'PageController@redirectIndex');
Route::post('/dateDetails', 'PageController@postDateDetails')->name('dateDetails');
Route::get('/equipments', 'PageController@getEquipments')->name('equipments');
Route::post('/addToRack', 'PageController@postAddToRack')->name('addToRack');
Route::post('/removeFromRack/{name}', 'PageController@postRemoveFromRack')->name('removeFromRack');
Route::get('/rentals', 'PageController@getRentals')->name('rentals');
Route::post('/saveRenter/{name}', 'PageController@postSaveRenter')->name('saveRenter');
Route::post('/editRenter/{name}', 'PageController@postEditRenter')->name('editRenter');
Route::post('/removeFromRental/{name}', 'PageController@postRemoveFromRental')->name('removeFromRental');
Route::get('/details', 'PageController@getPartyDetails')->name('details');

/* REFERENCE */
Route::post('/reference-details/{id}', 'PageController@postUpdateDateDetails')->name('reference.updateDetails');
Route::post('/reference/', 'PageController@postReference')->name('reference');
Route::post('/reference/{id}', 'BookingController@updateBooking')->name('reference.update');
Route::get('/updated/', 'BookingController@updated')->name('updated');

Route::get('/admin/packages', 'AdminController@getPackages')->name('packages');
Route::put('/admin/package', 'AdminController@storePackage')->name('package.store');
Route::put('/admin/package/{id}', 'AdminController@updatePackage')->name('package.update');
Route::post('/admin/package/{id}', 'AdminController@deletePackage')->name('package.delete');
Route::get('/admin/accommodations', 'AdminController@getAccommodations')->name('accommodations');
Route::get('/admin/accommodations/{id}', 'AdminController@showAccommodation')->name('accommodation');
Route::put('/admin/accommodations/', 'AdminController@storeAccommodation')->name('accommodation.store');
Route::put('/admin/accommodations/{id}', 'AdminController@updateAccommodation')->name('accommodation.update');
Route::post('/admin/accommodations/{id}', 'AdminController@deleteAccommodation')->name('accommodation.delete');
Route::put('/admin/operators/', 'AdminController@storeOperator')->name('operator.store');
Route::put('/admin/operators/{id}', 'AdminController@updateOperator')->name('operator.update');
Route::post('/admin/operators/{id}', 'AdminController@deleteOperator')->name('operator.delete');
Route::get('/admin/bookings', 'AdminController@getBookings')->name('bookings');
Route::post('/admin/bookings-filter', 'AdminController@filterBookings')->name('bookings.filter');
Route::get('/admin/bookings/{id}', 'AdminController@showBooking')->name('booking');
Route::post('/admin/booking-reference', 'AdminController@findBookingReference')->name('booking.reference');
Route::put('/admin/update-booking/{id}', 'AdminController@updateBooking')->name('bookings.update');
Route::post('/admin/delete-booking/{id}', 'AdminController@deleteBooking')->name('booking.delete');
Route::post('/admin/booking/{id}', 'AdminController@notifyCustomer')->name('booking.email');
Route::put('/admin/rental/{id}', 'AdminController@updateRental')->name('rental.update');
Route::post('/admin/rental/{id}', 'AdminController@postRemoveFromList')->name('rental.delete');
Route::get('/admin/picking-list/{id}', 'AdminController@getPickingList')->name('picking');
Route::get('/admin/invoice/{id}', 'AdminController@getInvoice')->name('invoice');
Route::get('/admin/invoiceEdit/{id}', 'AdminController@editInvoice')->name('invoice.edit');
Route::put('/admin/invoiceUpdate/{id}', 'AdminController@updateInvoice')->name('invoice.update');
Route::get('/admin', 'AdminController@index');

/* BOOKING */
Route::get('/booking/showAccommodationSelection.action', 'PageController@redirectIndex');
Route::resource('booking', 'BookingController');

/* IMPORT */
Route::get('/import', 'BookingController@runImport')->name('import');
Route::get('/import-alt', 'BookingController@runImportAlt');

/* ERROR */
Route::get('/session-expired/', 'ErrorController@getSessionExpired')->name('error.expired');

/* SUPERADMIN */
Route::get('/generate-invoices/', 'AdminController@generateInvoicesAll');
