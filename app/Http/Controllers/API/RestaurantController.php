<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Http\Traits\GeneralTrait;
use App\Models\City;
use App\Models\location;
use App\Models\Phone;
use App\Models\restaurant;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
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
            return $this->apiResponse(RestaurantResource::collection(Restaurant::all()));
        } catch (\Throwable $th) {
            return $this->apiResponse($th, false, $th->getMessage(), 500);
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
            'name' => 'required|string',
            'type_uuid' => 'required|string|exists:types,uuid',
            'phone' => 'required|regex:/(0)[0-9]/|notregex:/[a-z]/|min:10|unique:phones,phone',
            'location' => 'required|string',
            'city_uuid' => 'required|string|exists:cities,uuid',
            
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $restaurant = restaurant::create([
                    'name' => $request->input('name'),
                    'uuid' => Str::uuid(),
                    'type_id' => Type::whereuuid($request->input('type_uuid'))->first()->id,
                ]);
                $restaurant->location()->save(new location([
                    'location' => $request->input('location'),
                    'city_id' => City::whereuuid($request->input('city_uuid'))->first()->id,
                ]));
                $restaurant->phone()->save(new Phone([
                    'phone' => $request->input('phone'),
                ]));
                return $this->apiResponse($restaurant);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(restaurant $restaurant,$uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:restaurants,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                return $this->apiResponse(RestaurantResource::make($restaurant->whereuuid($uuid)->first()));
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, restaurant $restaurant ,$uuid)
    {
        $request['uuid'] = $uuid;
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|exists:restaurants,uuid',
            'phone' => 'required|regex:/(0)[0-9]/|notregex:/[a-z]/|min:10|unique:phones,phone',
            'location' => 'required|string',
            'city_uuid' => 'required|string|exists:cities,uuid',
            
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $restaurant=$restaurant->whereuuid($uuid)->first();
                $restaurant->location()->save(new location([
                    'location' => $request->input('location'),
                    'city_id' => City::whereuuid($request->input('city_uuid'))->first()->id,
                ]));
                $restaurant->phone()->save(new Phone([
                    'phone' => $request->input('phone'),
                ]));
                return $this->apiResponse($restaurant);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th->getMessage(), 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(restaurant $restaurant,$uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:restaurants,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $restaurant->whereuuid($uuid)->delete();
                return $this->apiResponse(True);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }
}
