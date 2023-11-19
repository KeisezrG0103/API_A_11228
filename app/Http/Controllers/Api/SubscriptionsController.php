<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscriptions::all();

        if ($subscriptions) {
            return response()->json([
                'success' => true,
                'message' => 'List Subscriptions',
                'data' => $subscriptions
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Subscriptions Not Found',
                'data' => ''
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $registration = $request->all();
        $validatedData = Validator::make($registration, [
            'id_user' => 'required',
            'category' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'data' => $validatedData->errors()
            ], 400);
        } else {
            $user = User::find($request->id_user);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User Tidak Ditemukan',
                ], 404);
            }

            if ($user->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'status telah aktif',
                    'data' => $user
                ]);
            }
            $typeCategory = strtolower($request->category);

            if ($typeCategory == 'basic') {
                $price = 50000;
            } elseif ($typeCategory == 'premium') {
                $price = 100000;
            } elseif ($typeCategory == 'standard') {
                $price = 150000;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Found',
                ], 404);
            }
        }
        $user->status = 1;
        $user->save();

        $transactionDate = Carbon::now()->format('Y-m-d H:i:s');

        Subscriptions::create([
            'id_user' => $request->id_user,
            'category' => $typeCategory,
            'price' => $price,
            'transaction_date' => $transactionDate,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Success Add Subcription',
            'data' => $registration,
        ]);
    }


    public function update(Request $request, $id)
    {
        $subscription = Subscriptions::find($id);
        $updatedData = $request->all();
        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriptions Not Found',
            ], 404);
        }



        $validatedData = Validator::make($updatedData, [
            'id_user' => 'required',
            'category' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Update Subscriptions Failed',
                'data' => $validatedData->errors()
            ], 400);
        }
        $user = User::find($request->id_user);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Tidak Ditemukan',
            ], 404);
        }

        if ($user->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'status tidak aktif',
                'data' => $user
            ]);
        }


        if ($user->status == 1) {
            $typeCategory = strtolower($request->category);

            if ($typeCategory == 'basic') {
                $price = 50000;
            } elseif ($typeCategory == 'premium') {
                $price = 100000;
            } elseif ($typeCategory == 'standard') {
                $price = 150000;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Category Not Found',
                ], 404);
            }
            $subscription->id_user = $request->id_user;
            $date = Carbon::now()->format('Y-m-d H:i:s');
            $subscription->transaction_date = $date;
            $subscription->price = $price;
            $subscription->category = $typeCategory;

            $subscription->save();
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Success Update Subcription',
                'data' => $subscription,
            ]);

        }



    }

    public function destroy($id)
    {
        $subscription = Subscriptions::find($id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriptions Not Found',
            ], 404);
        }

        if ($subscription->delete()) {
            $user = User::find($subscription->id_user);
            $user->status = 0;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Success Delete Subcription',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Subscriptions Delete Failed',
            ], 500);
        }
    }

    public function getByID($id) {
        $subscription = Subscriptions::find($id);
        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscriptions Not Found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Success Get Subcription',
            'data' => $subscription,
        ]);
    }
}
