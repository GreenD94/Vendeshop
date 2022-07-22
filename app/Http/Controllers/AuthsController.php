<?php

namespace App\Http\Controllers;

use App\Http\Requests\auths\AuthStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Responser;
use AppleSignIn\ASDecoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class AuthsController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {

        $authUser = User::find($request->user()->id);
        return $this->successResponse(new UserResource($authUser));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthStoreRequest $request)
    {


        if ($request->driver == "apple") {
            try {
                $result = User::StoreAppleAuth($request->device_key, $request->token);

                if (!$result) return $this->successResponse(null, "invalid credentials", 401);

                return $this->successResponse($result);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(),  $th->getMessage(), 500);
            }
        }


        if ($request->driver == "google") {
            try {
                $result = User::StoreGoogleAuth($request->device_key, $request->token);

                if (!$result) return $this->successResponse([], "invalid credentials", 401);

                return $this->successResponse($result);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(),  $th->getMessage(), $th->getCode());
            }
        }

        $result = User::StoreAuth($request->email, $request->password, $request->device_key);
        if (!$result) return $this->successResponse(null, "invalid credentials", 401);
        return $this->successResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {


        $user = User::find(Auth::id());
        $user->tokens()->delete();
        return $this->successResponse();
    }
}
