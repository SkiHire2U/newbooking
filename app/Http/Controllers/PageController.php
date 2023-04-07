<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Meta;
use App\Models\Operator;
use App\Models\Package;
use App\Models\Rental;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Session;

class PageController extends Controller
{
    public function __construct()
    {
        $this->calc = new CalculationController;
        $this->array = new ArrayController;

        $this->packageModel = new Package;
        $this->accommodationModel = new Accommodation;
        $this->metaModel = new Meta;
    }

    public function getIndex(): View
    {
        Session::forget('packages');
        Session::forget('reference');
        $operators = Operator::all();

        return view('pages.index')
            ->with('operators', $operators);
    }

    public function redirectIndex(): RedirectResponse
    {
        return redirect('/');
    }

    public function postDateDetails(Request $request): RedirectResponse
    {
        $this->validate($request,
            [
                'chalet_id' => 'required',
                'arrival_dtp' => 'required',
                'departure_dtp' => 'required',
                'mountain_dtp' => 'required',
            ]
        );

        $partyDetails = $request->all();
        $partyDetails['booking_id'] = null;

        Session::put('partyDetails', $partyDetails);

        //dd($request);

        return redirect()->route('equipments');
    }

    public function getEquipments()
    {
        $details = session()->get('partyDetails');

        if (! $details) {
            return redirect()->route('error.expired');
        }

        $days = $this->calc->getDays($details);

        $equips = $this->packageModel->all();
        foreach ($equips as $equip) {
            $equip->prices = json_decode($equip->prices);
        }

        $chalet = $this->calc->getChalet($details);

        $prices = [];
        $packages = session()->get('packages');
        if ($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        } else {
            $packages = [];
        }

        return view('pages.equipments')
            ->with('details', $details)
            ->with('days', $days)
            ->with('equips', $equips)
            ->with('chalet', $chalet)
            ->with('packages', $packages)
            ->with('prices', $prices)
            ->with('metaModel', $this->metaModel)
            ->with('packageModel', $this->packageModel);
    }

    public function postAddToRack(Request $request): RedirectResponse
    {
        $this->validate($request,
            [
                'package_renter' => 'required',
                'package_id' => 'required',
                'rent_days' => 'required',
            ]
        );
        $new = [];
        $package_renter = $request->package_renter;
        if (Session::has('packages')) {
            $packages = session()->get('packages');
            $i = 1;
            foreach ($packages as $name => $package) {
                $new[$name] = $package;
                if ($name == $package_renter) {
                    $pos = strpos($package_renter, '[');
                    if ($pos) {
                        $package_renter = substr($package_renter, 0, $pos - 1);
                    }
                    $package_renter = $package_renter.' ['.$i.']';
                    $i++;
                }
            }
        }
        $add = $request->all();
        $add['renter_age'] = null;
        $add['rental_id'] = null;
        $new[$package_renter] = $add;

        Session::put('packages', $new);

        Session::put('notify', 'true');

        Session::flash('success', 'Package has been successfully added to rack.');

        return redirect()->route('equipments');
    }

    public function postRemoveFromRack(Request $request, $name): RedirectResponse
    {
        Session::forget('packages.'.$name);

        Session::flash('success', $name.' has been successfully removed from rack.');

        return redirect()->route('equipments');
    }

    public function getRentals()
    {
        $session = session()->all();
        $details = session()->get('partyDetails');

        if (! $details) {
            return redirect()->route('error.expired');
        }

        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $equips = $this->packageModel->all();
        foreach ($equips as $equip) {
            $equip->prices = json_decode($equip->prices);
        }

        $prices = [];
        $button = true;
        $packages = [];

        if (session()->get('packages')) {
            $packages = session()->get('packages');
        }

        if ($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
            foreach ($packages as $package) {
                if ($package['rent_status'] != 'saved') {
                    $button = false;
                }
            }
        } else {
            return redirect()->route('equipments');
        }

        $operators = Operator::all();

        $select['age'] = $this->array->getAgeArray();
        $select['height'] = $this->array->getHeightArray();
        $select['weight'] = $this->array->getWeightArray();
        $select['foot'] = $this->array->getFootArray();
        $select['level'] = $this->array->getLevelArray();
        $select['packages'] = $this->array->getPackagesArray();

        return view('pages.rentals')
            ->with('button', $button)
            ->with('details', $details)
            ->with('days', $days)
            ->with('equips', $equips)
            ->with('chalet', $chalet)
            ->with('packages', $packages)
            ->with('prices', $prices)
            ->with('select', $select)
            ->with('operators', $operators)
            ->with('packageModel', $this->packageModel);
    }

