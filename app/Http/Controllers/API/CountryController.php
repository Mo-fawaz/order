<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CountryController extends Controller
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
            return $this->apiResponse(Country::all());
        } catch (\Throwable $th) {
            return $this->apiResponse(null, false, $th, 500);
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
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $country = Country::create([
                    'name' => $request->input('name'),
                    'uuid' => Str::uuid(),
                ]);
                return $this->apiResponse($country);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(country $country, $uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:countries,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                return $this->apiResponse($country->whereuuid($uuid)->first());
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(country $country, $uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:countries,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $country->whereuuid($uuid)->delete();
                return $this->apiResponse(True);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }
}
