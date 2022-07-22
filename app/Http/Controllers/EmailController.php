<?php

namespace App\Http\Controllers;

use App\Http\Requests\email\EmailStoreRequest;
use App\Models\User;
use App\Traits\Responser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
    public function store(EmailStoreRequest $request)
    {

        $details = [
            'mainTitle' => $request->mainTitle,
            'secundaryTitle' => $request->secundaryTitle,
            'body' =>  $request->body,
            'logoHref' =>  $request->logoHref ?? "https://vende-shop.com/",
            'logoSrc' => $request->logoSrc ?? "https://vende-shop.com/wp-content/uploads/2021/05/icono-ho.png",
            'facebookHref' => $request->facebookHref ?? "https://www.facebook.com/VendeShop2021/",
            'facebookSrc' => $request->facebookSrc ?? "https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-dark-gray/facebook@2x.png",
            'instagramHref' => $request->instagramHref ?? "https://www.instagram.com/vende_shop/",
            'instagramSrc' => $request->instagramSrc ?? "https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-dark-gray/instagram@2x.png",
            'youtubekHref' => $request->youtubekHref ?? "https://www.youtube.com/channel/UCkRDEkZREWbNPTREVMWXd5Q",
            'youtubekSrc' => $request->youtubekSrc ?? "https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/circle-dark-gray/youtube@2x.png",
            'buttonHref' => $request->buttonHref ?? "https://vende-shop.com/",
            'buttonText' => $request->buttonText ?? "Visitar Pagina",
        ];

        try {
            $toMails = $request->to;

            if ($request->to == '*') $toMails = User::whereNotNull('email')->pluck('email')->all();
            foreach ($toMails as $key => $mail) {
                Mail::to($mail)->send(new \App\Mail\MyTestMail($details));
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }


        return $this->successResponse(["emails" => $toMails], "success");
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
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
    }
}
