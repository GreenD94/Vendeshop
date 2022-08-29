<?php

namespace App\Http\Controllers;

use App\Http\Requests\guestusers\GuestUserDestroyRequest;
use App\Http\Requests\guestusers\GuestUserIndexRequest;
use App\Http\Requests\guestusers\GuestUserStoreRequest;
use App\Http\Requests\guestusers\GuestUserUpdateRequest;
use App\Http\Resources\GuestResource;
use App\Models\GuestUsers;
use App\Traits\Responser;




class GuestUsersController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GuestUserIndexRequest $request)
    {


        $stocks = GuestUsers::paginate($request->limit ?? 5);



        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'users' =>  GuestResource::collection($stocks->items()),
        ];
        return $this->successResponse($data);
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
    public function store(GuestUserStoreRequest $request)
    {

        $data = $request->only(
            'device_key',


        );


        $createdModel = GuestUsers::create($data);


        return $this->successResponse(new GuestResource($createdModel));
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
    public function update(GuestUserUpdateRequest $request)
    {
        $data = $request->only(

            'device_key',


        );



        GuestUsers::whereId($request->id)->update($data);
        $createdModel = GuestUsers::find($request->id);


        return $this->successResponse(new GuestResource($createdModel));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GuestUserDestroyRequest $request)
    {
        $createdModel = GuestUsers::find($request->id);
        GuestUsers::destroy($request->id);

        return $this->successResponse(new GuestResource($createdModel));
    }
}
