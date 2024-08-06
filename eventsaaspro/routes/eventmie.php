<?php

use Eventsaaspro\Http\Controllers\MyVenueController;
use Eventsaaspro\Http\Controllers\VenueController;
use Eventsaaspro\Middleware\Authenticate;
use Illuminate\Support\Facades\Schema;

/*
|
| Package Routes
|
 */

/* EventSaaSPro-pro package namespace */
$namespace = !empty(config('eventsaaspro.controllers.namespace')) ? '\\' . config('eventsaaspro.controllers.namespace') : '\Eventsaaspro\Http\Controllers';

/* Localization */
Route::get('/assets/js/eventmie_lang', function () {
    // user lang
    if (session('my_lang')) {
        \App::setLocale(session('my_lang'));
    }

    $strings['em'] = \Lang::get('eventsaaspro-pro::em');

    header('Content-Type: text/javascript; charset=UTF-8');
    echo ('window.i18n = ' . json_encode($strings) . ';');

    exit();
})->name('eventsaaspro.eventmie_lang');

/* set local timezone */
Route::post('/set/local_timezone', function (\Illuminate\Http\Request $request) {

    if (Schema::hasTable('settings')) {
        // if timezone_conversion off then set server timezone into client timezone else set client timezone
        if (empty(setting('regional.timezone_conversion'))) {
            session(['local_timezone' => setting('regional.timezone_default')]);
        } else {
            session(['local_timezone' => $request->local_timezone]);
        }

    }

    return response()->json(['success' => 'success'], 200);

})->name('eventsaaspro.local_timezone');

// Lang selector
Route::get('/lang/{lang?}', $namespace . '\EventmieController@change_lang')->name('eventsaaspro.change_lang');

// Package Asset
Route::get('frontend-assets', $namespace . '\EventmieController@assets')->name('eventsaaspro.eventmie_assets');

/* Auth */
Auth::routes();

// Login
Route::get('login', $namespace . '\Auth\LoginController@showLoginForm')->name('eventsaaspro.login');
Route::post('login', $namespace . '\Auth\LoginController@login')->name('eventsaaspro.login_post');
Route::post('guest-login', $namespace . '\Auth\LoginController@loginBook')->name('eventsaaspro.guest_login_post');

// Logout
Route::match(['get', 'post'], '/logout', $namespace . '\EventmieController@logout')->name('eventsaaspro.logout');

// Registration
Route::get('register', $namespace . '\Auth\RegisterController@showRegistrationForm')->name('eventsaaspro.register_show');
Route::post('register', $namespace . '\Auth\RegisterController@register')->name('eventsaaspro.register')->middleware(Spatie\Honeypot\ProtectAgainstSpam::class);

// Forgot password
Route::get('password/reset', $namespace . '\Auth\ForgotPasswordController@showLinkRequestForm')->name('eventsaaspro.password.request');
Route::post('password/email', $namespace . '\Auth\ForgotPasswordController@sendResetLinkEmail')->name('eventsaaspro.password.email');
Route::get('forgot/password/reset/{token}', $namespace . '\Auth\ResetPasswordController@showResetForm')->name('eventsaaspro.password.reset');
Route::post('forgot/password/reset/post', $namespace . '\Auth\ResetPasswordController@reset')->name('eventsaaspro.password.reset_post');

// Email Verify
Route::get('email/verify', $namespace . '\Auth\VerificationController@show')->name('verification.notice');
Route::middleware([Authenticate::class])->get('email/verify/{id}', $namespace . '\Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', $namespace . '\Auth\VerificationController@resend')->name('verification.resend');

/* Voyager */
Route::group([
    'namespace' => $namespace . '\Voyager',
    'prefix' => config('eventsaaspro.route.prefix') . '/' . config('eventsaaspro.route.admin_prefix'),
], function () use ($namespace) {
    $controller = $namespace . '\Voyager\DashboardController';

    \Voyager::routes();

    /* Override Voyager Default Routes */
    Route::get('/', "$controller@index")->name('voyager.dashboard');
    Route::post('sales/report', "$controller@sales_report")->name('voyager.sales_report');
    Route::post('export/sales/report', "$controller@export_sales_report")->name('voyager.export_sales_report');
    Route::post('event/total/sales_price', "$controller@EventTotalBySalesPrice")->name('voyager.event_total_by_sales_price');
    Route::post('get/event', "$controller@getEvent")->name('voyager.get_event');

    // Override menus route
    // Route::get('/menus', function() {
    //     return redirect()
    //     ->route("voyager.dashboard")
    //     ->send();
    // })->name('voyager.menus.index');

});

