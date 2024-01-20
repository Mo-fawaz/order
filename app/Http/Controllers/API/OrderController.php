<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Food;
use App\Models\location;
use App\Models\order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->apiResponse(OrderResource::collection(order::all()));
        } catch (\Throwable $th) {
            return $this->apiResponse(null, false, $th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_uuid' => 'required|string|exists:food,uuid',
            'user_uuid' => 'required|string|exists:users,uuid',
            'price_order' => 'required|integer',
            'order_type' => 'required|in:delivery,Receive',

        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $order = order::create([
                    'uuid' => Str::uuid(),
                    'price_order' => $request->input('price_order'),
                    'food_id' => Food::whereuuid($request->input('food_uuid'))->first()->id,
                    'user_id' => User::whereuuid($request->input('user_uuid'))->first()->id,
                    'location_id' => User::whereuuid($request->input('user_uuid'))->first()->location->id,
                    'order_type' => $request->input('order_type'),

                ]);
                return $this->apiResponse($order);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(order $order,$uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:orders,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                return $this->apiResponse($order->whereuuid($uuid)->first());
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th->getMessage(), 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(order $order,$uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:orders,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $order->whereuuid($uuid)->delete();
                return $this->apiResponse(True);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }
}
