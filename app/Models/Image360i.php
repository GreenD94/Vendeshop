<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image360i extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_main',
        'is_active',
        'animation_360_id',
        'image_id'
    ];


    public static $ANIMATION_360_ID = [];



    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }


    public function scopeOnlyAnimation360($query)
    {
        Image360i::$ANIMATION_360_ID = Image360i::pluck('animation_360_id')->unique()->all();
    }


    public function scopeWhenisMain($query, $is_main)
    {
        if ($is_main) $query->where('is_main', $is_main);
    }


    public function scopeWhenName($query, $name)
    {
        if ($name)  Image360i::$ANIMATION_360_ID = Image360i::where('name', 'like', '%' . $name . '%')->pluck('animation_360_id')->unique()->all();
    }



    public function scopeWhenisActive($query, $is_active)
    {
        if ($is_active) $query->where('is_active', $is_active);
    }


    public function scopeAnimation360Id($query, $animation_360_id)
    {

        if ($animation_360_id) Image360i::$ANIMATION_360_ID = collect(Image360i::$ANIMATION_360_ID)->filter(function ($value, $key) use ($animation_360_id) {
            return $value == $animation_360_id;
        })->all();
        //if ($chat_room_id) $query->where('chat_room_id', $chat_room_id);
    }

    public  function scopeGetAnimation360($query, $config)
    {
        $config = (object) $config;

        $frames = $query->whereIn('animation_360_id', Image360i::$ANIMATION_360_ID)->get();
        $frames = $frames->groupBy('animation_360_id');
        $frames = $frames->sortByDesc(function ($frame, $key) {
            return $frame[0]["animation_360_id"];
        });
        $frames = $frames->map(function ($item, $key) {
            return $item->sortByDesc("id")->values();
        });
        if ($config->frame_limit > 0) $frames = Image360i::limitFrames($config->frame_limit, $frames);
        $frames = Image360i::animation360Paginator($frames, $config->page, $config->paginate);
        return $frames;
    }



    public static  function animation360Paginator($frames, $page = null, $paginate = null)
    {
        if (($page === null) || ($paginate === null)) return $frames;
        $total = $frames->count();
        $last_page = ($total / $paginate);
        $page = $page - 1;
        $nextPage = $page <= $last_page ? $page : $page + 1;
        $previousPage = $page > 0 ? $page - 1 : $page;

        return [
            'total' => (int)  $total,
            'per_page' => (int) $paginate,
            'current_page' => (int) $page,
            'last_page' => (int) $last_page,
            'next_page_url' => request()->fullUrlWithQuery(array_merge(request()->query(), ['page' => $nextPage + 1])),
            'prev_page_url' => request()->fullUrlWithQuery(array_merge(request()->query(), ['page' => $previousPage + 1])),
            'animation' => $frames->splice($page, $paginate),
        ];;
    }

    public static  function limitFrames($frame_limit, $frames)
    {
        return $frames->map(function ($item, $key) use ($frame_limit) {
            return $item->splice(0, $frame_limit);
        });
    }
}
