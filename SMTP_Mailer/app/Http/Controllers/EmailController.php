<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\WelcomeEmail;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $toemail = "samayrajahan02@gmail.com";
        $message = "Hello bondhugon";
        $subject = "Kire Kire";
        $details = [
            'name' => 'Sanjida Akter',
            'age' => '23'
        ];

        // Sending email
        $request = Mail::to($toemail)
            // ->bcc('kazi.blackfox@gmail.com')
            ->send(new WelcomeEmail($message, $subject, $details));
        
        dd($request);
    }

    public function contactForm()
    {
        return view('mail.contact');
    }

    public function sendContactEmail(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'subject' => 'required|string|min:5|max:100',
            'message' => 'required|string|min:5|max:200',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:2048', // Optional file upload
        ]);
    
       // Handle the file upload if a file is provided
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '.' . $file->extension();
            $file->move(public_path('uploads'), $fileName);
        }
    
        // Prepare email details
        $name = $request->input('name');
        $email = $request->input('email');
        $subject = $request->input('subject');
        $message = $request->input('message');
    
        // Send email
        Mail::to($email)
            // ->bcc('kazi.blackfox@gmail.com')
            ->send(new WelcomeEmail($request->all(),$fileName));
    if($request){
        return back()->with('success', 'Your message has been sent successfully!');
    }
    else{
        return back()->with('error', 'Sorry!');
    }
    }
}
