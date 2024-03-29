<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ErrorController extends Controller
{
    public function __construct()
    {
        $this->email = new EmailController;
    }

    public function getSessionExpired(Request $request): View
    {
        $data = 'This is an error email from the Skihire2u Booking System';

        //$this->email->sendErrorMail($data);

        return view('errors.expired');
    }
}
