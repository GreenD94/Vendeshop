<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Stock extends Model
{

    use HasFactory;
    protected $table = 'stocks';
    protected $with = ["categories"];
    protected $fillable = [
        'price',
        'mock_price',
        'credits',
        'discount',
        'cover_image_id',
        'description',
        'name',
        'color_id',
        'size_id',
        'ribbon_id',
        'is_available',
        'rx_cost',
        'nacional_cost',
        'urbano_cost'
    ];
    // protected $appends = ['sizes', 'colors'];

    public function cover_image()
    {
        return $this->belongsTo(Image::class, 'cover_image_id', 'id');
    }
    public function ribbon()
    {
        return $this->belongsTo(Ribbon::class, 'ribbon_id', 'id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_subscriptions', 'stock_id', 'image_id');
    }
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_subscriptions', 'stock_id', 'video_id');
    }
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'colors_subscriptions', 'stock_id', 'color_id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'sizes_subscriptions', 'stock_id', 'size_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subscriptions', 'stock_id', 'category_id')->withTimestamps();
    }
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }




    public function favorited_by_users()
    {
        return $this->belongsToMany(User::class, 'favorite_stocks', 'stock_id', 'user_id');
    }


    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeWhenOrderBy($query, $orderBy)
    {
        if ($orderBy == "last_updated") $query->orderByDesc(
            CategorySubscription::select('id')
                ->whereColumn('stock_id', 'stocks.id')
                ->orderByDesc('id')
                ->limit(1)
        );
        if ($orderBy == "latest") $query->latest();
        if ($orderBy == "random") $query->inRandomOrder();
        if ($orderBy == "desc") $query->orderBy('id', 'desc');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeWhenLimit($query, $limit)
    {
        if ($limit) $query->limit($limit);
    }


    public function scopeWhenCategoryId($query, $category_id)
    {

        if ($category_id)
            $query->whereHas('categories', function (Builder $query2) use ($category_id) {
                $query2->where('categories.id',  $category_id);
            });
    }

    public function scopeWhenId($query, $id)
    {
        if ($id) $query->where('id',  $id);
    }

    // public function scopeWhenSearch($query, $search)
    // {

    //     if ($search)

    //         $query->orWhere(function ($query1) use ($search) {
    //             $query1->orWhere('name', 'like', '%' . $search . '%')
    //                 ->orWhereHas('categories', function (Builder $query2) use ($search) {
    //                     $query2->where('categories.name', 'like', '%' . $search . '%');
    //                 });
    //         });
    // }

    public function scopeWhenSearch($query, $search)
    {

        if (!$search) return;
        $searchWords = collect(explode(" ", $search));
        $isResultEmpty = true;

        while ($isResultEmpty) {

            $searchWords = $searchWords->filter(function ($value, $key) {
                $isNotShortWord = strlen($value) > 2;
                return  $isNotShortWord;
            });
            $searchWords = $searchWords->unique();
            if ($searchWords->isEmpty()) break;

            $clonedQuery = clone $query;
            $clonedQuery->orWhere(function ($query1) use ($searchWords) {
                foreach ($searchWords as $key => $value) {
                    $query1->orWhere('name', 'like', '%' . $value . '%');
                }
                $query1->orWhereHas('categories', function (Builder $query2) use ($searchWords) {
                    foreach ($searchWords as $key => $value) {
                        $query2->where('categories.name', 'like', '%' . $value . '%');
                    }
                });
            });
            $isResultEmpty =  $clonedQuery->count() == 0;
            if (!$isResultEmpty) break;
            $searchWords = $searchWords->map(function ($word, $key) {
                $reducedWord = substr($word, 0, -1);
                return $reducedWord;
            });
        }

        $query->orWhere(function ($query1) use ($searchWords) {
            foreach ($searchWords as $key => $value) {
                $query1->orWhere('name', 'like', '%' . $value . '%');
            }
            $query1->orWhereHas('categories', function (Builder $query2) use ($searchWords) {
                foreach ($searchWords as $key => $value) {
                    $query2->where('categories.name', 'like', '%' . $value . '%');
                }
            });
        });
    }





    public function scopeWhenIsFavorite($query, $is_favorite, $is_favorite_id)
    {


        if ($is_favorite && (!$is_favorite_id)) $query->whereHas('favorited_by_users', function (Builder $query2) use ($is_favorite_id) {
            $query2->where('users.id',  auth()->id());
        });
        if ($is_favorite && $is_favorite_id) $query->whereHas('favorited_by_users', function (Builder $query2) use ($is_favorite) {
            $query2->where('users.id',  $is_favorite);
        });
    }


    public  function CalculateShippingCost($tipoEnvio)
    {

        if ($tipoEnvio == ShippingCost::$RX) return $this->rx_cost;
        if ($tipoEnvio == ShippingCost::$NACIONAL) return $this->nacional_cost;
        if ($tipoEnvio == ShippingCost::$URBANO) return $this->urbano_cost;
    }
}
