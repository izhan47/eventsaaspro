<?php

namespace Eventsaaspro\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Eventsaaspro\Models\User;
use Eventsaaspro\Notifications\MailNotification;
use Facades\Eventsaaspro\EventSaaSPro;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
        $this->redirectTo = \URL::previous();
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        \Session::put('url.intended', \URL::previous());

        return EventSaaSPro::view('eventsaaspro::auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();
        try {
            $user = $this->create($request->all());
            event(new Registered($user));
            $this->guard()->login($user);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return $request->wantsJson()
            ? new JsonResponse($error, 404)
            : redirect()->intended($this->redirectPath())->with($error);
        }
        return $request->wantsJson()
        ? new JsonResponse(\Auth::user(), 201)
        : redirect()->intended($this->redirectPath());
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'accept' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2,
        ]);

        // Send welcome email
        if (!empty($user->id)) {
            // ====================== Notification ======================
            $mail['mail_subject'] = __('eventsaaspro-pro::em.register_success');
            $mail['mail_message'] = __('eventsaaspro-pro::em.get_tickets');
            $mail['action_title'] = __('eventsaaspro-pro::em.login');
            $mail['action_url'] = eventmie_url();
            $mail['n_type'] = "user";

            // notification for
            $notification_ids = [
                1, // admin
                $user->id, // new registered user
            ];

            $users = User::whereIn('id', $notification_ids)->get();
            if (checkMailCreds()) {
                try {
                    \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail));
                } catch (\Throwable $th) {

                }
            }
            // ====================== Notification ======================
        }

        $this->redirectTo = \Session::get('url.intended');

        return $user;
    }

}
