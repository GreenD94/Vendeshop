<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\stocks\StockDestroyRequest;
use App\Http\Requests\stocks\StockIndexRequest;
use App\Http\Requests\stocks\StockStoreRequest;
use App\Http\Requests\stocks\StockUpdateRequest;
use App\Http\Resources\StockResource;
use App\Models\Color;
use App\Models\Image;
use App\Models\Size;
use App\Models\Stock;
use App\Models\Video;
use App\Traits\Responser;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

class StocksController extends Controller
{

    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StockIndexRequest $request)
    {

        $relationship = [
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon',
            'favorited_by_users'
        ];
        $data = [];
        if ($request->has("page")) {

            $stocks = Stock::with($relationship)
                ->whenOrderBy($request->order_by)
                ->whenId($request->id)
                ->whenCategoryId($request->category_id)
                ->whenSearch($request->search)
                ->whenIsFavorite($request->is_favorite, $request->is_favorite_id)
                ->paginate($request->limit ?? 5);

            $data =  [
                'total' => (int) $stocks->total(),
                'per_page' => (int)$stocks->perPage(),
                'current_page' => (int)$stocks->currentPage(),
                'last_page' => (int) $stocks->lastPage(),
                'next_page_url' => $stocks->nextPageUrl(),
                'prev_page_url' => $stocks->previousPageUrl(),
                'prev_page_url' => $stocks->previousPageUrl(),
                'stocks' =>  StockResource::collection($stocks->items()),
            ];
        } else {

            $stocks = Stock::with($relationship)
                ->whenOrderBy($request->order_by)
                ->whenCategoryId($request->category_id)
                ->whenSearch($request->search)
                ->whenId($request->id)
                ->whenIsFavorite($request->is_favorite, $request->is_favorite_id)
                ->whenLimit($request->limit);
            $data = StockResource::collection($stocks->get());
        }

        return $this->successResponse($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StockStoreRequest $request)
    {



        try {
            $modelData = Image::storeImage($request->file('cover_image'), 'stock|' . $request->name);
            $coverImageModel = Image::create($modelData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }


        $modelData = array_merge($request->only(
            'price',
            'mock_price',
            'credits',
            'discount',
            'description',
            'name',
            'color_id',
            'size_id',
            'ribbon_id',
            'is_available'
        ), ["cover_image_id" => $coverImageModel->id]);

        // $modelData['discount'] = $modelData['discount'] / 100;
        $modelData['discount'] = 1;
        $createdModel = Stock::create($modelData);
        $createdModel->images()->attach($coverImageModel->id);

        $colors = collect([]);
        $colorDatas = $request->colors ?? [];
        foreach ($colorDatas as $key => $color) {
            $createdColor = Color::create(['hex' => $color]);
            $colors->push($createdColor->id);
        }
        if ($createdModel->color_id) $colors->push($createdModel->color_id);
        if ($colors->count() > 0) $createdModel->colors()->sync($colors);



        $sizes = collect([]);
        $sizesDatas = $request->sizes ?? [];
        foreach ($sizesDatas as $key => $size) {
            $createdSize = Size::create(['size' => $size]);
            $sizes->push($createdSize->id);
        }
        if ($createdModel->size_id) $sizes->push($createdModel->size_id);
        if ($sizes->count() > 0) $createdModel->sizes()->sync($sizes);




        $images = $request->file('images');
        foreach ($images as $key => $image) {
            try {
                $imageModelData = Image::storeImage($image, 'stock|' . $request->name);
                $imageImageModel = Image::create($imageModelData);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
            $createdModel->images()->attach($imageImageModel->id);
        }

        $categories = collect($request->categories);
        if ($categories->count() > 0) $createdModel->categories()->sync($categories);




        $videoDatas = $request->videos ?? [];
        foreach ($videoDatas as $key => $video) {
            $createdvideo = Video::factory()->create(['url' => $video]);
            $createdModel->videos()->attach($createdvideo->id);
        }




        $createdModel->load(
            'cover_image',
            'images',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon',
            'categories'
        );
        return $this->successResponse(new StockResource($createdModel));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StockUpdateRequest $request)
    {
        $modelData = $request->only(
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
            'is_available'
        );

        if ($request->ribbon_id == 0) $modelData['ribbon_id'] = null;
        if ($request->has('discount')) $modelData['discount'] = $modelData['discount'] / 100;

        if ($request->id == '*') {
            Stock::whenCategoryId($request->where_category_id)->update($modelData);



            if ($request->has('discount')) Stock::where('id', '>', 0)->update(
                ["price" => DB::raw('("price"-("price" * ' . $modelData['discount'] . '))')]
            );

            return   $this->successResponse([], Stock::count() . ' Stocks has been updated!!');
        }


        if (is_array($request->id)) {

            Stock::whereIn('id', $request->id)->update($modelData);
            if ($request->has('discount')) Stock::whereIn('id', $request->id)->update(
                ["price" => DB::raw('("price"-("price" * ' . $modelData['discount'] . '))')]
            );

            return   $this->successResponse([]);
        }


        if ($request->has('cover_image')) {
            try {
                $mockImage = null;
                $createdModel = Stock::find($request->id);
                if ($createdModel->cover_image_id)  Image::destroyImage($createdModel->cover_image_id);
                if (!$createdModel->cover_image_id) {
                    $mockImage = Image::create(['name' => 'a', 'url' => 'a']);
                    $createdModel->cover_image_id = $mockImage->id;
                };
                $modelImageData = Image::storeImage($request->file('cover_image'), 'stock|' . $createdModel->name);
                Image::where("id", $createdModel->cover_image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["cover_image_id" => $createdModel->cover_image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }

        if ($request->has('discount')) $modelData['price'] = ($modelData['price'] - ($modelData['price'] *  $modelData['discount']));

        Stock::whereId($request->id)->update($modelData);
        $createdModel = Stock::find($request->id);
        $categories = collect($request->categories);
        if ($categories->count() > 0) $createdModel->categories()->sync($categories);



        $colors = collect([]);
        $colorDatas = $request->colors ?? [];
        foreach ($colorDatas as $key => $color) {
            $createdColor = Color::create(['hex' => $color]);
            $colors->push($createdColor->id);
            $createdModel->colors()->attach($createdColor->id);
        }




        $sizes = collect([]);
        $sizesDatas = $request->sizes ?? [];
        foreach ($sizesDatas as $key => $size) {
            $createdSize = Size::create(['size' => $size]);
            $sizes->push($createdSize->id);
            $createdModel->sizes()->attach($createdSize->id);
        }


        $videoDatas = $request->videos ?? [];
        foreach ($videoDatas as $key => $video) {
            $createdvideo = Video::factory()->create(['url' => $video]);
            $createdModel->videos()->attach($createdvideo->id);
        }

        if ($request->has('images')) {
            $images = $request->file('images');
            foreach ($images as $key => $image) {
                try {
                    $imageModelData = Image::storeImage($image, 'stock|' . $request->name);
                    $imageImageModel = Image::create($imageModelData);
                } catch (\Throwable $th) {
                    return $this->errorResponse($th->getTrace(), $th->getMessage());
                }
                $createdModel->images()->attach($imageImageModel->id);
            }
        }
        $model = Stock::find($request->id)->load(
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon',
            'categories'
        );
        return $this->successResponse(new StockResource($model));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockDestroyRequest $request)
    {
        try {
            $createdModel = Stock::find($request->id);
            Image::destroyImage($createdModel->cover_image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }
        $createdModel->load(
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon.image',
            'categories'
        );

        $createdModel->categories()->detach();

        $images = $createdModel->images;
        $createdModel->images()->detach();
        $videos = $createdModel->videos;
        $createdModel->videos()->detach();
        $createdModel->favorited_by_users()->detach();
        Stock::destroy($createdModel->id);
        Image::destroy($images->pluck('id'));
        Video::destroy($videos->pluck('id'));





        return $this->successResponse(new StockResource($createdModel));
    }
}
