<?php

namespace App\Http\Controllers;

use App\Http\Requests\address\addressDestroyRequest;
use App\Http\Requests\address\addressIndexRequest;
use App\Http\Requests\address\addressStoreRequest;
use App\Http\Requests\address\addressUpdateRequest;
use App\Http\Resources\addressResource;
use App\Models\address;
use App\Models\User;
use App\Traits\Responser;
use Illuminate\Http\Request;

class addressController extends Controller
{

    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(addressIndexRequest $request)
    {
        $modelQuery = address::orderBy('id', 'desc')->whenUserId($request->user_id);

        if (!$request->page) return $this->successResponse(addressResource::collection($modelQuery->get()));

        $models = $modelQuery->paginate($request->limit ?? 5);


        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'address' =>  addressResource::collection($models->items()),
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
    public function store(addressStoreRequest $request)
    {
        $data = $request->only(
            'address',
            'city_id',
            'street',
            'postal_code',
            'deparment',
            'phone_number',
            'city_name',
            'is_favorite',
            'state_name',
            'state_id'
        );

        $createdModel = address::create($data);

        if ($request->has('user_id')) {


            $user = User::find($request->user_id);

            $isFirst = $user?->addresses?->isEmpty() ?? true;
            if ($isFirst) $data['is_favorite'] = true;

            $user->addresses()->attach($createdModel->id);
            if ($request->boolean('is_favorite')) {
                $keys =  $user?->addresses?->modelKeys();
                address::whereIn('id', $keys)->update(['is_favorite' => false]);
                address::whereId($request->id)->update(['is_favorite' => true]);
            }
        }
        return $this->successResponse(new addressResource($createdModel));
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
    public function update(addressUpdateRequest $request)
    {
        $data = $request->only(
            'address',
            'city_id',
            'city_name',
            'street',
            'postal_code',
            'deparment',
            'phone_number',
            'is_favorite',
            'state_name',
            'state_id'
        );

        $createdModel = address::find($request->id);
        address::where('id', $request->id)->update($data);
        $user_id =  $createdModel?->users?->first()?->id;
        if ($user_id) {
            $user = User::find($user_id);

            if ($request->boolean('is_favorite')) {
                $keys =  $user?->addresses?->modelKeys();
                address::whereIn('id', $keys)->update(['is_favorite' => false]);
                address::whereId($request->id)->update(['is_favorite' => true]);
            }
        }
        return $this->successResponse(new addressResource(address::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(addressDestroyRequest $request)
    {
        $createdModel = address::find($request->id);
        $user =  $createdModel->users->first();
        $isAlone =  $user?->addresses?->count() == 1;
        if ($isAlone) return $this->errorResponse([], "no se puede borrar la Direccion, ya que es el unico", 422);
        address::destroy($request->id);

        if ($createdModel->is_favorite)   $user = User::find($user->id);
        if ($createdModel->is_favorite) address::where("id", $user->addresses->first()->id)->update(["is_favorite" => true]);
        return $this->successResponse(new addressResource($createdModel));
    }
}
