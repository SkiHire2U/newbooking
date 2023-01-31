<?php

namespace App\Http\Controllers;

use App\Accommodation;
use App\Booking;
use App\Meta;
use App\Package;
use Carbon\Carbon;

class CalculationController extends Controller
{
    public function __construct()
    {
        $this->array = new ArrayController;

        $this->packageModel = new Package;
        $this->accommodationModel = new Accommodation;
        $this->metaModel = new Meta;
    }

    public function getSkiLength($data)
    {
        $ski = 0;
        $level = $data['renter_ability'];
        $height = $data['renter_height'];
        $weight = $data['renter_weight'];
        $age = $data['renter_age'];
        $type = '';

        if ($age > 3 && $height >= 140) {
            $type = 'adult';
        } else {
            $type = 'child';
        }

        $array = $this->array->getSkiLengthArray();

        foreach ($array[$type] as $value) {
            if ($value['min'] == 0) {
                if ($height <= $value['max']) {
                    $ski = (int) $value['ski'][$level - 1];
                    if ($weight > $value['weight']) {
                        $ski = $ski + 5;
                    }
                }
            } elseif ($value['max'] == 0) {
                if ($height >= $value['min']) {
                    $ski = (int) $value['ski'][$level - 1];
                }
            } else {
                if ($height >= $value['min'] && $height <= $value['max']) {
                    $ski = (int) $value['ski'][$level - 1];
                    if ($weight > $value['weight']) {
                        $ski = $ski + 5;
                    }
                }
            }
        }

        if ($age == 6) {
            $ski = $ski - 10;
        }

        return $ski;
    }

    public function getPoleLength($data)
    {
        $pole = 0;
        $height = $data['renter_height'] / 2.54;

        $array = $this->array->getPoleLengthArray();

        foreach ($array as $value) {
            if ($value['min'] == 0) {
                if ($height <= $value['max']) {
                    $pole = (int) $value['pole'];
                }
            } elseif ($value['max'] == 0) {
                if ($height >= $value['min']) {
                    $pole = (int) $value['pole'];
                }
            } else {
                if ($height >= $value['min'] && $height < $value['max']) {
                    $pole = (int) $value['pole'];
                }
            }
        }

        return $pole;
    }

    public function getSkierCode($data)
    {
        $code = '';
        $weight = $data['renter_weight'];

        $array = $this->array->getSkierCodeArray();

        foreach ($array as $value) {
            if ($value['min'] == 0) {
                if ($weight <= $value['max']) {
                    $code = $value['code'];
                }
            } elseif ($value['max'] == 0) {
                if ($weight >= $value['min']) {
                    $code = $value['code'];
                }
            } else {
                if ($weight >= $value['min'] && $weight <= $value['max']) {
                    $code = $value['code'];
                }
            }
        }

        return $code;
    }

    public function getMondpoint($data)
    {
        $foot = (int) $data['renter_foot'];
        $array = $this->array->getMondoArray();
        $mondo = $array[$foot - 1];

        return $mondo;
    }

    public function getBootSize($mondo)
    {
        $boot = 0;
        $array = $this->array->getBootArray();

        foreach ($array as $value) {
            if ($mondo >= $value['min'] && $mondo < $value['max']) {
                $boot = $value['boot'];
            }
        }

        return $boot;
    }

    public function getDIN($data)
    {
        $din = 0;
        $codeKey = 0;
        $foot = (int) $data['renter_foot'];
        $weight = $data['renter_weight'];
        $level = $data['renter_ability'];
        $mondoArray = $this->array->getMondoArray();
        $mondo = $mondoArray[$foot - 1];
        $boot = $this->getBootSize($mondo);
        $skierCodeArray = $this->array->getSkierCodeArray();

        foreach ($skierCodeArray as $key => $value) {
            if ($value['min'] == 0) {
                if ($weight <= $value['max']) {
                    $codeKey = $key;
                }
            } elseif ($value['max'] == 0) {
                if ($weight >= $value['min']) {
                    $codeKey = $key;
                }
            } else {
                if ($weight >= $value['min'] && $weight <= $value['max']) {
                    $codeKey = $key;
                }
            }
        }

        $codeKey = $codeKey + $level - 1;

        $dinArray = $this->array->getDinArray();

        foreach ($dinArray as $value) {
            if ($value['min'] == 0) {
                if ($boot <= $value['max']) {
                    $din = $value['din'][$codeKey];
                }
            } elseif ($value['max'] == 0) {
                if ($boot >= $value['min']) {
                    $din = $value['din'][$codeKey];
                }
            } else {
                if ($boot >= $value['min'] && $boot <= $value['max']) {
                    $din = $value['din'][$codeKey];
                }
            }
        }

        return $din;
    }

