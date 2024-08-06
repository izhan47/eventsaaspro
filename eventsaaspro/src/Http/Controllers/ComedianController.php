<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Eventsaaspro\Models\ComedianEvent;
use Eventsaaspro\Models\Event;
use Eventsaaspro\Models\User;
use Facades\Eventsaaspro\EventSaaSPro;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ComedianController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('common');
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($perPage = 10)
    {
        $comedians = User::where('role_id', 4)->paginate($perPage);

        if ($comedians->isEmpty()) {
            return response()->json(['status' => false]);
        }

        return response()->json(['comedians' => $comedians, 'status' => true]);
    }
    public function deAttachComedian(Request $request)
    {
        $request->validate([
            'event_id' => 'required|numeric|exists:events,id',
            'user_id' => 'required|numeric|exists:users,id',
        ]);

        $eventStatus = Event::where('id', $request->event_id)->value('status');

        if ($eventStatus == 2) {
            return response()->json([
                'message' => "Sorry, you can't remove a comedian because the event has already completed.",
                'status' => false,
            ]);
        }

        ComedianEvent::where('event_id', $request->event_id)
            ->where('user_id', $request->user_id)
            ->delete();

        return response()->json(['message' => "Comedian Removed Successfully", 'status' => true]);
    }

    public function createComedian(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $data = [];
        foreach ($request->all() as $key => $value) {
            if ($key == 'password') {
                $data[$key] = Hash::make($value);
            }else {
                $data[$key] = $value;
            }
        }
        $data['role_id'] = 4;
        $comedian = User::create($data);

        return response()->json(['comedian' => $comedian, 'status' => true]);
    }


    public function attachComedian(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|numeric|exists:events,id',
                'user_id' => 'required|numeric|exists:users,id',
                'fixed' => 'required|boolean',
                'percent' => 'required|boolean',
                'fixed_value' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('fixed') == 1 && empty($value)) {
                            $fail("The $attribute field is required when 'fixed' is enabled.");
                        }
                    },
                ],
                'percent_value' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('percent') == 1 && empty($value)) {
                            $fail("The $attribute field is required when 'percent' is enabled.");
                        }
                    },
                ],
                'fixed' => 'required_without:percent',
                'percent' => 'required_without:fixed',
            ]);
            $checkEventStatus = Event::where('id', $request->event_id)->pluck('status')->first();
            if ($checkEventStatus == 2) {
                return response()->json([
                    'message' => "Sorry, you can't add a comedian because the event has already completed.",
                    'status' => false,
                ]);
            }

            $check = ComedianEvent::where('event_id', $request->event_id)->where('user_id', $request->user_id)->first();
            if ($check) {
                return response()->json([
                    'message' => "A comedian has already been added to this event.",
                    'status' => false,
                ]);
            }

            if ($request->fixed == 1) {
                $previousMax = null;
                foreach ($request->fixed_value as $key => $value) {
                    if ($previousMax !== null && $previousMax >= $value['min_person']) {
                        return response()->json(['message' => "Ranges should not overlap or fall within previous ranges.", 'field' => 'fixed_value', 'status' => false]);
                    }
                    if ($value['max_person'] !== null) {
                        $previousMax = $value['max_person'];
                    }
                }
            }
            if ($request->percent == 1) {
                $previousMax = null;
                foreach ($request->percent_value as $key => $value) {
                    if ($previousMax !== null && $previousMax >= $value['min_person']) {
                        return response()->json(['message' => "Ranges should not overlap or fall within previous ranges.", 'field' => 'fixed_value', 'status' => false]);
                    }
                    if ($value['max_person'] !== null) {
                        $previousMax = $value['max_person'];
                    }
                }
            }

            $comedianEvent = new ComedianEvent();
            $comedianEvent->event_id = $request->event_id;
            $comedianEvent->user_id = $request->user_id;
            $comedianEvent->fixed = $request->fixed;
            $comedianEvent->fixed_value = $request->fixed_value;
            $comedianEvent->percent = $request->percent;
            $comedianEvent->percent_value = $request->percent_value;
            $comedianEvent->save();
            $comedianEvent->load('user');

            $event = Event::where('id', $request->event_id)->first();
            $isPublishable = json_decode($event->is_publishable, true);
            $isPublishable['comedians'] = 1;
            $event->update(['is_publishable' => json_encode($isPublishable)]);

            return response()->json(['comedianEvent' => $comedianEvent, 'message' => "Comedian Attach Successfully", 'status' => true]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false]);
        }
    }

    public function getEventComedians($event_id)
    {
        $comedians = ComedianEvent::where('event_id', $event_id)->with('user')->get();

        if ($comedians->isEmpty()) {
            return response()->json(['status' => false]);
        }

        return response()->json(['comedians' => $comedians, 'status' => true]);
    }

    public function getEventsComediansPayoutsPage($view = 'eventsaaspro::comedians_payout.index', $extra = [])
    {
        $path = false;
        if (!empty(config('eventsaaspro.route.prefix'))) {
            $path = config('eventsaaspro.route.prefix');
        }
        return EventSaaSPro::view($view, compact('path', 'extra'));
    }
    public function getEventComedianPayoutList(Request $request, $perPage = 10)
    {
        if (!empty($request->search)) {
            $search = $request->search;
            $comedians = ComedianEvent::with('event', 'user')
                ->whereHas('event', function ($query) {
                    $query->where('status', 2);
                })
                ->where(function ($query) use ($search) {
                    $query->whereHas('event', function ($subQuery) use ($search) {
                        $subQuery->where('title', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                })
                ->paginate($perPage);
        } else {
            $comedians = ComedianEvent::with('event')
                ->whereHas('event', function ($query) {
                    $query->where('status', 2);
                })
                ->paginate($perPage);
        }
        return response()->json(['comedians' => $comedians, 'status' => true]);

    }
    public function getEventComedianPayoutGridData()
    {
        $comediansUnpaidCount = ComedianEvent::where('status', '0')->with('event')
            ->whereHas('event', function ($query) {
                $query->where('status', 2);
            })
            ->count();
        $comediansUnpaidTotal = ComedianEvent::where('status', '0')->with('event')
            ->whereHas('event', function ($query) {
                $query->where('status', 2);
            })
            ->sum('total');
        $comediansPaidTotal = ComedianEvent::where('status', '1')->with('event')
            ->whereHas('event', function ($query) {
                $query->where('status', 2);
            })
            ->sum('total');
        return response()->json([
            'comediansUnpaidTotal' => $comediansUnpaidTotal,
            'comediansUnpaidCount' => $comediansUnpaidCount,
            'comediansPaidTotal' => $comediansPaidTotal,
            'status' => true,
        ]);

    }
    public function updatePaymentStatus(Request $request, $status)
    {
        $request->validate([
            'comedian_event_ids' => 'required',
        ]);
       try {
            foreach ($request->comedian_event_ids as $key => $value) {
                $comedian = ComedianEvent::where('id', $value)->first();
                if ($comedian) {
                    $comedian->status = $status;
                    $comedian->save();
                }
            }
            return response()->json(['message' => 'Comedian Payment Status Updated', 'status' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => false]);
        }
    }
}
