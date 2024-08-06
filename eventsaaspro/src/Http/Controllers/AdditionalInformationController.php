<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Eventsaaspro\Models\AdditionalInformation;
use Illuminate\Http\Response;
use Facades\Eventsaaspro\EventSaaSPro;
use Illuminate\Http\Request;

class AdditionalInformationController extends Controller
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
    public function index($view = 'eventsaaspro::additional_information.index', $extra = [])
    {
        $path = false;
        if (!empty(config('eventsaaspro.route.prefix'))) {
            $path = config('eventsaaspro.route.prefix');
        }
        return EventSaaSPro::view($view, compact('path', 'extra'));
    }
    public function getData($id)
    {
        $additional = AdditionalInformation::where('organizer_id', $id)->with('organizer')->first();
        return response()->json(['additional' => $additional, 'status' => true]);
    }
    public function store(Request $request)
    {
        try {
            $check = AdditionalInformation::where('organizer_id', auth()->user()->id)->first();
            if (!empty($check)) {
                return response()->json([
                    'message' => "Sorry, you can't create a new additional information.",
                    'status' => false,
                ]);
            }
            $request->validate([
                'additional_information',
            ]);
            $additional = AdditionalInformation::create([
                'organizer_id' => auth()->user()->id,
                'additional_information' => $request->additional_information,
            ]);
            return response()->json(['additional' => $additional, 'message' => 'Additional Information save successfully', 'status' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ]);
        }
    }
    public function update(Request $request)
    {
        try {
            if (!$request->has('additional_information')) {
                return response()->json(['message' => 'The additional_information field is missing from the request.', 'status' => false]);
            }

            $additional = AdditionalInformation::where('organizer_id', auth()->user()->id)->first();
            $additional->additional_information = $request->additional_information;
            $additional->save();
            return response()->json(['additional' => $additional, 'message' => 'Additional Information update successfully', 'status' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ]);
        }

    }
}
