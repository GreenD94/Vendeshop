<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Orden Actualizada</title>

</head>



<body style=" width: 1150px;
height: 100%;
margin: 0;
    padding: 0;
    box-sizing: border-box;
    outline: none;">

    <div class="pricing" style="display: flex;
    justify-content: center;
    flex-wrap: wrap;
    width: 100%;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    outline: none;
    padding: 2rem;">

    <div class="vendeshop-header" style="text-align: center; background-color:rgb(240, 240, 240); border-radius: 20px;
    padding-top: 20px;
    margin: 0;
    box-sizing: border-box;
    outline: none;
    padding-bottom: 10px;

    width: 90%; ">

        <img src="https://dpg9ad9lj9exw.cloudfront.net/images/nrEpAVNMCZ2vdazP1samUHHMc43FXiAUtilpkkyt.png" alt=""
            width="40%">
    </div>
</div>

    <div
        style="
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        background-color: rgb(230, 230, 230);
        margin: 50px 50px 50px 50px;
        border-radius: 25px;
        padding-right: 65px;
        align-items: center;
        text-align: center;
        ">

        @if ($orden?->status_Logs->count()>1)

        <h1 style="text-align: center;"> Orden # {{ $orden->id }} <br> ha Cambiado del estado
            {{ $orden?->status_Logs->reverse()?->values()[1]?->order_status_name }} a el estado
            {{ $orden?->status_Logs->reverse()?->values()[0]?->order_status_name }}</h1>
        @endif

        <section class="pricing" style="display: flex;
        justify-content: center;
        flex-wrap: wrap;

        width: 100%;
        padding: 2rem;">


            <!-- Datos --------------------------------- comprador-->

                <div class="card-wrapper" style="display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 320px;
                height: auto;
                background-color: #f9f9f9;
                border-radius: 20px;
                box-shadow: 0 5px 14px rgba(0, 0, 0, 0.25), 0 5px 14px rgba(0, 0, 0, 0.22);
                padding: 2rem;
                margin: 1rem;
                transform: all 0.2s ease-in;">
                <font face="Arial">
                    <!-- card header-->
                    <div class="card-header" style=" margin: 1rem;
                    text-align: center;">
                        <h2 style="color: #6fa759;
                        letter-spacing: 2;"> Datos del Comprador</h2>
                    </div>
                    <!--card detail-->
                    <div class="card-detail" style="width: 100%;  ">
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Nombre:</b> {{ $orden->user_first_name ?? '' }}
                            {{ $orden->user_last_name ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Teléfono:</b>  {{ $orden->user_phone ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Correo:</b> {{ $orden->user_email ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Fecha de Nacimiento: </b> {{ $orden->user_birth_date ?? '' }}</p>
                    </div>
                </font>
                </div>


            @if ($orden->address_address)
            <!-- Direc --------------------------------- ción-->

                <div class="card-wrapper" style="display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 320px;
                height: auto;
                background-color: #f9f9f9;
                border-radius: 20px;
                box-shadow: 0 5px 14px rgba(0, 0, 0, 0.25), 0 5px 14px rgba(0, 0, 0, 0.22);
                padding: 2rem;
                margin: 1rem;
                transform: all 0.2s ease-in;">
                <font face="Arial">
                    <!-- card header-->
                    <div class="card-header" style=" margin: 1rem;
                    text-align: center;">

                        <h2 style="color: #6fa759;
                        letter-spacing: 2;"> Direccion</h2>
                    </div>
                    <!--card detail-->
                    <div class="card-detail" style="width: 100%;  ">
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Principal:</b> {{ $orden->address_address ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Estado:</b> {{ $orden->address_state_name ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Ciudad: </b> {{ $orden->address_city_name ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Calle:</b> {{ $orden->address_street ?? '' }}</p>

                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b> Codigo Postal:</b> {{ $orden->address_postal_code ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Departamento:</b> {{ $orden->address_deparment ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Telefono:</b> {{ $orden->address_phone_number ?? '' }}</p>

                    </div>
                </font>
                </div>

            @endif

            <!-- lo --------------------------------- otro-->


            @if ($orden->billing_address)
                <div class="card-wrapper" style="display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 320px;
                height: auto;
                background-color: #f9f9f9;
                border-radius: 20px;
                box-shadow: 0 5px 14px rgba(0, 0, 0, 0.25), 0 5px 14px rgba(0, 0, 0, 0.22);
                margin: 1rem;
                transform: all 0.2s ease-in;">
                <font face="Arial">
                    <!-- card header-->
                    <div class="card-header" style="margin: 1rem;
                    text-align: center;">

                        <h2 style="color: #6fa759;
                        letter-spacing: 2;"> Direccion</h2>
                    </div>
                    <!--card detail-->
                    <div class="card-detail" style="width: 100%;  padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;">
                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b>Principal:</b> {{ $orden->billing_address ?? '' }}</p>
                        @endif

                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b>Estado:</b> {{ $orden->billing_state_name ?? '' }}</p>
                        @endif

                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b>Ciudad: </b> {{ $orden->billing_city_name ?? '' }}</p>
                        @endif

                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b>Calle:</b> {{ $orden->billing_street ?? '' }}</p>
                        @endif

                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b> Codigo Postal:</b> {{ $orden->billing_postal_code ?? '' }}</p>
                        @endif

                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b>Departamento:</b> {{ $orden->billing_deparment ?? '' }}</p>
                        @endif

                        @if ($orden->billing_phone_number)
                        <p><span class="fas fa-check check"></span> <b>Telefono:</b> {{ $orden->billing_phone_number ?? '' }}</p>
                        @endif
                    </div>
                </font>
                </div>

            @endif

            <!-- lo --------------------------------- otro again -->


                <div class="card-wrapper" style="display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 320px;
                height: auto;
                background-color: #f9f9f9;
                border-radius: 20px;
                box-shadow: 0 5px 14px rgba(0, 0, 0, 0.25), 0 5px 14px rgba(0, 0, 0, 0.22);

                margin: 1rem;
                transform: all 0.2s ease-in;">
                <font face="Arial">
                    <!-- card header-->
                    <div class="card-header" style=" margin: 1rem;
                    text-align: center;">

                        <h2 style="color: #6fa759;
                        letter-spacing: 2;"> General</h2>
                    </div>
                    <!--card detail-->
                    <div class="card-detail" style="width: 100%; ">

                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Estado:</b> {{ $orden?->status?->order_status_name ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Tipo De Pago:</b> {{ $orden->payment_type_name ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Total De Puntos Gastados:</b>
                            {{ floatval(number_format((float) $orden->tickets()->sum('value'), 2, '.', '')) }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Total:</b> {{ $orden->total ?? '' }}</p>
                        <p style=" padding: 0.6rem 1.5rem;
                        font-size: 0.8rem;
                        border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Costo de envio:</b> {{ $orden->shipping_cost ?? '' }}</p>
                        @if ($orden->numero_guia)
                        <p><span class="fas fa-check check"></span> <b>Numero Guia:</b> {{ $orden->numero_guia ?? '' }}</p>
                        @endif

                    </div>
                </font>
                </div>

            </section>
    </div>

    </div>

    <div
        style="
        margin: 50px 50px 50px 50px;
        border-radius: 25px;
        text-align: center;
        align-items: center;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        background-color: rgb(230, 230, 230);
        padding-right: 65px;">

        <section class="pricing" style="  display: flex;
        justify-content: center;
        flex-wrap: wrap;
        width: 100%;
        padding-right: 40px;">
        <ul>


        @foreach ($orden->details as $detail)
        <li>
            <div class="card-wrapper" style=" display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 320px;
            height: auto;
            background-color: #f9f9f9;
            border-radius: 20px;
            box-shadow: 0 5px 14px rgba(0, 0, 0, 0.25), 0 5px 14px rgba(0, 0, 0, 0.22);
            padding: 2rem;
            margin: 1rem;
            transform: all 0.2s ease-in;">
            <font face="Arial">
                <!-- card header-->
                <div class="card-header" style=" margin: 1rem;
                text-align: center;">

                    <h2 style="color: #6fa759;
                    letter-spacing: 2;">{{ $loop->index + 1 }}</h2>
                    <img src="https://dpg9ad9lj9exw.cloudfront.net/{{ $detail?->cover_image_url ?? '' }}" alt="" style="width: 200px;
                    display: block;
                    margin: 0 auto;
                    border-radius: 20px; border: 30px">
                </div>
                <!--card detail-->
                <div class="card-detail" style="width: 100%;">
                    <p style="padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Nombre:</b> {{ $detail?->name ?? '' }}</p>
                    <p style="padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Cantidad:</b> {{ $detail?->quantity ?? '' }}</p>
                    <p style="padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Precio:</b> {{ $detail?->price ?? '' }}</p>
                    <p style="padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Descuento:</b> {{ $detail?->discount ?? '' }}</p>

                    @if ($detail->color_hex)
                    <p style="padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Color:</b> <b style="color:{{ $detail->color_hex }}"> - </b> </p>
                    @endif

                    @if ($detail->size_size)
                    <p style="padding: 0.6rem 1.5rem;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #d5d5d5;"><span class="fas fa-check check"></span> <b>Talla:</b> {{ $detail->size_size ?? '' }}</p>
                    @endif

                </div>
            </font>
            </div>
        </li>
            @endforeach
        </ul>
        </section>
    </div>


</body>

</html>

