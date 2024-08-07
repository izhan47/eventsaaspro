<?php

namespace Eventsaaspro\Http\Controllers\Auth;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Eventsaaspro\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Facades\Eventsaaspro\EventSaaSPro;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Eventsaaspro\Notifications\MailNotification;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->redirectTo = \URL::previous();
    }

    /**
     *  Handle Social login request
     *
     * @return response
    */
    public function socialLogin($social)
    {
        return Socialite::driver($social)->redirect();
    }

    /**
    *  Obtain the user information from Social Logged in.
    *  @param $social
    *  @return Response
    */
    public function handleProviderCallback($social)
    {
        try{
            $userSocial = Socialite::driver($social)->user();
        }
        catch(\Throwable $th){
            return $this->loginRedirect();
        }

        // email is required
        if(empty($userSocial->getEmail()))
            return error_redirect([__('eventsaaspro-pro::em.email').' '.__('eventsaaspro-pro::em.required'), __('eventsaaspro-pro::em.no_email_attached').ucfirst($social)]);

        $user = User::where(['email' => $userSocial->getEmail()])->first();

        // if user with same email already exist then login
        if($user)
        {
            \Auth::login($user);

            return $this->loginRedirect();
        }
        else
        {
            // else register the user first then login
            if(!empty($userSocial->getName()))
                $name   = $userSocial->getName();
            else
                $name   = ucfirst(strstr($userSocial->getEmail(), '@', true));

            $new_user = User::create([
                'name' => $name,
                'email' => $userSocial->getEmail(),
                'password' => Hash::make(rand(1,988)), // random password
                'role_id'  => 2,
                'email_verified_at'  => Carbon::now(), // default email verify true in oauth
            ]);

            $user = User::where(['email' => $userSocial->getEmail()])->first();

            \Auth::login($user);

            // Send welcome email
            if(!empty($new_user->email))
            {
                // ====================== Notification ======================
                $mail['mail_subject']   = __('eventsaaspro-pro::em.register_success');
                $mail['mail_message']   = __('eventsaaspro-pro::em.get_tickets');
                $mail['action_title']   = __('eventsaaspro-pro::em.login');
                $mail['action_url']     = eventmie_url();
                $mail['n_type']         = "user";

                // notification for
                $notification_ids       = [
                    1, // admin
                    $new_user->id, // new registered user
                ];

                $users = User::whereIn('id', $notification_ids)->get();
                if(checkMailCreds())
                {
                    try {
                        \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail));
                    } catch (\Throwable $th) {}
                }
                // ====================== Notification ======================
            }

            return $this->loginRedirect();
        }
    }


    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (\Auth::check()) {
            return $this->loginRedirect();
        }

        \Session::put('url.intended',\URL::previous());

        return EventSaaSPro::view('eventsaaspro::auth.login');
    }

    /**
     *  after login
     */

    // check if authenticated, then redirect to welcome page
    protected function authenticated()
    {
        if (\Auth::check()) {
            return $this->loginRedirect();
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:512',
            'password' => 'required|max:512'
        ]);

        $flag = \Auth::attempt ([
            'email' => $request->get ( 'email' ),
            'password' => $request->get ( 'password' )
        ]);
        if ($flag)
        {
            // check if user is not disabled
            if(! \Auth::user()['status'])
            {
                \Auth::logout();
                return error_redirect( __('eventsaaspro-pro::em.user_disabled'));
            }

            // dd(\Auth::user()->hasRole('organiser'));
            // dd(route('eventsaaspro.o_dashboard'));

            if (\Auth::user()->hasRole('organiser')) {
                return redirect()->route('eventsaaspro.o_dashboard');
            } else {
                return $this->loginRedirect();
            }

        }
        else
        {
            return error_redirect( __('eventsaaspro-pro::em.incorrect_email_password') );
        }
    }
    public function loginBook(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:512',
            'password' => 'required|max:512'
        ]);

        $flag = \Auth::attempt ([
            'email' => $request->get ( 'email' ),
            'password' => $request->get ( 'password' )
        ]);
        if ($flag)
        {
          return response([
            'user'=> \Auth::user(),
          ]);

        }
        else
        {
            return error_redirect( __('eventsaaspro-pro::em.incorrect_email_password') );
        }
    }
    private function loginRedirect()
    {
        $this->redirectTo = route('eventsaaspro.welcome');

        try {

            $url = \Session::get('url.intended');

            $route = collect(\Route::getRoutes())->first(function($route) use($url){
                return $route->matches(request()->create($url));
            });

            if( $route->action['as'] == 'eventsaaspro.events_show') {
                $this->redirectTo =  \Session::get('url.intended');
            }
        }catch(\Throwable $th){

        }

        // if coming from event checkout
        $redirect = !empty(config('eventsaaspro.route.prefix')) ? config('eventsaaspro.route.prefix') : '/';
        if(!empty(session('redirect_to_event')))
        {
            $redirect = session('redirect_to_event');
            // forget session
            session()->forget(['redirect_to_event']);
        }

        // // redirect to event
        return redirect()->intended($this->redirectTo);
    }



}

