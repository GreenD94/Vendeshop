<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class address extends Model
{
    use HasFactory;
    protected $fillable = [
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
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_address', 'address_id', 'user_id');
    }

    public function scopeWhenUserId($query, $user_id)
    {

        if ($user_id)
            $query->whereHas('users', function (Builder $query2) use ($user_id) {
                $query2->where('users.id',  $user_id);
            });
    }


    public  function calculateShippingCostType()
    {

        $unwanted_array = array(
            'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
        );



        //deparment --> departamento destino
        //city name --> poblacion de destionoe
        $query = null;
        //$query->orWhere('departamento_destino', 'like', '%' . $this->deparment . '%');
        $searchs = explode(";", $this->city_name);
        foreach ($searchs as $key => $search) {
            if ($query) {
                $query->orWhere('poblacion_destino', 'like', '%' . $search . '%');
            } else {
                $query = ShippingCost::where('poblacion_destino', 'like', '%' . $search . '%');
            }
        }
        $shippingCosts = $query->get()->map(function ($shippingCost, $key) use ($searchs, $unwanted_array) {
            $poblacion_destino = $shippingCost->poblacion_destino;
            $poblacion_destino = strtr($poblacion_destino, $unwanted_array);
            $poblacion_destino = Str::of($poblacion_destino)->lower();
            $shippingCost->match = 0;

            foreach ($searchs as $key => $search) {

                $fixedSearch =  strtr($search, $unwanted_array);
                $fixedSearch = Str::of($fixedSearch)->lower();

                $hasFixedSearch = $poblacion_destino->contains($fixedSearch);
                if ($hasFixedSearch) $shippingCost->match++;
            }

            foreach ($searchs as $key => $search) {

                $fixedSearch =  strtr($search, $unwanted_array);
                $fixedSearch = Str::of($fixedSearch)->lower();
                $poblacion_destino = $poblacion_destino->remove($fixedSearch);
            }

            $shippingCost->matchPrecicion =  strlen($poblacion_destino);
            return $shippingCost;
        });

        $matchPrecicion = $shippingCosts->min('matchPrecicion');
        $match = $shippingCosts->max('match');
        $shippingCosts =  $shippingCosts->filter(function ($shippingCost, $key) use ($match, $matchPrecicion) {
            return $shippingCost->matchPrecicion == $matchPrecicion && $shippingCost->match == $match;
        });


        return $query->first()?->type ?? ShippingCost::$RX;
    }
}
