<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\FMClient;
use App\Models\FMRental;
use App\Models\Operator;
use App\Models\Package;
use App\Models\Rental;
use Carbon\Carbon;
use Excel;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Session;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->calc = new CalculationController;
        $this->array = new ArrayController;
        $this->invoice = new InvoiceController;
        $this->email = new EmailController;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('booking.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'party_leader' => 'required',
                'party_email' => 'required',
                'terms_and_conditions' => 'required',
            ]
        );

        $details = session()->get('partyDetails');
        $packages = session()->get('packages');

        if (!$details) {
            return redirect()->route('error.expired');
        }

        $now = Carbon::now();
        $ref = substr(strtoupper(hash('md5', $now)), -6);

        $booking = new Booking;
        $booking->chalet_id = $details['chalet_id'];
        if ($details['chalet_id'] == 1) {
            $booking->chalet_name = $details['chalet_name'];
            $booking->chalet_address = $details['chalet_address'];
        }
        $booking->reference_number = $ref;
        $booking->party_leader = $request['party_leader'];
        $booking->party_email = $request['party_email'];
        $booking->party_mobile = $request['party_mobile'];
        $booking->arrival_datetime = $this->returnDateTimeFormat($details['arrival_dtp']);
        $booking->departure_datetime = $this->returnDateTimeFormat($details['departure_dtp']);
        $booking->mountain_datetime = $this->returnDateTimeFormat($details['mountain_dtp']);
        $booking->notes = $request['party_notes'];
        $booking->save();

        $booking_id = $booking->id;

        foreach ($packages as $package) {
            $rental = [
                'booking_id' => $booking_id,
                'package_id' => $package['package_id'],
                'addons' => json_encode($package['addon']),
                'duration' => $package['rent_days'],
                'name' => $package['package_renter'],
                'age' => $package['renter_age'],
                'sex' => $package['renter_sex'],
                'ability' => $package['renter_ability'],
                'weight' => $package['renter_weight'],
                'height' => $package['renter_height'],
                'foot' => $package['renter_foot'],
                'ski_length' => $this->calc->getSkiLength($package),
                'pole_length' => $this->calc->getPoleLength($package),
                'skier_code' => $this->calc->getSkierCode($package),
                'boot_size' => $this->calc->getMondpoint($package),
                'din' => $this->calc->getDIN($package),
                'notes' => $package['renter_notes'],
            ];
            Rental::unguard();
            Rental::create($rental);
        }

        $invoice_id = $this->invoice->saveInvoice($booking_id);

        $booking->invoice_id = $invoice_id;
        $booking->save();

        $accommodationModel = new Accommodation;
        $operatorModel = new Operator;
        $packageModel = new Package;

        if ($details['chalet_id'] == 1) {
            $name = $details['chalet_name'] . ' (Independent)';
        } else {
            $accommodation = $accommodationModel->find($details['chalet_id']);
            $operator = $operatorModel->find($accommodation->operator_id);
            if ($operator) {
                $name = $accommodationModel->getAccommodationName($details['chalet_id']) . ' (' . $operator->name . ')';
            } else {
                $name = $accommodationModel->getAccommodationName($details['chalet_id']) . ' (Operator not found.)';
            }
        }

        $new = [];
        $packages = $booking->rentals;
        foreach ($packages as $package) {
            $new[$package->name] = [
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
            ];
        }
        $packages = $new;

        $select['age'] = $this->array->getAgeArray();
        $select['height'] = $this->array->getHeightArray();
        $select['weight'] = $this->array->getWeightArray();
        $select['foot'] = $this->array->getFootArray();
        $select['level'] = $this->array->getLevelArray();
        $select['packages'] = $this->array->getPackagesArray();

        $invoice = json_decode($this->invoice->getInvoice($booking->invoice_id), true);

        $data = [
            'type' => 'booked',
            'subject' => 'Thank you for booking with SkiHire2U. This is your booking confirmation.',
            'name' => $request['party_leader'],
            'email' => $request['party_email'],
            'accommodation' => $name,
            'arrival' => $details['arrival_dtp'],
            'mountain' => $details['mountain_dtp'],
            'departure' => $details['departure_dtp'],
            'reference' => $ref,
            'packages' => $packages,
            'invoice' => $invoice,
            'select' => $select,
        ];

        $this->email->sendMail($data);

        $data['subject'] = 'New Booking';

        $this->email->sendAdminMail($data);

        $name = $request['party_leader'];
        $nameExploded = explode ( ' ', $name, 2);

        $firstName = $nameExploded[0];
        $lastName = $nameExploded[1];
        //Try to find or create client
        try {
            $fmClient = FMCLient::firstOrNew(['First' => $firstName, 'Last' => $lastName]);
            $fmClient->Email = $request['party_email'];
            $fmClient->Company = $name;
            $fmClient->phone = $request['party_mobile'];
            $fmClient->Address = $details['chalet_name'];
            $fmClient->address2 = $details['chalet_address'];
            $fmClient->save();
            $clientId = $fmClient->id;

            $fmRental = new FMRental;
            $fmRental->id_Customer = $clientId;
            $fmRental->Date = $details['arrival_dtp'];
            $fmRental->DateEnd = $details['departure_dtp'];
            $fmRental->reference_no = $ref;
            $fmRental->party_number = '';
            $fmRental->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        //put rental on client


        Session::flash('success', 'You have successfully submitted your booking. We sent you an email containing the reference number of your booking to ' . $request['party_email'] . ' so you can revisit it in the future. Please check your spam folder just in case, if you did not receive an email please contact us at info@skihire2u.com');

        return redirect('/');
        /*
        return view('booking.thankyou')
            ->with('email', $request['party_email']);
        */
    }

    public function returnDateTimeFormat($date)
    {
        return Carbon::parse($date)->toDateTimeString();
    }

    public function returnDateTimeFormatDb($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString();
    }

    public function returnDateTimeFormat2($date)
    {
        return Carbon::createFromFormat('m/d/Y H:i', $date)->toDateTimeString();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    public function updateBooking(Request $request, $id): RedirectResponse
    {
        $packages = session()->get('packages');

        if (!$packages) {
            return redirect()->route('error.expired');
        }

        foreach ($packages as $package) {
            $rental = [
                'booking_id' => $id,
                'package_id' => $package['package_id'],
                'addons' => json_encode($package['addon']),
                'duration' => $package['rent_days'],
                'name' => $package['package_renter'],
                'age' => $package['renter_age'],
                'sex' => $package['renter_sex'],
                'ability' => $package['renter_ability'],
                'weight' => $package['renter_weight'],
                'height' => $package['renter_height'],
                'foot' => $package['renter_foot'],
                'ski_length' => $this->calc->getSkiLength($package),
                'pole_length' => $this->calc->getPoleLength($package),
                'skier_code' => $this->calc->getSkierCode($package),
                'boot_size' => $this->calc->getMondpoint($package),
                'din' => $this->calc->getDIN($package),
                'notes' => $package['renter_notes'],
            ];
            Rental::unguard();
            Rental::updateOrCreate(['id' => $package['rental_id']], $rental);
        }

        $accommodationModel = new Accommodation;
        $operatorModel = new Operator;
        $packageModel = new Package;

        $details = session()->get('partyDetails');

        $booking = Booking::find($id);
        $booking->chalet_id = $details['chalet_id'];
        if ($details['chalet_id'] == 1) {
            $booking->chalet_name = $details['chalet_name'];
            $booking->chalet_address = $details['chalet_address'];
        }
        $booking->party_leader = $details['party_leader'];
        $booking->party_email = $details['party_email'];
        $booking->party_mobile = $details['party_mobile'];
        $booking->arrival_datetime = $this->returnDateTimeFormat($details['arrival_dtp']);
        $booking->departure_datetime = $this->returnDateTimeFormat($details['departure_dtp']);
        $booking->mountain_datetime = $this->returnDateTimeFormat($details['mountain_dtp']);
        $booking->save();

        $booking = Booking::find($id);
        if ($booking->invoice_id == null) {
            $invoice_id = $this->invoice->saveInvoice($id);
            $booking->invoice_id = $invoice_id;
            $booking->save();
        } else {
            $this->invoice->updateInvoice($id);
        }

        if ($details['chalet_id'] == 1) {
            $name = $details['chalet_name'] . ' (Independent)';
        } else {
            $accommodation = $accommodationModel->find($details['chalet_id']);
            $operator = $operatorModel->find($accommodation->operator_id);
            if ($operator) {
                $name = $accommodationModel->getAccommodationName($details['chalet_id']) . ' (' . $operator->name . ')';
            } else {
                $name = $accommodationModel->getAccommodationName($details['chalet_id']) . ' (Operator not found.)';
            }
        }

        $new = [];
        $packages = $booking->rentals;
        foreach ($packages as $package) {
            $new[$package->name] = [
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
            ];
        }
        $packages = $new;

        $select['age'] = $this->array->getAgeArray();
        $select['height'] = $this->array->getHeightArray();
        $select['weight'] = $this->array->getWeightArray();
        $select['foot'] = $this->array->getFootArray();
        $select['level'] = $this->array->getLevelArray();
        $select['packages'] = $this->array->getPackagesArray();

        $invoice = json_decode($this->invoice->getInvoice($booking->invoice_id), true);

        $data = [
            'type' => 'updated',
            'subject' => 'Booking Updated!',
            'name' => $details['party_leader'],
            'email' => $details['party_email'],
            'accommodation' => $name,
            'arrival' => $details['arrival_dtp'],
            'mountain' => $details['mountain_dtp'],
            'departure' => $details['departure_dtp'],
            'reference' => $details['reference_number'],
            'packages' => $packages,
            'invoice' => $invoice,
            'select' => $select,
        ];

        $this->email->sendMail($data);

        $data['subject'] = 'Amended Booking';

        $this->email->sendAdminMail($data);

        Session::flash('success', 'You have successfully updated your booking.  We sent an email regarding the updates to this booking to ' . $details['party_email'] . '. Please check your spam folder just in case, if you did not receive an email please contact us at info@skihire2u.com');

        Session::forget('reference');

        return redirect('/');

        // return redirect()->route('updated');
    }

    public function updated(): View
    {
        return view('booking.updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }

    public function runImportAlt()
    {
        Excel::load('rental.csv', function ($reader) {
            $results = $reader->all();
            $packages = [];
            foreach ($results as $row) {
                $packages[$row->rentalid] = [
                    'package_renter' => $row->fullname,
                    'renter_age' => $row->age,
                    'renter_sex' => $row->sex,
                    'renter_notes' => $row->notes,
                    'renter_ability' => $row->ability,
                    'renter_height' => $row->height,
                    'renter_weight' => $row->weight,
                    'renter_foot' => $row->footsize,
                    'package_id' => $row->rentalpackageid,
                    'rent_days' => $row->noofdays,
                    'addon' => [
                        'boots' => $row->bootsrequired == '1' ? 'on' : 'off',
                        'helmet' => $row->helmetrequired == '1' ? 'on' : 'off',
                        'insurance' => $row->insurancerequired == '1' ? 'on' : 'off',
                    ],
                    'rent_status' => 'saved',
                    'rental_id' => null,
                    'booking_id' => $row->booking_id,
                    'created_at' => $this->returnDateTimeFormat2($row->create),
                    'updated_at' => $this->returnDateTimeFormat2($row->edit),
                ];
            }

            foreach ($packages as $package) {
                $rental = [
                    'booking_id' => $package['booking_id'],
                    'package_id' => $package['package_id'],
                    'addons' => json_encode($package['addon']),
                    'duration' => $package['rent_days'],
                    'name' => $package['package_renter'],
                    'age' => $package['renter_age'],
                    'sex' => $package['renter_sex'],
                    'ability' => $package['renter_ability'],
                    'weight' => $package['renter_weight'],
                    'height' => $package['renter_height'],
                    'foot' => $package['renter_foot'],
                    'ski_length' => $this->calc->getSkiLength($package),
                    'pole_length' => $this->calc->getPoleLength($package),
                    'skier_code' => $this->calc->getSkierCode($package),
                    'boot_size' => $this->calc->getMondpoint($package),
                    'din' => $this->calc->getDIN($package),
                    'notes' => $package['renter_notes'],
                    'created_at' => $package['created_at'],
                    'updated_at' => $package['updated_at'],
                ];
                Rental::unguard();
                Rental::create($rental);
            }
        });

        Excel::load('booking.csv', function ($reader) {
            $results = $reader->all();
            foreach ($results as $row) {
                $new = [];
                $packages = Rental::where('booking_id', $row->id)->get();
                foreach ($packages as $package) {
                    $new[$package->id] = [
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
                    ];
                }
                $packages = $new;

                $arrival = $row->arrivaldate . ' ' . $row->arrivaltime;
                $departure = $row->departuredate . ' ' . $row->departuretime;
                $mountain = $row->mountaindate . ' 09:00';

                $details = [
                    'chalet_id' => $row->chalet_id,
                    'chalet_name' => null,
                    'chalet_address' => null,
                    'reference_number' => $row->bookingid,
                    'party_leader' => $row->name,
                    'party_email' => $row->email,
                    'party_mobile' => $row->mobile,
                    'arrival_dtp' => $this->returnDateTimeFormat2($arrival),
                    'departure_dtp' => $this->returnDateTimeFormat2($departure),
                    'mountain_dtp' => $this->returnDateTimeFormat2($mountain),
                    'notes' => $row->notes,
                    'booking_id' => $row->id,
                ];

                $invoice_id = $this->invoice->saveInvoice($details, $packages);

                $booking = new Booking;
                $booking->chalet_id = $details['chalet_id'];
                if ($details['chalet_id'] == 1) {
                    $booking->chalet_name = $details['chalet_name'];
                    $booking->chalet_address = $details['chalet_address'];
                }
                $booking->reference_number = $details['reference_number'];
                $booking->party_leader = $details['party_leader'];
                $booking->party_email = $details['party_email'];
                $booking->party_mobile = $details['party_mobile'];
                $booking->arrival_datetime = $details['arrival_dtp'];
                $booking->departure_datetime = $details['departure_dtp'];
                $booking->mountain_datetime = $details['mountain_dtp'];
                $booking->notes = $details['notes'];
                $booking->invoice_id = $invoice_id;
                $booking->created_at = $this->returnDateTimeFormat2($row->create);
                $booking->updated_at = $this->returnDateTimeFormat2($row->edit);
                $booking->save();
            }
        });

        dd('success!');
    }

    public function runImport()
    {
        Excel::load('rentals.csv', function ($reader) {
            $results = $reader->all();
            $packages = [];
            foreach ($results as $row) {
                $packages[$row->name] = [
                    'package_renter' => $row->name,
                    'renter_age' => $row->age,
                    'renter_sex' => $row->sex,
                    'renter_notes' => $row->notes,
                    'renter_ability' => $row->ability,
                    'renter_height' => $row->height,
                    'renter_weight' => $row->weight,
                    'renter_foot' => $row->foot,
                    'package_id' => $row->package_id,
                    'rent_days' => $row->duration,
                    'addon' => json_decode($row->addons, true),
                    'rent_status' => 'saved',
                    'rental_id' => null,
                    'booking_id' => $row->booking_id,
                    'created_at' => $this->returnDateTimeFormat2($row->created_at),
                    'updated_at' => $this->returnDateTimeFormat2($row->updated_at),
                ];
            }

            foreach ($packages as $package) {
                $rental = [
                    'booking_id' => $package['booking_id'],
                    'package_id' => $package['package_id'],
                    'addons' => json_encode($package['addon']),
                    'duration' => $package['rent_days'],
                    'name' => $package['package_renter'],
                    'age' => $package['renter_age'],
                    'sex' => $package['renter_sex'],
                    'ability' => $package['renter_ability'],
                    'weight' => $package['renter_weight'],
                    'height' => $package['renter_height'],
                    'foot' => $package['renter_foot'],
                    'ski_length' => $this->calc->getSkiLength($package),
                    'pole_length' => $this->calc->getPoleLength($package),
                    'skier_code' => $this->calc->getSkierCode($package),
                    'boot_size' => $this->calc->getMondpoint($package),
                    'din' => $this->calc->getDIN($package),
                    'notes' => $package['renter_notes'],
                    'created_at' => $package['created_at'],
                    'updated_at' => $package['updated_at'],
                ];
                Rental::unguard();
                Rental::create($rental);
            }
        });

        Excel::load('bookings.csv', function ($reader) {
            $results = $reader->all();
            foreach ($results as $row) {
                $new = [];
                $packages = Rental::where('booking_id', $row->id)->get();
                foreach ($packages as $package) {
                    $new[$package->id] = [
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
                    ];
                }
                $packages = $new;

                $details = [
                    'chalet_id' => $row->chalet_id,
                    'chalet_name' => $row->chalet_name,
                    'chalet_address' => $row->chalet_address,
                    'reference_number' => $row->reference_number,
                    'party_leader' => $row->party_leader,
                    'party_email' => $row->party_email,
                    'party_mobile' => $row->party_mobile,
                    'arrival_dtp' => $this->returnDateTimeFormat2($row->arrival_datetime),
                    'departure_dtp' => $this->returnDateTimeFormat2($row->departure_datetime),
                    'mountain_dtp' => $this->returnDateTimeFormat2($row->mountain_datetime),
                    'notes' => null,
                    'booking_id' => $row->id,
                ];

                $invoice_id = $this->invoice->saveInvoice($details, $packages);

                $booking = new Booking;
                $booking->chalet_id = $details['chalet_id'];
                if ($details['chalet_id'] == 1) {
                    $booking->chalet_name = $details['chalet_name'];
                    $booking->chalet_address = $details['chalet_address'];
                }
                $booking->reference_number = $details['reference_number'];
                $booking->party_leader = $details['party_leader'];
                $booking->party_email = $details['party_email'];
                $booking->party_mobile = $details['party_mobile'];
                $booking->arrival_datetime = $details['arrival_dtp'];
                $booking->departure_datetime = $details['departure_dtp'];
                $booking->mountain_datetime = $details['mountain_dtp'];
                $booking->notes = $details['notes'];
                $booking->invoice_id = $invoice_id;
                $booking->created_at = $this->returnDateTimeFormat2($row->created_at);
                $booking->updated_at = $this->returnDateTimeFormat2($row->updated_at);
                $booking->save();
            }
        });

        dd('success!');
    }
}