    public function getDays($data)
    {
        $mtn = Carbon::parse($data['mountain_dtp'])->toDateString();
        $dep = Carbon::parse($data['departure_dtp'])->toDateString();
        $mtn = Carbon::parse($mtn);
        $dep = Carbon::parse($dep);
        $days = $mtn->diffInDays($dep);
        $days++;
        if ($days > 15) {
            $days = 15;
        }

        return $days;
    }

    public function getChalet($data)
    {
        $chalet = [];

        if ($data['chalet_id'] == 1) {
            $chalet['name'] = $data['chalet_name'].'(Independent)';
        } else {
            $chalet['name'] = $this->accommodationModel->getAccommodationName($data['chalet_id']);
        }
        $chalet['discount'] = $this->accommodationModel->getAccommodationDiscount($data['chalet_id']);

        return $chalet;
    }

    public function getPricing($packages, $discount)
    {
        $prices['total'] = 0;
        $prices['totalDiscounted'] = 0;

        foreach ($packages as $key => $package) {
            $packageType = Package::find($package['package_id'])->type;
            $packagePrice = (float) $this->packageModel->getPackagePrice($package['package_id'], $package['rent_days'], $package['addon']['boots']);

            if ($package['addon']['helmet'] == 'on') {
                if ((int) $package['renter_age'] > 3 || $package['renter_age'] == null) {
                    $helmetPrice = (float) $this->metaModel->getAddonMeta('helmet_prices', $packageType);
                    $helmetIncrements = (float) $this->metaModel->getAddonMeta('helmet_increments', $packageType);
                    $packagePrice += (float) $helmetPrice + ($helmetIncrements * ($package['rent_days'] - 1));
                }
            }

            if ($discount) {
                $prices[$key]['discountAmount'] = $packagePrice * ((float) $discount / 100);
                $prices[$key]['discounted'] = $packagePrice - $prices[$key]['discountAmount'];
            }

            if ($package['addon']['insurance'] == 'on') {
                $insuranceIncrements = (float) $this->metaModel->getAddonMeta('insurance_increments', $packageType);
                $packagePrice += (float) $insuranceIncrements * $package['rent_days'];
                if ($discount) {
                    $prices[$key]['discounted'] += (float) $insuranceIncrements * $package['rent_days'];
                }
            }

            $prices[$key]['originalAmount'] = $packagePrice;

            $prices['total'] += $packagePrice;
            if ($discount) {
                $prices['totalDiscounted'] += $prices[$key]['discounted'];
            }
        }

        return $prices;
    }

    public function getDetails($id)
    {
        $booking = Booking::find($id);

        $details = [
            'chalet_id' => $booking->chalet_id,
            'chalet_name' => $booking->chalet_name,
            'chalet_address' => $booking->chalet_address,
            'reference_number' => $booking->reference_number,
            'party_leader' => $booking->party_leader,
            'party_email' => $booking->party_email,
            'party_mobile' => $booking->party_mobile,
            'party_notes' => $booking->notes,
            'arrival_dtp' => $booking->arrival_datetime,
            'departure_dtp' => $booking->departure_datetime,
            'mountain_dtp' => $booking->mountain_datetime,
            'booking_id' => $booking->id,
        ];

        return $details;
    }

    public function getPackages($id)
    {
        $booking = Booking::find($id);

        $new = [];
        $packages = $booking->rentals;
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

        return $packages;
    }

    public function getInvoiceValues($id)
    {
    }
}
