<?php

namespace Eventsaaspro\Http\Controllers\Auth;
use Facades\Eventsaaspro\EventSaaSPro;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         // language change
        $this->middleware('common');

        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return EventSaaSPro::view('eventsaaspro::auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        try {
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $th) {}

        return back()->with('status', __('eventsaaspro-pro::em.reset_email_info'));
    }



    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
        //users is table name of users
        return Password::broker('users');
    }

}