    public function postRemoveFromRental(Request $request, $key): RedirectResponse
    {
        $package = session()->get('packages.'.$key);
        $name = $package['package_renter'];

        if ($package['rental_id'] != null) {
            $rental = Rental::findOrFail($package['rental_id']);
            $rental->delete();
        }

        Session::forget('packages.'.$key);

        Session::flash('success', $name.' has been successfully removed from rental.');

        return redirect()->route('rentals');
    }

    public function getPartyDetails()
    {
        $details = session()->get('partyDetails');

        if (! $details) {
            return redirect()->route('error.expired');
        }

        $days = $this->calc->getDays($details);

        $equips = $this->packageModel->all();
        foreach ($equips as $equip) {
            $equip->prices = json_decode($equip->prices);
        }

        $chalet = $this->calc->getChalet($details);

        $prices = [];
        $packages = [];

        if (session()->get('packages')) {
            $packages = session()->get('packages');
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }

        return view('pages.details')
            ->with('details', $details)
            ->with('days', $days)
            ->with('equips', $equips)
            ->with('chalet', $chalet)
            ->with('packages', $packages)
            ->with('prices', $prices)
            ->with('packageModel', $this->packageModel);
    }

    public function postSaveRenter(Request $request, $name): RedirectResponse
    {
        $this->validate($request,
            [
                'package_renter' => 'required',
                'renter_age' => 'required',
                'renter_sex' => 'required',
                'renter_ability' => 'required',
                'renter_height' => 'required',
                'renter_weight' => 'required',
                'renter_foot' => 'required',
                'package_id' => 'required',
                'rent_days' => 'required',
            ]
        );

        $new = [];
        $add = $request->all();
        $packages = [];

        if (session()->get('packages')) {
            $packages = session()->get('packages');

            foreach ($packages as $key => $package) {
                if ($key == $name) {
                    $add['rental_id'] = $package['rental_id'];
                    $new[$key] = $add;
                } else {
                    $new[$key] = $package;
                }
            }
        }

        Session::put('packages', $new);

        Session::flash('success', 'Package has been successfully saved.');

        Session::forget('notify');

        return redirect()->route('rentals');
    }

    public function postEditRenter(Request $request, $name): RedirectResponse
    {
        $new = [];
        $add = $request->all();
        $packages = session()->get('packages');
        foreach ($packages as $key => $package) {
            if ($key == $name) {
                $package['rent_status'] = 'edit';
                $new[$key] = $package;
            } else {
                $new[$key] = $package;
            }
        }

        Session::put('packages', $new);

        Session::put('notify', 'true');

        return redirect()->route('rentals');
    }

    public function postReference(Request $request): RedirectResponse
    {
        $this->validate($request,
            [
                'party_leader' => 'required|email',
                'reference_code' => 'required',
            ]
        );

        $bookingModel = new Booking;
        $booking = $bookingModel
            ->where('reference_number', $request->reference_code)
            ->where('party_email', $request->party_leader)
            ->first();

        if (empty($booking)) {
            Session::flash('danger', 'Incorrect email address or reference number!');

            return redirect('/')->withInput();
        }

        $partyDetails = $this->calc->getDetails($booking->id);
        $packages = $this->calc->getPackages($booking->id);

        Session::put('partyDetails', $partyDetails);

        Session::put('packages', $packages);

        Session::put('reference', $request->reference_code);

        return redirect()->route('rentals');
    }

    public function postUpdateDateDetails(Request $request, $id): RedirectResponse
    {
        $this->validate($request,
            [
                'chalet_id' => 'required',
                'arrival_dtp' => 'required',
                'departure_dtp' => 'required',
                'mountain_dtp' => 'required',
                'party_leader' => 'required',
                'party_email' => 'required',
                'party_mobile' => 'required',
            ]
        );

        $details = session()->get('partyDetails');

        $partyDetails = [
            'chalet_id' => $request->chalet_id,
            'chalet_name' => $request->chalet_name,
            'chalet_address' => $request->chalet_address,
            'reference_number' => $details['reference_number'],
            'party_leader' => $request->party_leader,
            'party_email' => $request->party_email,
            'party_mobile' => $request->party_mobile,
            'party_notes' => $request->party_notes,
            'arrival_dtp' => $request->arrival_dtp,
            'departure_dtp' => $request->departure_dtp,
            'mountain_dtp' => $request->mountain_dtp,
            'booking_id' => $details['booking_id'],
        ];
        Session::put('partyDetails', $partyDetails);

        $days = $this->calc->getDays($partyDetails);
        $packages = session()->get('packages');

        foreach ($packages as &$package) {
            if ((int) $package['rent_days'] > $days) {
                $package['rent_days'] = (string) $days;
            }
        }
        Session::put('packages', $packages);

        Session::flash('info', 'Your changes have been made but please remember to press the ‘Save and Exit’ button once you have finished to make sure all your changes are saved.');

        return redirect()->route('rentals');
    }
}
