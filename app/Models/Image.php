<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url'];


    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'image_subscriptions',  'image_id', 'stock_id',);
    }

    public function stocks_covers()
    {
        return $this->hasMany(Stock::class, 'cover_image_id', 'id');
    }


    /**
     * Store the uploaded file on the s3 dile disk and return
     * [
     *   "name" => $imgePrefix . "|" . $nextId,
     *    "url" => $path
     * ]
     *
     * @param  UploadedFile  $uploadedFile
     * @param  String  $imgePrefix="image"
     * @return array
     */
    public static function storeImage(UploadedFile $uploadedFile, String $imgePrefix = "image"): array
    {
        $path = $uploadedFile->store('images', 's3');
        $lastestModel = Image::latest()->first();
        $nextId = $lastestModel ? $lastestModel->id + 1 : 1;
        return [
            "name" => $imgePrefix . "|" . $nextId,
            "url" => $path
        ];
    }


    /**
     * Delete the uploaded file on the s3
     *
     * @param  String  $id
     * @return Image
     */
    public static function destroyImage(int $id)
    {
        if (!$id) return;
        $createdModel = Image::find($id);
        Storage::disk('s3')->delete($createdModel->url);
        return $createdModel;
    }
}
