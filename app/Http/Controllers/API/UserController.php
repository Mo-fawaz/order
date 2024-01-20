<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\City;
use App\Models\location;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = $request->user();
            $token = $user->createToken('token-name')->plainTextToken;
            return $this->apiResponse(['token' => $token]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:Users|max:255',
                'password' => 'required|string',
                'city_uuid' => 'required|string|exists:cities,uuid',
                'location' => 'required|string',
            ]
        );
        if ($validate->fails()) {
            return $this->requiredField($validate->errors());
        } else {
            try {
                $user=User::create(
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'uuid' => Str::uuid(),
                        'role' => 0,
                    ]
                );
                $user->location()->save(new location([
                    'location' => $request->input('location'),
                    'city_id' => City::whereuuid($request->input('city_uuid'))->first()->id,
                ]));
                return $this->apiResponse($user);
            } catch (\Throwable $th) {
                return $this->apiResponse(null, false, $th->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        //
    }
}
