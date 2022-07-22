<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    static  $EN_ESPERA = 1;
    static  $LISTO = 2;
    static  $CANCELADO = 3;
    static  $EN_ENVIO = 4;

    public function isEnEspera()
    {
        return $this->id == 1;
    }
    public function isListo()
    {
        return $this->id == 2;
    }
    public function isCancelado()
    {
        return $this->id == 3;
    }
}
