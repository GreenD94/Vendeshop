<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $with = ['image'];
    protected $fillable = [
        'name',
        'is_favorite',
        "color",
        'image_id',
    ];
    static $LO_MAS_TOP = 1;
    static $OFERTAS = 2;

    public function isLoMasTop()
    {
        $this->id == Category::$LO_MAS_TOP;
    }

    public function isOfertas()
    {
        $this->id == Category::$OFERTAS;
    }


    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'category_subscriptions', 'category_id', 'stock_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }
}
