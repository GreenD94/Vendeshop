<?php

namespace App\Models;

use App\Http\Resources\BannerResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $with = ['image'];
    protected $fillable = ['is_favorite', 'image_id', 'name', 'group_number'];
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }
    public static function groupByNumber(Collection $banners)
    {
        $groups = collect([]);
        $bannersFiltered =  collect([]);
        foreach ($banners as $key => $banner) {
            $groups->push($banner->group_number);
        }
        $groups = collect($groups->unique()->values()->all());
        $groups = collect($groups->sort()->values()->all());

        foreach ($groups as $key => $group) {
            $filtered = collect($banners->filter(function ($banner, $key) use ($group) {

                return $banner->group_number ==  $group;
            }));
            $filtered =  collect($filtered->sort()->values()->all());
            $bannersFiltered['B_' . $group] = BannerResource::collection($filtered);
        }

        return    $bannersFiltered;
    }
}
