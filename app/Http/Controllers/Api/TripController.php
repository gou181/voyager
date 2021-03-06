<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    // List all of the trips
    public function index()
    {
        $trips = Trip::simplePaginate(10);
        return $trips;
    }

    // Show a single trip
    public function show(Trip $trip) {
        return response()->json(['data' => $trip]);
    }

    // Store a new trip
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'from' => ['required', 'string', 'min:3', 'max:255'],
                'to' => ['required', 'string', 'min:3', 'max:255'],
                'car_model' => ['required', 'string', 'max:255'],
                'price_per_passenger' => ['required', 'integer'],
                'number_of_empty_seats' => ['required', 'integer', 'min:1'],
                'departure_date' => ['required', 'date', 'after:today'],
                'description' => ['nullable', 'min:10', 'max:255']
            ]
        );

        if($validator->fails()) {
            return response()->json(['status' => 'Validation failure', 'errors' => $validator->errors()]);
        }

        $input = $request->all();
        $trip = Trip::create($input);

        return response()->json(['success' => true, 'data' => $trip], 201);
    }

    // Update a trip
    public function update(Request $request, Trip $trip)
    {
        if($trip->user_id != Auth::guard('api')->id()) {
            return response()->json(['status' => 'Forbidden'], 403);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'from' => ['required', 'string', 'min:3', 'max:255'],
                'to' => ['required', 'string', 'min:3', 'max:255'],
                'car_model' => ['required', 'string', 'max:255'],
                'price_per_passenger' => ['required', 'integer'],
                'number_of_empty_seats' => ['required', 'integer', 'min:1'],
                'departure_date' => ['required', 'date', 'after:today'],
                'description' => ['nullable', 'min:10', 'max:255']
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'Validation failure', 'errors' => $validator->errors()]);
        }

        $input = $request->all();
        $trip->update($input);
        return response()->json(['status' => 'success', 'data' => $trip]);
    }

    // Delete a trip
    public function destroy(Trip $trip)
    {
        if ($trip->user_id != Auth::guard('api')->id()) {
            return response()->json(['status' => 'Forbidden'], 403);
        }
        $trip->delete();
        return response()->json(['success' => 'true', 'message' => 'Trip deleted successfuly.', 'data' => $trip], 200);
    }

}
