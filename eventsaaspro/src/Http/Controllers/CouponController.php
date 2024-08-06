<?php

namespace Eventsaaspro\Http\Controllers;

use App\Http\Controllers\Controller;
use Eventsaaspro\Models\Coupon;
use Eventsaaspro\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('common');
        // $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($event_id, $perPage = 10)
    {
        $coupon = Coupon::where('event_id', $event_id)->paginate($perPage);
        return response()->json(['coupon' => $coupon, 'status' => true]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|numeric|exists:events,id',
                'title' => 'required',
                'amount' => 'required|numeric',
                'type' => ['required', Rule::in(['fixed', 'percent'])],
                'start_date' => 'required|date|after_or_equal:today',
                'ticket' => 'required',
            ]);
            $coupon = Coupon::create([
                'event_id' => $request->event_id,
                'title' => $request->title,
                'amount' => $request->amount,
                'type' => $request->type,
                'start_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                'expire_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                'ticket' => json_encode($request->ticket),
                'quantity' => $request->quantity,
                'status' => $request->status,
            ]);
            return response()->json(['coupon' => $coupon, 'message' => 'Coupon added successfully', 'status' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ]);
        }
    }

    public function updateCoupon($id, Request $request)
    {
        try {
            $request->validate([
                'amount' => 'numeric',
                'type' => [Rule::in(['fixed', 'percent'])],
                'start_date' => 'date|after_or_equal:today',
            ]);
            $coupon = Coupon::where('id', $id)->first();
            foreach ($request->all() as $key => $value) {
                if ($key == 'ticket') {
                    $coupon->$key = json_encode($value);
                } elseif ($key == 'id' ||$key == 'event_id' ||$key == 'created_at' ||$key == 'updated_at'  ) {
                    continue;
                }elseif ($key == 'start_date' || $key == 'expire_date' ) {
                    $coupon->$key =Carbon::parse($value)->format('Y-m-d');
                }else {
                    $coupon->$key = $value;
                }
            }
            $coupon->save();
            return response()->json(['coupon' => $coupon, 'message' => 'Coupon updated successfully', 'status' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => false,
            ]);
        }
    }

    public function updateStatus($id, $status)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $coupon->status = $status;
            $coupon->save();
            return response()->json(['message' => 'Status updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Coupon not found'], 404);
        }
    }

    public function getTickets($id)
    {
        return response()->json(['tickets' => Ticket::where('event_id', $id)->get(['title', 'id']), 'message' => 'Success', 'status' => true]);
    }
}
