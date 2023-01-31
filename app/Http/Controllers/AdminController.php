<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Accommodation;
use App\Booking;
use App\Operator;
use App\Package;
use App\Rental;
use App\Addon;
use App\Invoice;
use Carbon\Carbon;
use Session;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->calc = new CalculationController;
        $this->array = new ArrayController;
        $this->invoice = new InvoiceController;
        $this->email = new EmailController;

        $this->bookingModel = new Booking;
        $this->packageModel = new Package;
        $this->addonModel = new Addon;
        $this->rentalModel = new Rental;
        $this->accommodationModel = new Accommodation;
        $this->operatorModel = new Operator;

        $this->limitDays = 365;
        $this->filterMonthSessionKey = "monthFilter";
        $this->filterYearSessionKey = "yearFilter";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('admin.index');
        return redirect()->route('bookings');
    }

    public function getBookings()
    {   
        $defaultLimit = null;
        $now = Carbon::now();
        $year = Session::get($this->filterYearSessionKey);
        $month = Session::get($this->filterMonthSessionKey);

        if(is_null($month) && is_null($year)) {
            $defaultLimit = Carbon::now()->subDays($this->limitDays)->format('Y-m-d');
            $limit = $defaultLimit;
            $bookings = Booking::where('created_at', '>' , $limit)->get();
        } else {
            if(is_null($year)) {
                $year = $now->format('Y');
            }
            if(is_null($month)) {
                $month = $now->format('m');
            }

            $limit = $year . "-" . $month;

            $bookings = Booking::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();
        }

        foreach ($bookings as $booking) {
            if($booking->chalet_id == 1) {
                $booking->chalet_name = $booking->chalet_name . ' (Independent)';
            } else {
                $accommodation = $this->accommodationModel->find($booking->chalet_id);
                if($accommodation) {
                    $operator = $this->operatorModel->find($accommodation->operator_id);
                    if($operator) {
                        $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (' . $operator->name . ')';
                    } else {
                        $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (Operator not found.)';
                    }
                } else {
                    $booking->chalet_name = "Accommodation not found on database";
                }
            }
        }

        return view('admin.bookings')
            ->with('yearFilter', $year)
            ->with('monthFilter', $month)
            ->with('records', $limit)
            ->with('bookings', $bookings)
            ->with('bookingModel', $this->bookingModel)
            ->with('accommodationModel', $this->accommodationModel);
    }

    public function filterBookings(Request $request) {
        switch ($request->action) {
            case 'Filter':
                if($request->booking_month == "0") {
                    Session::forget($this->filterMonthSessionKey);
                } else {
                    Session::put($this->filterMonthSessionKey, $request->booking_month);
                }

                if($request->booking_year == "0") {
                    Session::forget($this->filterYearSessionKey);
                } else {
                    Session::put($this->filterYearSessionKey, $request->booking_year);
                }
                break;
            case 'Reset':
                Session::forget($this->filterMonthSessionKey);
                Session::forget($this->filterYearSessionKey);
                break;
        }

        return redirect()->route('bookings');
    }

    public function showBooking($id)
    {
        $booking = Booking::find($id);
        $invoice = $this->invoice->getInvoice($booking->invoice_id);

        $select['age'] = $this->array->getAgeArray();
        $select['height'] = $this->array->getHeightArray();
        $select['weight'] = $this->array->getWeightArray();
        $select['foot'] = $this->array->getFootArray();
        $select['level'] = $this->array->getLevelArray();
        $select['packages'] = $this->array->getPackagesArray();

        if($booking->chalet_id == 1) {
            $booking->chalet_name = $booking->chalet_name . ' (Independent)';
        } else {
            $accommodation = $this->accommodationModel->find($booking->chalet_id);
            $operator = $this->operatorModel->find($accommodation->operator_id);
            if($operator) {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (' . $operator->name . ')';
            } else {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (Operator not found.)';
            }
        }

        $details = $this->calc->getDetails($id);
        $packages = $this->calc->getPackages($id);
        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $prices = array();
        if($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }

        foreach($booking->rentals as $rental) {
            $rental['addons'] = json_decode($rental['addons']);
        }

        return view('admin.booking')
            ->with('booking', $booking)
            ->with('days', $days)
            ->with('select', $select)
            ->with('chalet', $chalet)
            ->with('invoice', $invoice)
            ->with('prices', $prices)
            ->with('bookingModel', $this->bookingModel)
            ->with('rentalModel', $this->rentalModel)
            ->with('packageModel', $this->packageModel)
            ->with('accommodationModel', $this->accommodationModel);
    }

    public function findBookingReference(Request $request)
	{
        $booking = Booking::where('reference_number', $request->booking_reference)->first();
        if($booking) {
            return redirect()->route('booking', $booking->id);
        } else {
            Session::flash('warning', 'Booking reference number not found');
            
            return redirect()->route('bookings');
        }
    }
	
	public function findBookingName(Request $request)
	{
        $booking = Booking::where('reference_number', $request->booking_reference)->first();
        if($booking) {
            return redirect()->route('booking', $booking->id);
        } else {
            Session::flash('warning', 'Booking reference number not found');
            
            return redirect()->route('bookings');
        }
    }

    public function updateBooking(Request $request, $id) {
        $booking = Booking::find($id);
        $booking->party_leader = $request['party_leader'];
        $booking->party_email = $request['party_email'];
        $booking->save();

        return redirect()->route('booking', $id);
    }

    public function deleteBooking($id) {
        $booking = Booking::find($id);
        Rental::where('booking_id', $booking->id)->delete();
        Invoice::destroy($booking->invoice_id);
        Booking::destroy($id);

        Session::flash('success', 'Booking deleted successfully!');

        return redirect()->route('bookings');
    }

    public function updateRental(Request $request, $id) {
        $this->validate($request,
            array(
                'package_renter' => 'required',
                'renter_sex' => 'required',
                'renter_age' => 'required',
                'renter_ability' => 'required',
                'renter_weight' => 'required',
                'renter_height' => 'required',
                'renter_foot' => 'required',
                'package_id' => 'required',
                'rent_days' => 'required',
            )
        );

        $package = array();
        $package['package_renter'] = $request->package_renter;
        $package['renter_sex'] = $request->renter_sex;
        $package['renter_age'] = $request->renter_age;
        $package['renter_ability'] = $request->renter_ability;
        $package['renter_weight'] = $request->renter_weight;
        $package['renter_height'] = $request->renter_height;
        $package['renter_foot'] = $request->renter_foot;

        $rental = Rental::find($id);
        $rental->package_id = $request->package_id;
        $rental->addons = json_encode($request->addon);
        $rental->duration = $request->rent_days;
        $rental->name = $request->package_renter;
        $rental->age = $package['renter_age'];
        $rental->sex = $package['renter_sex'];
        $rental->ability = $package['renter_ability'];
        $rental->weight = $package['renter_weight'];
        $rental->height = $package['renter_height'];
        $rental->foot = $package['renter_foot'];
        $rental->ski_length = $this->calc->getSkiLength($package);
        $rental->pole_length = $this->calc->getPoleLength($package);
        $rental->skier_code = $this->calc->getSkierCode($package);
        $rental->boot_size = $this->calc->getMondpoint($package);
        $rental->din = $this->calc->getDIN($package);
        $rental->notes = $request->notes;
        $rental->save();

        $this->invoice->updateInvoiceRental($request->booking_id, $id);

        return redirect()->route('booking', $request->booking_id);
    }

    public function postRemoveFromList(Request $request, $id) {
        $rental = Rental::findOrFail($id);
        $name = $rental->name;

        $this->invoice->removeFromInvoice($request->booking_id, $id);

        $rental->delete();

        Session::flash('success', $name . ' has been successfully removed from the list.');

        return redirect()->route('booking', $request->booking_id);
    }

    public function getPickingList($id) {
        $booking = Booking::find($id);

        $select['age'] = $this->array->getAgeArray();
        $select['height'] = $this->array->getHeightArray();
        $select['weight'] = $this->array->getWeightArray();
        $select['foot'] = $this->array->getFootArray();
        $select['level'] = $this->array->getLevelArray();
        $select['packages'] = $this->array->getPackagesArray();

        if($booking->chalet_id == 1) {
            $booking->chalet_name = $booking->chalet_name . ' (Independent)';
        } else {
            $accommodation = $this->accommodationModel->find($booking->chalet_id);
            $operator = $this->operatorModel->find($accommodation->operator_id);
            if($operator) {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (' . $operator->name . ')';
            } else {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (Operator not found.)';
            }
        }

        $details = array(
            'chalet_id' => $booking->chalet_id,
            'chalet_name' => $booking->chalet_name,
            'arrival_dtp' => $booking->arrival_datetime,
            'departure_dtp' => $booking->departure_datetime,
            'mountain_dtp' => $booking->mountain_datetime,
            'booking_id' => $booking->id,
        );

        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $new = [];
        $packages = $booking->rentals;
        foreach ($packages as $package) {
            $new[$package->name] = array(
                'package_id' => $package->package_id,
                'addon' => json_decode($package->addons, true),
                'rent_days' => $package->duration,
                'package_renter' => $package->name,
                'renter_age' => $package->age,
                'renter_sex' => $package->sex,
                'renter_ability' => $package->ability,
                'renter_weight' => $package->weight,
                'renter_height' => $package->height,
                'renter_foot' => $package->foot,
                'renter_notes' => $package->notes,
                'rent_status' => 'saved',
                'rental_id' => $package->id,
            );
        }
        $packages = $new;

        $prices = array();
        if($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }

        foreach($booking->rentals as $rental) {
            $rental['addons'] = json_decode($rental['addons']);
        }

        return view('admin.picking')
            ->with('booking', $booking)
            ->with('days', $days)
            ->with('select', $select)
            ->with('chalet', $chalet)
            ->with('prices', $prices)
            ->with('bookingModel', $this->bookingModel)
            ->with('rentalModel', $this->rentalModel)
            ->with('packageModel', $this->packageModel)
            ->with('accommodationModel', $this->accommodationModel);
    }

    public function notifyCustomer($id) {
        $accommodationModel = new Accommodation;
        $packageModel = new Package;

        $booking = Booking::find($id);
        $details = array();
        $details['chalet_id'] = $booking->chalet_id;
        $details['chalet_name'] = $booking->chalet_name;
        $details['reference_number'] = $booking->reference_number;
        $details['party_leader'] = $booking->party_leader;
        $details['party_email'] = $booking->party_email;
        $details['party_mobile'] = $booking->party_mobile;
        $details['arrival_dtp'] = $booking->arrival_datetime;
        $details['departure_dtp'] = $booking->departure_datetime;
        $details['mountain_dtp'] = $booking->mountain_datetime;

        /*
        if($details['chalet_id'] == 1) {
            $name = $details['chalet_name'] . ' (Independent)';
        } else {
            $name = $accommodationModel->getAccommodationName($details['chalet_id']);
        }
        */

        if($booking->chalet_id == 1) {
            $name = $booking->chalet_name . ' (Independent)';
        } else {
            $accommodation = $this->accommodationModel->find($booking->chalet_id);
            $operator = $this->operatorModel->find($accommodation->operator_id);
            if($operator) {
                $name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (' . $operator->name . ')';
            } else {
                $name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (Operator not found.)';
            }
        }

        $new = [];
        $packages = $booking->rentals;
        foreach ($packages as $package) {
            $new[$package->name] = array(
                'package_id' => $package->package_id,
                'addon' => json_decode($package->addons, true),
                'rent_days' => $package->duration,
                'renter_id' => $package->id,
                'package_renter' => $package->name,
                'renter_age' => $package->age,
                'renter_sex' => $package->sex,
                'renter_ability' => $package->ability,
                'renter_weight' => $package->weight,
                'renter_height' => $package->height,
                'renter_foot' => $package->foot,
                'renter_notes' => $package->notes,
                'package_name' => $packageModel->getPackageName($package->package_id),
                'package_level' => $packageModel->getPackageLevel($package->package_id),
                'package_type' => $packageModel->getPackageType($package->package_id),
            );
        }
        $packages = $new;

        $select['age'] = $this->array->getAgeArray();
        $select['height'] = $this->array->getHeightArray();
        $select['weight'] = $this->array->getWeightArray();
        $select['foot'] = $this->array->getFootArray();
        $select['level'] = $this->array->getLevelArray();
        $select['packages'] = $this->array->getPackagesArray();

        $invoice = json_decode($this->invoice->getInvoice($booking->invoice_id), true);

        $data = array(
            'type' => 'updated',
            'subject' => 'Booking Updated!',
            'name' => $details['party_leader'],
            'email' => $details['party_email'],
            'accommodation' => $name,
            'arrival' => $details['arrival_dtp'],
            'departure' => $details['departure_dtp'],
            'mountain' => $details['mountain_dtp'],
            'reference' => $details['reference_number'],
            'packages' => $packages,
            'invoice' => $invoice,
            'select' => $select,
        );

        $this->email->sendMail($data);

        $data['subject'] = 'Amended Booking (Admin Panel)';

        $this->email->sendAdminMail($data);

        Session::flash('success', 'Email successfully sent.');

        return redirect()->route('booking', $id);
    }

    public function getInvoice($id) {
        $booking = Booking::find($id);
        $invoice = $this->invoice->getInvoice($booking->invoice_id);

        //dd($invoice);

        if($booking->chalet_id == 1) {
            $booking->chalet_name = $booking->chalet_name . ' (Independent)';
        } else {
            $accommodation = $this->accommodationModel->find($booking->chalet_id);
            $operator = $this->operatorModel->find($accommodation->operator_id);
            if($operator) {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (' . $operator->name . ')';
            } else {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (Operator not found.)';
            }
        }

        $details = array(
            'chalet_id' => $booking->chalet_id,
            'chalet_name' => $booking->chalet_name,
            'arrival_dtp' => $booking->arrival_datetime,
            'departure_dtp' => $booking->departure_datetime,
            'mountain_dtp' => $booking->mountain_datetime,
            'booking_id' => $booking->id,
        );

        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $new = [];
        $packages = $booking->rentals;
        foreach ($packages as $package) {
            $new[$package->name] = array(
                'package_id' => $package->package_id,
                'addon' => json_decode($package->addons, true),
                'rent_days' => $package->duration,
                'package_renter' => $package->name,
                'renter_age' => $package->age,
                'renter_sex' => $package->sex,
                'renter_ability' => $package->ability,
                'renter_weight' => $package->weight,
                'renter_height' => $package->height,
                'renter_foot' => $package->foot,
                'renter_notes' => $package->notes,
                'rent_status' => 'saved',
                'rental_id' => $package->id,
            );
        }
        $packages = $new;

        //dd($invoice);

        /*
        $prices = array();
        if($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }
        */

        foreach($booking->rentals as $rental) {
            $rental['addons'] = json_decode($rental['addons']);
        }

        return view('admin.invoice')
            ->with('booking', $booking)
            ->with('days', $days)
            ->with('chalet', $chalet)
            ->with('invoice', $invoice)
            ->with('bookingModel', $this->bookingModel)
            ->with('rentalModel', $this->rentalModel)
            ->with('packageModel', $this->packageModel)
            ->with('accommodationModel', $this->accommodationModel);
    }

    public function editInvoice($id) {
        $booking = Booking::find($id);
        $invoice = $this->invoice->getInvoice($booking->invoice_id);

        //dd($invoice);

        if($booking->chalet_id == 1) {
            $booking->chalet_name = $booking->chalet_name . ' (Independent)';
        } else {
            $accommodation = $this->accommodationModel->find($booking->chalet_id);
            $operator = $this->operatorModel->find($accommodation->operator_id);
            if($operator) {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (' . $operator->name . ')';
            } else {
                $booking->chalet_name = $this->accommodationModel->getAccommodationName($booking->chalet_id) . ' (Operator not found.)';
            }
        }

        $details = array(
            'chalet_id' => $booking->chalet_id,
            'chalet_name' => $booking->chalet_name,
            'arrival_dtp' => $booking->arrival_datetime,
            'departure_dtp' => $booking->departure_datetime,
            'mountain_dtp' => $booking->mountain_datetime,
            'booking_id' => $booking->id,
        );

        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $new = [];
        $packages = $booking->rentals;
        foreach ($packages as $package) {
            $new[$package->name] = array(
                'package_id' => $package->package_id,
                'addon' => json_decode($package->addons, true),
                'rent_days' => $package->duration,
                'package_renter' => $package->name,
                'renter_age' => $package->age,
                'renter_sex' => $package->sex,
                'renter_ability' => $package->ability,
                'renter_weight' => $package->weight,
                'renter_height' => $package->height,
                'renter_foot' => $package->foot,
                'renter_notes' => $package->notes,
                'rent_status' => 'saved',
                'rental_id' => $package->id,
            );
        }
        $packages = $new;

        /*
        $prices = array();
        if($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }
        */

        foreach($booking->rentals as $rental) {
            $rental['addons'] = json_decode($rental['addons']);
        }

        return view('admin.editInvoice')
            ->with('booking', $booking)
            ->with('days', $days)
            ->with('chalet', $chalet)
            ->with('invoice', $invoice)
            ->with('bookingModel', $this->bookingModel)
            ->with('rentalModel', $this->rentalModel)
            ->with('packageModel', $this->packageModel)
            ->with('accommodationModel', $this->accommodationModel);
    }

    public function updateInvoice(Request $request, $id) {
        $this->invoice->updateInvoiceAdmin($id, $request);

        return redirect()->route('invoice', $id);
    }

    public function getAccommodations()
    {
        $operators = Operator::all();
        $accommodations = Accommodation::all();

        return view('admin.accommodations')
            ->with('operators', $operators)
            ->with('accommodations', $accommodations);
    }

    public function storeOperator(Request $request)
    {
        $this->validate($request,
            array(
                'operator_name' => 'required',
            )
        );

        $operator = new Operator;
        $operator->name = $request->operator_name;
        $operator->web_address = $request->web_address;
        $operator->postal_address = $request->postal_address;
        $operator->notes = $request->notes;
        $operator->is_active = $request->is_active === 'on' ? true : false;
        $operator->save();

        Session::flash('success', 'Operator added successfully!');

        return redirect()->route('accommodations');
    }

    public function updateOperator(Request $request, $id)
    {
        $this->validate($request,
            array(
                'operator_name' => 'required',
            )
        );

        $operator = Operator::find($id);
        $operator->name = $request->operator_name;
        $operator->web_address = $request->web_address;
        $operator->postal_address = $request->postal_address;
        $operator->notes = $request->notes;
        $operator->is_active = $request->is_active === 'on' ? true : false;
        $operator->save();

        Session::flash('success', 'Operator updated successfully!');

        return redirect()->route('accommodations');
    }

    public function deleteOperator($id)
    {
        Operator::destroy($id);

        Session::flash('success', 'Operator deleted successfully!');

        return redirect()->route('accommodations');
    }

    public function showAccommodation($id)
    {
        $operator = Operator::find($id);
        $accommodations = $operator->accommodations;

        return view('admin.accommodation')
            ->with('operator', $operator)
            ->with('accommodations', $accommodations);
    }

    public function storeAccommodation(Request $request)
    {
        $this->validate($request,
            array(
                'operator_id' => 'required',
                'chalet_name' => 'required',
            )
        );

        $accommodation = new Accommodation;
        $accommodation->operator_id = $request->operator_id;
        $accommodation->name = $request->chalet_name;
        $accommodation->postal_address = $request->postal_address;
        $accommodation->discount = $request->discount ? $request->discount : null ;
        $accommodation->notes = $request->notes;
        $accommodation->is_active = $request->is_active === 'on' ? true : false;
        $accommodation->save();

        Session::flash('success', 'Chalet added successfully!');

        return redirect()->route('accommodation', $request->operator_id);
    }

    public function updateAccommodation(Request $request, $id)
    {
        $this->validate($request,
            array(
                'operator_id' => 'required',
                'chalet_name' => 'required',
            )
        );
        $accommodation = Accommodation::find($id);
        $accommodation->name = $request->chalet_name;
        $accommodation->postal_address = $request->postal_address;
        $accommodation->discount = $request->discount ? $request->discount : null ;
        $accommodation->notes = $request->notes;
        $accommodation->is_active = $request->is_active === 'on' ? true : false;
        $accommodation->save();

        Session::flash('success', 'Chalet updated successfully!');

        return redirect()->route('accommodation', $request->operator_id);
    }

    public function deleteAccommodation(Request $request, $id)
    {
        Accommodation::destroy($id);

        Session::flash('success', 'Chalet deleted successfully!');

        return redirect()->route('accommodation', $request->operator_id);
    }

    public function getPackages()
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            $package->prices = json_decode($package->prices);
        }
        //dd($packages);
        return view('admin.packages')
            ->with('packages', $packages);
    }

    public function storePackage(Request $request) {
        $this->validate($request,
            array(
                'package_name' => 'required',
                'type' => 'required',
            )
        );

        $package = new Package;
        $package->name = $request->package_name;
        $package->level = $request->level;
        $package->type = $request->type;
        $package->category = $request->category;
        $package->prices = json_encode($request->prices);
        $package->notes = $request->notes;
        if($request->image_url == '') {
            $request->image_url = '/images/ski.jpg';
        }
        $package->image_url = $request->image_url;
        $package->save();

        Session::flash('success', 'Package added successfully!');

        return redirect()->route('packages');
    }

    public function updatePackage(Request $request, $id) {
        $this->validate($request,
            array(
                'package_name' => 'required',
                'type' => 'required',
            )
        );

        $package = Package::find($id);
        $package->name = $request->package_name;
        $package->level = $request->level;
        $package->type = $request->type;
        $package->category = $request->category;
        $package->prices = json_encode($request->prices);
        $package->notes = $request->notes;
        if($request->image_url == '') {
            $request->image_url = '/images/ski.jpg';
        }
        $package->image_url = $request->image_url;
        $package->save();

        Session::flash('success', 'Package updated successfully!');

        return redirect()->route('packages');
    }

    public function deletePackage($id) {
        Package::destroy($id);

        Session::flash('success', 'Package deleted successfully!');

        return redirect()->route('packages');
    }

    public function generateInvoicesAll() {
        $bookings = Booking::all();

        foreach ($bookings as $booking) {
            $this->invoice->updateInvoice($booking->id);
        }

        dd('Invoices generated!');
    }
}
