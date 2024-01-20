<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Http\Traits\GeneralTrait;
use App\Models\food;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class FoodController extends Controller
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
            return $this->apiResponse(FoodResource::collection(food::all()));
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
            'restaurant_uuid' => 'required|string|exists:restaurants,uuid',
            'components' => 'required|string',
            'price' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $food = food::create([
                    'name' => $request->input('name'),
                    'uuid' => Str::uuid(),
                    'price' => $request->input('price'),
                    'components' => $request->input('components'),
                    'restaurant_id' => Restaurant::whereuuid($request->input('restaurant_uuid'))->first()->id,
                ]);
                return $this->apiResponse($food);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(food $food,$uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:food,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                return $this->apiResponse(FoodResource::make($food->whereuuid($uuid)->first()));
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, food $food ,$uuid)
    {
        $request['uuid'] = $uuid;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'components' => 'required|string',
            'price' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $food = food::whereuuid($uuid)->update([
                    'name' => $request->input('name'),
                    'price' => $request->input('price'),
                    'components' => $request->input('components'),
                ]);
                return $this->apiResponse($food);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th, 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(food $food,$uuid)
    {
        $validator = Validator::make(['uuid' => $uuid], [
            'uuid' => 'required|exists:food,uuid',
        ]);
        if ($validator->fails()) {
            return $this->requiredField($validator->errors());
        } else {
            try {
                $food->whereuuid($uuid)->delete();
                return $this->apiResponse(True);
            } catch (\Throwable $th) {
                return $this->apiResponse($th, false, $th, 500);
            }
        }
    }
}
