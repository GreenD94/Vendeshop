<?php

namespace App\Http\Controllers;

use App\Http\Requests\users\UserDestroyRequest;
use App\Http\Requests\users\UserIndexRequest;
use App\Http\Requests\users\UserStoreRequest;
use App\Http\Requests\users\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\address;
use App\Models\Image;
use App\Models\User;
use App\Traits\Responser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserIndexRequest $request)
    {


        $stocks = User::whenRole($request->role)
            ->whenVV($request->boolean('vv'))
            ->whenId($request->id)
            ->paginate($request->limit ?? 5);



        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'users' =>  UserResource::collection($stocks->items()),
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
    public function store(UserStoreRequest $request)
    {

        $data = $request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'phone',
            'birth_date',
            'dni'

        );

        if ($request->has('avatar')) {
            try {
                $modelData = Image::storeImage($request->file('avatar'));
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
            $createdImageModel = Image::create($modelData);
            $data['avatar_id'] = $createdImageModel->id;
        }

        $data['password'] = Hash::make($data['password']);

        if ($request->has('vv')) $data['last_name'] = 'vvvvv' . $data['last_name'];
        $createdModel = User::create($data);


        if ($request->role_id && Auth()->check()) {
            $createdModel->assignRole($request->role_id);
        } else {
            $createdModel->assignRole(Role::findByName('customer')->id);
        }

        $createdModel->addresses()->attach(address::create(['is_favorite' => true])->id);


        if ($request->from_app) $createdModel->tickets()->create(
            [
                'value' => 5000,
                'expiration_time' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
        return $this->successResponse(new UserResource($createdModel));
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
    public function update(UserUpdateRequest $request)
    {
        $data = $request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'phone',
            'birth_date',
            'device_key',
            'city',
            'address',
            'dni'

        );

        if ($request->has('avatar')) {
            try {
                $createdModel = User::find($request->id);
                if ($createdModel?->avatar?->id) Image::destroyImage($createdModel?->avatar?->id);
                $modelImageData = Image::storeImage($request->file('avatar'));
                $newImageModel = $createdModel->avatar_id ? Image::where("id", $createdModel->avatar_id)->update($modelImageData) : Image::create($modelImageData);;
                $data = array_merge($data, ["avatar_id" => $createdModel->avatar_id ?? $newImageModel->id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }
        if ($request->has('password'))
            $data['password'] = Hash::make($data['password']);


        User::whereId($request->id)->update($data);
        $createdModel = User::find($request->id);
        $address =  $createdModel?->addresses?->first();
        if ($address) {
            $dataAdrres = $request->only(
                'address',
                'city_id',
                'city_name',
                'street',
                'postal_code',
                'deparment',
                'is_favorite'

            );
            if ($request->phone) $dataAdrres['phone_number'] = $request->phone;
            address::whereId($address->id)->update($dataAdrres);
        }
        return $this->successResponse(new UserResource(User::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDestroyRequest $request)
    {
        $createdModel = User::find($request->id);
        User::destroy($request->id);

        return $this->successResponse(new UserResource($createdModel));
    }
}
