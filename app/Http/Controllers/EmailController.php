<?php

namespace App\Http\Controllers;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendMail($data)
    {
        Mail::queue((new Mailable())
            ->from('info@skihire2u.com', 'SkiHire2U')
            ->to($data['email'])
            ->subject($data['subject'])
            ->view('emails.' . $data['type'], $data));
    }


    public function sendAdminMail($data)
    {
        Mail::queue((new Mailable())
            ->from('info@skihire2u.com', 'SkiHire2U Booking System')
            ->to('info@skihire2u.com')
            ->subject($data['subject'])
            ->view('emails.' . $data['type'], $data));
    }

    public function sendErrorMail($data)
    {
        /*
        Mail::queue('emails.exception', ['message' => $data], function($message) {
            $message->from('info@skihire2u.com', 'SkiHire2U');
            $message->to('paul.sepe@webee.com.mt');
            // $message->bcc('paul.sepe@webee.com.mt');
            $message->subject('Error on Booking');
        });
        */
    }
}
