<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Addon;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Rental;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->calc = new CalculationController;
        $this->array = new ArrayController;

        $this->bookingModel = new Booking;
        $this->packageModel = new Package;
        $this->addonModel = new Addon;
        $this->rentalModel = new Rental;
        $this->accommodationModel = new Accommodation;
    }

    public function saveInvoice($id)
    {
        $details = $this->calc->getDetails($id);
        $packages = $this->calc->getPackages($id);
        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $prices = [];
        if ($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }

        $chalet_discount = 0;
        $discount = 0;

        $rental_prices = [];
        foreach ($packages as $key => $package) {
            if ($chalet['discount'] != '' or $chalet['discount'] != 0) {
                $rental_prices[$package['rental_id']] = [
                    'price' => number_format($prices[$key]['originalAmount'], 2, '.', ','),
                    'discount' => number_format($prices[$key]['discountAmount'], 2, '.', ','),
                    'total' => number_format($prices[$key]['discounted'], 2, '.', ','),
                ];
                $chalet_discount = $chalet['discount'];
                $discount = $prices['total'] - $prices['totalDiscounted'];
            } else {
                $rental_prices[$package['rental_id']] = [
                    'price' => number_format($prices[$key]['originalAmount'], 2, '.', ','),
                    'discount' => '0',
                    'total' => number_format($prices[$key]['originalAmount'], 2, '.', ','),
                ];
                $chalet_discount = 0;
                $discount = 0;
            }
        }

        $invoice = new Invoice;
        $invoice->chalet_discount = $chalet_discount;
        $invoice->rental_prices = json_encode($rental_prices);
        $invoice->subtotal = number_format($prices['total'], 2, '.', ',');
        $invoice->discount = number_format($discount, 2, '.', ',');
        $invoice->total = number_format($prices['total'] - $discount, 2, '.', ',');
        $invoice->save();

        return $invoice->id;
    }

    public function updateInvoice($id)
    {
        $booking = Booking::find($id);

        $details = $this->calc->getDetails($id);
        $packages = $this->calc->getPackages($id);
        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $prices = [];
        if ($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }

        $chalet_discount = 0;
        $discount = 0;

        $rental_prices = [];
        foreach ($packages as $key => $package) {
            if ($chalet['discount'] != '' or $chalet['discount'] != 0) {
                $rental_prices[$package['rental_id']] = [
                    'price' => number_format($prices[$key]['originalAmount'], 2, '.', ','),
                    'discount' => number_format($prices[$key]['discountAmount'], 2, '.', ','),
                    'total' => number_format($prices[$key]['discounted'], 2, '.', ','),
                ];
                $chalet_discount = $chalet['discount'];
                $discount = $prices['total'] - $prices['totalDiscounted'];
            } else {
                $rental_prices[$package['rental_id']] = [
                    'price' => number_format($prices[$key]['originalAmount'], 2, '.', ','),
                    'discount' => '0',
                    'total' => number_format($prices[$key]['originalAmount'], 2, '.', ','),
                ];
                $chalet_discount = 0;
                $discount = 0;
            }
        }

        $invoice = Invoice::find($booking->invoice_id);
        $invoice->chalet_discount = $chalet_discount;
        $invoice->rental_prices = json_encode($rental_prices);
        $invoice->subtotal = number_format($prices['total'], 2, '.', ',');
        $invoice->discount = number_format($discount, 2, '.', ',');
        $invoice->total = number_format($prices['total'] - $discount, 2, '.', ',');
        $invoice->save();
    }

    public function getInvoice($id)
    {
        $invoice = Invoice::find($id);

        $invoice->rental_prices = json_decode($invoice->rental_prices, true);

        return $invoice;
    }

    public function updateInvoiceAdmin($id, $data)
    {
        $booking = Booking::find($id);
        $packages = $this->calc->getPackages($id);

        $rental_prices = [];
        foreach ($packages as $key => $package) {
            $rental_prices[$key] = [
                'price' => number_format($data->rental_price[$key], 2, '.', ','),
                'discount' => number_format($data->rental_discount[$key], 2, '.', ','),
                'total' => number_format($data->rental_total[$key], 2, '.', ','),
            ];
        }

        $invoice = Invoice::find($booking->invoice_id);
        $invoice->rental_prices = json_encode($rental_prices);
        $invoice->subtotal = number_format($data->subtotal, 2, '.', ',');
        $invoice->discount = number_format($data->discount, 2, '.', ',');
        $invoice->total = number_format($data->total, 2, '.', ',');
        $invoice->save();
    }

    public function updateInvoiceRental($id, $rental_id)
    {
        $booking = Booking::find($id);
        $invoice = Invoice::find($booking->invoice_id);
        $rental_prices = json_decode($invoice->rental_prices, true);

        $details = $this->calc->getDetails($id);
        $packages = $this->calc->getPackages($id);
        $days = $this->calc->getDays($details);
        $chalet = $this->calc->getChalet($details);

        $prices = [];
        if ($packages) {
            $prices = $this->calc->getPricing($packages, $chalet['discount']);
        }

        $chalet_discount = 0;
        $discount = 0;

        if ($chalet['discount'] != '' or $chalet['discount'] != 0) {
            $rental_prices[$rental_id] = [
                'price' => number_format($prices[$rental_id]['originalAmount'], 2, '.', ','),
                'discount' => number_format($prices[$rental_id]['discountAmount'], 2, '.', ','),
                'total' => number_format($prices[$rental_id]['discounted'], 2, '.', ','),
            ];
            $chalet_discount = $chalet['discount'];
            $discount = $prices['total'] - $prices['totalDiscounted'];
        } else {
            $rental_prices[$rental_id] = [
                'price' => number_format($prices[$rental_id]['originalAmount'], 2, '.', ','),
                'discount' => '0',
                'total' => number_format($prices[$rental_id]['originalAmount'], 2, '.', ','),
            ];
            $chalet_discount = 0;
            $discount = 0;
        }

        $total = 0;
        $less = 0;

        foreach ($rental_prices as $prices) {
            $total += $prices['price'];
            $less += $prices['discount'];
        }

        $invoice->rental_prices = json_encode($rental_prices);
        $invoice->subtotal = number_format($total, 2, '.', ',');
        $invoice->discount = number_format($less, 2, '.', ',');
        $invoice->total = number_format($total - $less, 2, '.', ',');
        $invoice->save();

        //$this->updateInvoice($booking->invoice_id, $details, $packages);
    }

    public function removeFromInvoice($id, $rental_id)
    {
        $booking = Booking::find($id);
        $invoice = Invoice::find($booking->invoice_id);
        $rental_prices = json_decode($invoice->rental_prices, true);

        foreach ($rental_prices as $key => $prices) {
            if ($key == $rental_id) {
                unset($rental_prices[$rental_id]);
            }
        }

        $total = 0;
        $less = 0;

        foreach ($rental_prices as $prices) {
            $total += $prices['price'];
            $less += $prices['discount'];
        }

        $invoice->rental_prices = json_encode($rental_prices);
        $invoice->subtotal = number_format($total, 2, '.', ',');
        $invoice->discount = number_format($less, 2, '.', ',');
        $invoice->total = number_format($total - $less, 2, '.', ',');
        $invoice->save();
    }
}
