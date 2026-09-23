<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'business_type' => ['required', 'string', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'goal' => ['required', 'string', 'max:2000'],
            'details' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = config('mail.contact_recipient', 'ronalyn.tolosa24@gmail.com');
        $subject = 'New portfolio inquiry from ' . $validated['name'];
        $body = implode("\n", [
            'New project inquiry from the portfolio website',
            '',
            'Name: ' . $validated['name'],
            'Email: ' . $validated['email'],
            'Business / Brand: ' . $validated['business_type'],
            'Service Needed: ' . $validated['service'],
            'Main Goal: ' . $validated['goal'],
            '',
            'Project Details:',
            $validated['details'],
        ]);

        Mail::raw($body, function ($message) use ($recipient, $subject, $validated) {
            $message
                ->to($recipient)
                ->replyTo($validated['email'], $validated['name'])
                ->subject($subject);
        });

        return redirect()->to('/#contact')->with('contact_success', 'Thanks! Your inquiry has been sent. I’ll get back to you soon.');
    }
}
