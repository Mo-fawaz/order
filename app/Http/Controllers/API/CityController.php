<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class CityController extends Controller
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
            return $this->apiResponse(City::all());
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
            'name' => 'required|string',
            'country_uuid' => 'required|string|exists:countries,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $city = City::create([
                    'name' => $request->input('name'),
                    'country_id' => Country::whereuuid($request->input('country_uuid'))->first()->id,
                    'uuid' => Str::uuid(),
                ]);
                return $this->apiResponse($city);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city, $uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:cities,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                return $this->apiResponse($city->whereuuid($uuid)->first());
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city, $uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:cities,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $city->whereuuid($uuid)->delete();
                return $this->apiResponse(True);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }
}
