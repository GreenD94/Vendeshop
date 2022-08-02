<?php

namespace App\Http\Controllers;

use App\Http\Requests\boots\BootIndexRequest;
use App\Http\Requests\categories\CategoryDeleteRequest;
use App\Http\Requests\categories\CategoryIndexRequest;
use App\Http\Requests\categories\CategoryStoreRequest;
use App\Http\Requests\categories\CategoryUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\BackgroundResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\IconCategoryResource;
use App\Http\Resources\IconResource;
use App\Http\Resources\StockResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VideoResource;
use App\Models\Ad;
use App\Models\Background;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Icon;
use App\Models\IconCategory;
use App\Models\Stock;
use App\Models\User;
use App\Models\Video;
use App\Traits\Responser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class BootsController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BootIndexRequest $request)
    {
        $latest_stocks = Stock::with([
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon'
        ])->whenOrderBy($request->Latest_stock_order_by ?? "latest")->paginate($request->Latest_limit ?? 5);

        $latest_stocks =  [
            'total' => (int) $latest_stocks->total(),
            'per_page' => (int)$latest_stocks->perPage(),
            'current_page' => (int)$latest_stocks->currentPage(),
            'last_page' => (int) $latest_stocks->lastPage(),
            'next_page_url' => $latest_stocks->nextPageUrl(),
            'prev_page_url' => $latest_stocks->previousPageUrl(),
            'prev_page_url' => $latest_stocks->previousPageUrl(),
            'stocks' =>  StockResource::collection($latest_stocks->items()),
        ];

        $stocks = Stock::with([
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon'
        ])->whenOrderBy($request->stock_order_by ?? "random")->paginate($request->Latest_limit ?? 5);

        $stocks =  [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'stocks' =>  StockResource::collection($stocks->items()),
        ];

        $top_stocks = Stock::with([
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon'
        ])->whenOrderBy($request->top_stock_order_by ?? "last_updated")->whenCategoryId(Category::$LO_MAS_TOP)->paginate($request->Latest_limit ?? 5);

        $top_stocks =  [
            'total' => (int) $top_stocks->total(),
            'per_page' => (int)$top_stocks->perPage(),
            'current_page' => (int)$top_stocks->currentPage(),
            'last_page' => (int) $top_stocks->lastPage(),
            'next_page_url' => $top_stocks->nextPageUrl(),
            'prev_page_url' => $top_stocks->previousPageUrl(),
            'prev_page_url' => $top_stocks->previousPageUrl(),
            'top_stocks' =>  StockResource::collection($top_stocks->items()),
        ];

        $stocks_promotions = Stock::with([
            'cover_image',
            'images',
            'videos',
            'categories',
            'color',
            'size',
            'product.sizes',
            'product.colors',
            'ribbon'
        ])->whenOrderBy($request->promotions_stock_order_by ?? "last_updated")->whenCategoryId(Category::$OFERTAS)->paginate($request->Latest_limit ?? 5);

        $stocks_promotions =  [
            'total' => (int) $stocks_promotions->total(),
            'per_page' => (int)$stocks_promotions->perPage(),
            'current_page' => (int)$stocks_promotions->currentPage(),
            'last_page' => (int) $stocks_promotions->lastPage(),
            'next_page_url' => $stocks_promotions->nextPageUrl(),
            'prev_page_url' => $stocks_promotions->previousPageUrl(),
            'prev_page_url' => $stocks_promotions->previousPageUrl(),
            'stocks_promotions' =>  StockResource::collection($stocks_promotions->items()),
        ];






        $banners = Banner::groupByNumber(Banner::orderBy('id', 'desc')->get());


        $icons = Icon::orderBy('id', 'desc')->paginate($request->icon_limit ?? 5);
        $icons = [
            'total' => (int) $icons->total(),
            'per_page' => (int)$icons->perPage(),
            'current_page' => (int)$icons->currentPage(),
            'last_page' => (int) $icons->lastPage(),
            'next_page_url' => $icons->nextPageUrl(),
            'prev_page_url' => $icons->previousPageUrl(),
            'prev_page_url' => $icons->previousPageUrl(),
            'icons' =>  IconResource::collection($icons->items()),
        ];

        $ads = Ad::orderBy('id', 'desc')->paginate($request->ad_limit ?? 5);
        $ads = [
            'total' => (int) $ads->total(),
            'per_page' => (int)$ads->perPage(),
            'current_page' => (int)$ads->currentPage(),
            'last_page' => (int) $ads->lastPage(),
            'next_page_url' => $ads->nextPageUrl(),
            'prev_page_url' => $ads->previousPageUrl(),
            'prev_page_url' => $ads->previousPageUrl(),
            'ads' =>  AdResource::collection($ads->items()),
        ];

        $backgrounds = Background::orderBy('id', 'desc')->paginate($request->background_limit ?? 5);
        $backgrounds = [
            'total' => (int) $backgrounds->total(),
            'per_page' => (int)$backgrounds->perPage(),
            'current_page' => (int)$backgrounds->currentPage(),
            'last_page' => (int) $backgrounds->lastPage(),
            'next_page_url' => $backgrounds->nextPageUrl(),
            'prev_page_url' => $backgrounds->previousPageUrl(),
            'prev_page_url' => $backgrounds->previousPageUrl(),
            'backgrounds' =>  BackgroundResource::collection($backgrounds->items()),
        ];

        $videos = Video::orderBy('id', 'desc')->whenIsInformation(true)->paginate($request->video_limit ?? 5);
        $videos = [
            'total' => (int) $videos->total(),
            'per_page' => (int)$videos->perPage(),
            'current_page' => (int)$videos->currentPage(),
            'last_page' => (int) $videos->lastPage(),
            'next_page_url' => $videos->nextPageUrl(),
            'prev_page_url' => $videos->previousPageUrl(),
            'prev_page_url' => $videos->previousPageUrl(),
            'videos' =>  VideoResource::collection($videos->items()),
        ];





        $iconCategory =  IconCategory::orderBy('id', 'desc')->get();
        // $iconCategory = [
        //     'total' => (int) $iconCategory->total(),
        //     'per_page' => (int)$iconCategory->perPage(),
        //     'current_page' => (int)$iconCategory->currentPage(),
        //     'last_page' => (int) $iconCategory->lastPage(),
        //     'next_page_url' => $iconCategory->nextPageUrl(),
        //     'prev_page_url' => $iconCategory->previousPageUrl(),
        //     'prev_page_url' => $iconCategory->previousPageUrl(),
        //     'icon_category' =>  IconCategoryResource::collection($iconCategory->items()),
        // ];

        $authUser = User::find(Auth()->id() ?? 1);
        $data = [
            "latest_stocks" => $latest_stocks,
            "stocks" => $stocks,
            "top_stocks" => $top_stocks,
            "stocks_promotions" => $stocks_promotions,
            "banners" => $banners,
            "icons" => $icons,
            'icon_category' =>  IconCategoryResource::collection($iconCategory),
            "ads" => $ads,
            "videos" => $videos,
            "backgrounds" => $backgrounds,
            "categories" => CategoryResource::collection(Category::orderBy('id', 'desc')->get()),
            "auth" => auth()->check() ? new UserResource(User::find(Auth()->id() ?? 1)) : null,
            "badge" => $authUser->unreadNotifications()->count()
        ];

        return $this->successResponse($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        try {

            // Artisan::call('migrate');
            Artisan::call('db:seed');
        } catch (\Throwable $th) {
            $this->errorResponse($th->getTrace(), $th->getMessage(), 500);
        }


        return $this->successResponse([], "success");
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
    public function store(request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
    }
}
