<?php

namespace Eventsaaspro\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Eventsaaspro\Models\User;
use Facades\Eventsaaspro\EventSaaSPro;
use Illuminate\Support\Facades\Storage;

use Eventsaaspro\Notifications\MailNotification;

use Intervention\Image\Facades\Image;
use File;
Use Illuminate\Support\Carbon;

class ProfileController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // language change
        $this->middleware('common');

        $this->middleware('auth');
    }

    /**
     * index
     *
     * @param  String $view
     * @param  Array $extra
     * @return view
     */
    public function index($view = 'eventsaaspro::profile.profile', $extra = [])
    {
        $user  = $this->getAuthUser();

        return EventSaaSPro::view($view, compact('user', 'extra'));
    }


    /**
     * getAuthUser
     *
     * @return \App\Model\User $user
     */
    public function getAuthUser ()
    {
        return Auth::user();
    }


    /**
     * updateAuthUser
     *
     * @param   Illuminate\Http\Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function updateAuthUser (Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }

        $this->validate($request, [
            'name' => 'required|string',

            'email' => 'required|email|unique:users,email,'.Auth::id()
        ]);

        $user = User::find(Auth::id());

        $user->name                  = $request->name;
        // $user->username              = $request->username;
        $user->email                 = $request->email;
        $user->address               = $request->address;
        $user->phone                 = $request->phone;

        $this->uploadImage($request, $user);

        $user->save();

        // redirect no matter what so that it never turns back
        $msg = __('eventsaaspro-pro::em.saved').' '.__('eventsaaspro-pro::em.successfully');
        return success_redirect($msg, route('eventsaaspro.profile'));

    }


    /**
     * updateSecurity
     *
     * @param   Illuminate\Http\Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function updateSecurity (Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }

        if(!empty($request->current))
        {
            $data = $this->updateAuthUserPassword($request);
            if($data['status'] == false)
            {
                return error_redirect($data['errors']);
            }
        }

         // redirect no matter what so that it never turns back
        $msg = __('eventsaaspro-pro::em.saved').' '.__('eventsaaspro-pro::em.successfully');
        return success_redirect($msg, route('eventsaaspro.profile').'#/userSecurity');
    }


    /**
     * updateBank
     *
     * @param   Illuminate\Http\Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function updateBank(Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }

        $user = User::find(Auth::id());


        //CUSOTM
        $this->updatebankDetails($request, $user);

        $user->save();

        // redirect no matter what so that it never turns back
        $msg = __('eventsaaspro-pro::em.saved').' '.__('eventsaaspro-pro::em.successfully');
        return success_redirect($msg, route('eventsaaspro.profile').'#/userBankDetails');

    }


    /**
     * updateOrganiser
     *
     * @param   Illuminate\Http\Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function updateOrganiser(Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }

        $user = User::find(Auth::id());
        if(Auth::user()->hasRole('organiser')) {
            $user->organisation          = $request->organisation;
        }


        $user->save();

        // redirect no matter what so that it never turns back
        $msg = __('eventsaaspro-pro::em.saved').' '.__('eventsaaspro-pro::em.successfully');
        return success_redirect($msg, route('eventsaaspro.profile').'#/userOrganiserInfo');

    }


    /**
     * updatebankDetails
     *
     * @param   Illuminate\Http\Request $request
     * @param  App\Model\User $user
     * @return void
     */
    public function updatebankDetails(Request $request, $user = [])
    {
        $user->bank_name           = $request->bank_name;
        $user->bank_code           = $request->bank_code;
        $user->bank_branch_name    = $request->bank_branch_name;
        $user->bank_branch_code    = $request->bank_branch_code;
        $user->bank_account_name   = $request->bank_account_name;
        $user->bank_account_number = $request->bank_account_number;
        $user->bank_account_phone  = $request->bank_account_phone;
    }

    /**
     * updateAuthUserRole
     *
     * @param  Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAuthUserRole(Request $request)
    {
        $this->validate($request, [
            'organisation'  => 'required',
        ]);

        $manually_approve_organizer = (int)setting('multi-vendor.manually_approve_organizer');


        $user = User::find(Auth::id());

        // manually aporove organizer setting on then don't change role
        if(empty($manually_approve_organizer))
        {

            $user->role_id      = 3;

        }

        $user->organisation = $request->organisation;

        $user->save();

        // ====================== Notification ======================
        // Manual Organizer approval email
        $msg[]                  = __('eventsaaspro-pro::em.name').' - '.$user->name;
        $msg[]                  = __('eventsaaspro-pro::em.email').' - '.$user->email;
        $extra_lines            = $msg;

        $mail['mail_subject']   = __('eventsaaspro-pro::em.requested_to_become_organiser');
        $mail['mail_message']   = __('eventsaaspro-pro::em.become_organiser_notification');
        $mail['action_title']   = __('eventsaaspro-pro::em.view').' '.__('eventsaaspro-pro::em.profile');
        $mail['action_url']     = route('eventsaaspro.profile');
        $mail['n_type']         = "Approve-Organizer";
        if(empty($manually_approve_organizer))
        {
            // Became Organizer successfully email
            $msg[]                  = __('eventsaaspro-pro::em.name').' - '.$user->name;
            $msg[]                  = __('eventsaaspro-pro::em.email').' - '.$user->email;
            $extra_lines            = $msg;

            $mail['mail_subject']   = __('eventsaaspro-pro::em.became_organiser_successful');
            $mail['mail_message']   = __('eventsaaspro-pro::em.became_organiser_successful_msg');
            $mail['action_title']   = __('eventsaaspro-pro::em.view').' '.__('eventsaaspro-pro::em.profile');
            $mail['action_url']     = route('eventsaaspro.profile');
            $mail['n_type']         = "Approved-Organizer";
        }


        // notification for
        $notification_ids       = [
            1, // admin
            $user->id, // logged in user by
        ];

        $users = User::whereIn('id', $notification_ids)->get();
        try {
            \Notification::locale(\App::getLocale())->send($users, new MailNotification($mail, $extra_lines));
        } catch (\Throwable $th) {}
        // ====================== Notification ======================


        return redirect()->route('eventsaaspro.profile');
    }

    /**
     * updateAuthUserPassword
     *
     * @param  Illuminate\Http\Request $request
     * @return boolean
     */
    public function updateAuthUserPassword(Request $request)
    {
        // demo mode restrictions
        if(config('voyager.demo_mode'))
        {
            return error_redirect('Demo mode');
        }
        $this->validate($request, [
            'current' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current, $user->password)) {
            return ['errors' => __('eventsaaspro-pro::em.current_password_not_match') , 'status' => false];
        }


        $user->password = Hash::make($request->password);
        $user->save();

        return ['status' => true];
    }


    /**
     * uploadImage
     *
     * @param   Illuminate\Http\Request $request
     * @param  App\Model\User $user
     * @return void
     */
    protected function uploadImage(Request $request, User $user)
    {
        $path   = 'users/';

        // for image
        if($request->hasfile('avatar'))
        {
            $request->validate([
                'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

            ]);

            $file = $request->file('avatar');

            $extension       = $file->getClientOriginalExtension(); // getting image extension
            $avatar          = time().rand(1,988).'.'.'webp';

            $image_resize    = Image::make($file)->encode('webp', 90)->resize(512, 512);

            $s3Path = $path . $avatar;
            Storage::disk('s3')->put($s3Path, $image_resize->stream()->__toString(), 'public');
            $user->avatar    = Storage::disk('s3')->url($s3Path);

        }

        if(empty($user->avatar) || $user->avatar == 'users/default.png')
        {
            $request->validate([
                'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

            ]);
        }


    }

}
