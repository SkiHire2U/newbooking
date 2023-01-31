<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;

class EmailController extends Controller
{
    public function sendMail($data) {

        Mail::queue('emails.' . $data['type'], $data, function($message) use ($data) {
            $message->from('info@skihire2u.com', 'SkiHire2U');
            $message->to($data['email']);
            // $message->to('paul.sepe@webee.com.mt');
            // $message->bcc('paul.sepe@webee.com.mt');
            $message->subject($data['subject']);
        });
    }

    public function sendAdminMail($data) {

        Mail::queue('emails.' . $data['type'], $data, function($message) use ($data) {
            $message->from('info@skihire2u.com', 'SkiHire2U Booking System');
            $message->to('info@skihire2u.com');
            // $message->to('paul.sepe@webee.com.mt');
            // $message->bcc('paul.sepe@webee.com.mt');
            // $message->bcc('noreply.newskihire2u@gmail.com');
            $message->subject($data['subject']);
        });
    }

    public function sendErrorMail($data) {
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