/* EventSaaSPro-pro package routes */
Route::group([
    'prefix' => config('eventsaaspro.route.prefix'),
    'as' => 'eventsaaspro.',
    'middleware' => [(Schema::hasTable('settings') ? !empty(setting('multi-vendor.verify_email')) : false) ? 'everified' : 'common'],
], function () use ($namespace) {

    /* Welcome */
    Route::get('/', $namespace . "\WelcomeController@index")->name('welcome');

    Route::get('/home', function () {
        return redirect()->route('eventsaaspro.welcome');
    });

    /* Organiser Dashboard */
    Route::prefix('/dashboard')->group(function () use ($namespace) {
        $controller = $namespace . '\ODashboardController';

        Route::get('/', "$controller@index")->name('o_dashboard');
        Route::get('/organiser_monthly_revenue', "$controller@organizer_booking_revenue")->name('monthly_revenue');
    });

    /* Tags */
    Route::prefix('/dashboard/mytags')->group(function () use ($namespace) {
        $controller = $namespace . '\TagsController';

        Route::get('/', "$controller@form")->name('tags_form');

        // API
        Route::post('/api', "$controller@tags")->name('tags');
        Route::post('/api/add', "$controller@store")->name('tags_store');
        Route::post('/api/delete', "$controller@delete")->name('tags_delete');
        Route::post('/api/selected/tags', "$controller@selected_event_tags")->name('selected_tags');
        Route::post('/api/search/tags', "$controller@search_tags")->name('search_tags');
    });

    Route::resources([
        'dashboard/myvenues' => MyVenueController::class,
        'venues' => VenueController::class,
    ]);

    Route::prefix('/venues')->group(function () use ($namespace) {
        $controller = VenueController::class;

        Route::post('/request_quote', [VenueController::class, 'request_quote'])->name('request_quote');
        Route::post('/api/search/venues', "$controller@search_venues")->name('search_venues_all');
    });

    Route::prefix('/dashboard/myvenues')->group(function () use ($namespace) {
        $controller = MyVenueController::class;

        // API
        Route::post('/delete_venueimage/{venue}', [MyVenueController::class, 'delete_venueimage'])->name('delete_venueimage');
        Route::post('/api/selected/venues', "$controller@selected_event_venues")->name('selected_venues');
        Route::post('/api/search/venues', "$controller@search_venues")->name('search_venues');

    });
    /* Tickets */
    Route::prefix('/comedians')->group(function () use ($namespace) {
        $controller = $namespace . '\ComedianController';
        // API
        Route::get('/api/comedians/{perPage?}', "$controller@index")->name('comedians');
        Route::post('/api/create', "$controller@createComedian")->name('comedian_create');
        Route::post('/api/attach', "$controller@attachComedian")->name('comedian_attach');
        Route::post('/api/de-attach', "$controller@deAttachComedian")->name('comedian_de_attach');
        Route::get('/api/event-comedian/{event_id}', "$controller@getEventComedians")->name('event_comedians');

    });
    /* My Events (organiser) */
    Route::prefix('/dashboard/comedians-payout')->group(function () use ($namespace) {
        $controller = $namespace . '\ComedianController';

        Route::get('/', "$controller@getEventsComediansPayoutsPage")->name('comedians_payout_index');
        Route::post('/api/update-payment-status/{status}', "$controller@updatePaymentStatus")->name('update_payment_status');
        Route::get('/api/get-comedian-payout/{perPage?}', "$controller@getEventComedianPayoutList")->name('get_comedian_payout');
        Route::get('/api/get-comedian-payout-grid-data', "$controller@getEventComedianPayoutGridData")->name('get_comedian_payout_grid_data');
    });
    Route::prefix('/event-brite')->group(function () use ($namespace) {
        $controller = $namespace . '\EventBriteController';
        Route::any('/webhook', "$controller@webhook");

        // API
        Route::get('/api/categories/{page?}', "$controller@syncCategories")->name('categories');
        Route::get('/api/venues/{organization_id?}/{page?}', "$controller@syncVenues")->name('venues');
        Route::get('/api/events/{organization_id?}/{page?}', "$controller@syncEvents")->name('events');
        Route::get('/api/test', "$controller@test")->name('test');

    });
    /* Tickets */
    Route::prefix('/tickets')->group(function () use ($namespace) {
        $controller = $namespace . '\TicketsController';

        // API
        Route::post('/api', "$controller@tickets")->name('tickets');
        Route::get('/api/taxes', "$controller@taxes")->name('tickets_taxes');
        Route::post('/api/store', "$controller@store")->name('tickets_store');
        Route::post('/api/delete', "$controller@delete")->name('tickets_delete');

        Route::post('/api/search-event/{search}', "$controller@getEvents")->name('search_event');
        Route::post('/api/clone-ticket/{cloneEventId}/{eventId}', "$controller@cloneTickets")->name('clone_ticket');

    });

    /* Schedules */
    Route::prefix('/schedules')->group(function () use ($namespace) {
        $controller = $namespace . '\SchedulesController';

        // API
        Route::post('/api', "$controller@schedules")->name('schedules');
        Route::post('/api/event_schedule', "$controller@event_schedule")->name('event_schedule');
    });

    /* Events */
    Route::prefix('/events')->group(function () use ($namespace) {
        $controller = $namespace . '\EventsController';

        Route::get('/', "$controller@index")->name('events_index');

        // Wildcard
        Route::get('/{event}', "$controller@show")->name('events_show');
        Route::get('/{event}/tag_{tag_title}', "$controller@tag")->name('events_tags');

        // API
        Route::get('/api/get_events', "$controller@events")->name('events');
        Route::get('/api/categories', "$controller@categories")->name('myevents_categories');
        Route::get('/api/cities', "$controller@cities")->name('myevents_cities');
        Route::post('/api/check/session', "$controller@check_session")->name('check_session');
    });

    /* Clubs */
    Route::prefix('/clubs')->group(function () use ($namespace) {
        $controller = $namespace . '\ClubsController';

        Route::get('/', "$controller@index")->name('clubs_index');

    });

    /* Bookings */
    Route::prefix('/bookings')->group(function () use ($namespace) {
        $controller = $namespace . '\BookingsController';

        // Paypal Checkout
        Route::match(['get', 'post'], '/paypal/callback', "$controller@paypal_callback")->name('bookings_paypal_callback');

        // Redirect back to event
        Route::get('/login-first', "$controller@login_first")->name('login_first');
        Route::get('/signup-first', "$controller@signup_first")->name('signup_first');

        // API
        $eventController = $namespace . '\EventsController';

        Route::post('/api/check-email', "$eventController@checkEmail")->name('check_email');
        Route::post('/api/get_tickets', "$controller@get_tickets")->name('bookings_get_tickets');
        Route::post('/api/book_tickets', "$controller@book_tickets")->name('bookings_book_tickets');
    });

    /* My Bookings (customers) */
    Route::prefix('/mybookings')->group(function () use ($namespace) {
        $controller = $namespace . '\MyBookingsController';

        Route::get('/', "$controller@index")->name('mybookings_index');

        // API
        Route::get('/api/get_mybookings', "$controller@mybookings")->name('mybookings');
        Route::post('/api/cancel', "$controller@cancel")->name('mybookings_cancel');
    });

    /* My Bookings (organizer) */
    Route::prefix('/dashboard/mybookings')->group(function () use ($namespace) {
        $controller = $namespace . '\OBookingsController';

        Route::get('/', "$controller@index")->name('obookings_index');
        Route::get('/{id}', "$controller@organiser_bookings_show")->name('obookings_organiser_bookings_show');
        Route::get('/delete/{id}', "$controller@delete_booking")->name('obookings_organiser_booking_delete');

        // API
        Route::get('/api/organiser_bookings', "$controller@organiser_bookings")->name('obookings_organiser_bookings');
        Route::post('/api/organiser_bookings_edit', "$controller@organiser_bookings_edit")->name('obookings_organiser_bookings_edit');

        Route::post('/api/booking_customers', "$controller@get_customers")->name('get_customers');
    });

    /* My Earnings (organiser) */
    Route::prefix('/dashboard/myearning')->group(function () use ($namespace) {
        $controller = $namespace . '\MyEarningsController';

        Route::get('/', "$controller@index")->name('event_earning_index');
        Route::get('/organiser/earning', "$controller@organiser_event_earning")->name('organiser_event_earning');
        Route::get('/organiser/total/earning', "$controller@organiser_total_earning")->name('organiser_total_earning');
    });

    /* My Events (organiser) */
    Route::prefix('/dashboard/myevents')->group(function () use ($namespace) {
        $controller = $namespace . '\MyEventsController';

        Route::get('/', "$controller@index")->name('myevents_index');
        Route::get('/manage/{slug?}', "$controller@form")->name('myevents_form');
        Route::get('/delete/{slug}', "$controller@delete_event")->name('delete_event');
        Route::get('/export_attendees/{slug}', "$controller@export_attendees")->name('export_attendees');

        // API
        Route::get('/api/complete_event/{event_id}/{status}', "$controller@completeEvent")->name('event_earning_complete');
        Route::get('/api/get_myevents', "$controller@get_myevents")->name('myevents');
        Route::get('/api/get_all_myevents', "$controller@get_all_myevents")->name('all_myevents');
        Route::post('/api/store', "$controller@store")->name('myevents_store');
        Route::post('/api/store_media', "$controller@store_media")->name('myevents_store_media');
        Route::post('/api/store_location', "$controller@store_location")->name('myevents_store_location');
        Route::post('/api/store_timing', "$controller@store_timing")->name('myevents_store_timing');
        Route::post('/api/store_event_tags', "$controller@store_event_tags")->name('myevents_store_event_tags');
        Route::post('/api/store_seo', "$controller@store_seo")->name('myevents_store_seo');
        Route::get('/api/countries', "$controller@countries")->name('myevents_countries');
        Route::post('/api/get_myevent', "$controller@get_user_event")->name('get_myevent');
        Route::post('/api/publish_myevent', "$controller@event_publish")->name('publish_myevent');

        Route::get('/api/event-coupons/{event_id}/{perPage?}', $namespace ."\CouponController@index")->name('event_coupons');
        Route::post('/api/store-event-coupon', $namespace ."\CouponController@store")->name('store_event_coupon');
        Route::post('/api/update-event-coupon/{id}', $namespace ."\CouponController@updateCoupon")->name('update_event_coupon');
        Route::get('/api/update-event-coupon-status/{id}/{status}', $namespace ."\CouponController@index")->name('update_event_coupon_status');
        Route::get('/api/get-events-tickets/{id}', $namespace ."\CouponController@getTickets")->name('get_event_tickets');

        Route::post('/api/myevent_organizers', "$controller@get_organizers")->name('get_organizers');

        Route::get('/api/duplicate-event/{event_id}', "$controller@duplicateEvent")->name('duplicateEvent');

        //delete multiple images
        Route::post('delete/image', "$controller@delete_image")->name('delete_image');
    });

    /* Notification */
    Route::prefix('/notifications')->group(function () use ($namespace) {

        // read notification
        Route::get('/read/{n_type}', function ($n_type) {
            if ($n_type) {
                $id = \Auth::id();
                $user = \Eventsaaspro\Models\User::find($id);
                $user->unreadNotifications->where('n_type', $n_type)->markAsRead();
            }

            // Admin: redirect to admin-panel
            if (\Auth::user()->hasRole('admin')) {
                if ($n_type == "user") {
                    return redirect()->route('voyager.users.index');
                } else if ($n_type == "bookings" || $n_type == "cancel") {
                    return redirect()->route('voyager.bookings.index');
                } else if ($n_type == "events") {
                    return redirect()->route('voyager.events.index');
                } else {
                    return redirect()->route('voyager.dashboard');
                }

            }

            // Organizer: redirect to notification related page
            if (\Auth::user()->hasRole('organiser')) {
                // create events notification
                if ($n_type == "events") {
                    return redirect()->route('eventsaaspro.myevents_index');
                }

                // create booking notification
                if ($n_type == "bookings" || $n_type == "cancel") {
                    return redirect()->route('eventsaaspro.obookings_index');
                }

            }

            // Customer: redirect to notification related page
            if (\Auth::user()->hasRole('customer')) {
                // create events notification
                if ($n_type == "user") {
                    return redirect()->route('eventsaaspro.profile');
                }

                // create booking notification
                if ($n_type == "bookings" || $n_type == "cancel") {
                    return redirect()->route('eventsaaspro.mybookings_index');
                }

            }

            // Default: redirect to homepage
            return redirect()->route('eventsaaspro.welcome');
        })->name('notify_read');

    });

    /* Profile */
    Route::prefix('/profile')->group(function () use ($namespace) {
        $controller = $namespace . '\ProfileController';

        Route::get('/', "$controller@index")->name('profile');
        Route::post('/updateAuthUser', "$controller@updateAuthUser")->name('updateAuthUser');
        Route::post('/updateAuthUserRole', "$controller@updateAuthUserRole")->name('updateAuthUserRole');
        Route::post('/updatePasswordUser', "$controller@updateSecurity")->name('updatePasswordUser');
        Route::post('/updateBankUser', "$controller@updateBank")->name('updateBankUser');
        Route::post('/updateOrganiserUser', "$controller@updateOrganiser")->name('updateOrganiserUser');

    });

    /* Blogs */
    Route::prefix('/blogs')->group(function () use ($namespace) {
        $controller = $namespace . '\BlogsController';

        Route::get('/', "$controller@get_posts")->name('get_posts');

        // Wildcard
        Route::get('/{slug}', "$controller@view")->name('post_view');
    });

    /* Contact */
    Route::prefix('/contact')->group(function () use ($namespace) {
        $controller = $namespace . '\ContactController';

        Route::get('/', "$controller@index")->name('contact');
        Route::post('/save', "$controller@store_contact")->name('store_contact')->middleware(Spatie\Honeypot\ProtectAgainstSpam::class);
    });

    /* OAuth login */
    Route::get('/login/{social}', $namespace . '\Auth\LoginController@socialLogin')->where('social', 'facebook|google')->name('oauth_login');
    Route::get('/login/{social}/callback', $namespace . '\Auth\LoginController@handleProviderCallback')->where('social', 'facebook|google')->name('oauth_callback');

    /* Download Ticket */
    Route::prefix('/download')->group(function () use ($namespace) {
        $controller = $namespace . '\DownloadsController';

        Route::get('/ticket/{id}/{order_number}', "$controller@index")->name('downloads_index');
    });

    /* Commission */
    Route::post('/commission/update', $namespace . '\Voyager\CommissionsController@commission_update')->name('commission_update');
    Route::post('/commission/settlement_update', $namespace . '\Voyager\CommissionsController@settlement_update')->name('settlement_update');

    /* QrCode Scanner */
    Route::prefix('/dashboard/ticket-scan')->group(function () use ($namespace) {
        $controller = $namespace . '\TicketScanController';

        Route::get('/', "$controller@index")->name('ticket_scan');
        Route::post('/verify-ticket', "$controller@verify_ticket")->name('verify_ticket');
        Route::post('/get-booking', "$controller@get_booking")->name('get_booking');
    });

    /* Send Email */
    Route::get('/send/email', $namespace . '\SendEmailController@send_email')->name('send_email');

    /* ============================= ALL OTHER ROUTES ABOVE ============================= */
    /* Wildcard routes (add all other routes above) */
    /* Static Pages */
    Route::get('pages/{page}', $namespace . "\PagesController@view")->name('page');
    /* ============================= NO ROUTES BELOW THIS ============================= */

    /* Checkout */
    Route::prefix('/checkout')->group(function () use ($namespace) {
        $controller = $namespace . '\CheckoutController';

        Route::get('/', "$controller@index")->name('checkout_index');

    });

    Route::prefix('/additional-information')->group(function () use ($namespace) {
        $controller = $namespace . '\AdditionalInformationController';
        Route::get('/', "$controller@index")->name('additional_information');

        Route::get('/api/{organizer_id}', "$controller@getData")->name('get_additional_information');
        Route::post('/api/add-new-additional-information', "$controller@store")->name('add_additional_information');
        Route::post('/api/update-additional-information', "$controller@update")->name('update_additional_information');
    });
});
