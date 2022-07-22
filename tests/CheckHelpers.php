<?php

namespace Tests;

use App\Models\Image;
use App\Models\User;
use Exception;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\assertTrue;

trait CheckHelpers
{

    protected $mainModelName, $mainApiName, $mainApiMethod, $dataheaders = [];



    protected function ResponseAssertJson(\Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($this->mainApiName == 'api.mobile.push_notification_events.destroy' || $this->mainApiName == 'api.mobile.push_notification_events.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                        )
                );
        if ($this->mainApiName == 'api.mobile.push_notification_events.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json) =>
                            $json
                                ->where('id',  $controlData["id"])
                                ->where('name', $controlData["name"])

                        )
                );
        if ($this->mainApiName == 'api.mobile.push_notification_events.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('name', $controlData["name"])
                        )
                );



        if ($this->mainApiName == 'api.mobile.push_notifications.destroy' || $this->mainApiName == 'api.mobile.push_notifications.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',

                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('user_id', $controlData["user_id"])
                                ->where('tittle', $controlData["tittle"])
                                ->where('body', $controlData["body"])
                                ->where('fcm_token', $controlData["fcm_token"])
                                ->where('is_new', $controlData["is_new"])
                                ->where('is_new', $controlData["is_new"])
                                ->has('event', fn ($json) =>
                                $json
                                    ->where('id', $controlData["event_id"])
                                    ->where('name', $controlData["event_name"]))
                        )
                );



        if ($this->mainApiName == 'api.mobile.push_notifications.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('push_notifications',  $controlData['data_length'], fn ($json) =>  $json
                                    ->where('id', $controlData["id"])
                                    ->where('user_id', $controlData["user_id"])
                                    ->where('tittle', $controlData["tittle"])
                                    ->where('body', $controlData["body"])
                                    ->where('fcm_token', $controlData["fcm_token"])
                                    ->where('is_new', $controlData["is_new"])
                                    ->where('is_new', $controlData["is_new"])
                                    ->has('event', fn ($json) =>
                                    $json
                                        ->where('id', $controlData["event_id"])
                                        ->where('name', $controlData["event_name"])))
                        )
                );
        if ($this->mainApiName == 'api.mobile.push_notifications.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('user_id', $controlData["user_id"])
                                ->where('tittle', $controlData["tittle"])
                                ->where('body', $controlData["body"])
                                ->where('fcm_token', $controlData["fcm_token"])
                                ->where('is_new', $controlData["is_new"])

                                ->has('event', fn ($json) =>
                                $json
                                    ->where('id', $controlData["event_id"])
                                    ->where('name', $controlData["event_name"]))
                        )
                );



        if ($this->mainApiName == 'api.mobile.icons.destroy' || $this->mainApiName == 'api.mobile.icons.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->where('id', $controlData["image_id"])
                                    ->where('name', $controlData["image_name"])
                                    ->where('url', $controlData["image_url"]))
                        )
                );



        if ($this->mainApiName == 'api.mobile.icons.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json) =>
                            $json
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->where('id', $controlData["image_id"])
                                    ->where('name', $controlData["image_name"])
                                    ->where('url', $controlData["image_url"]))
                        )
                );
        if ($this->mainApiName == 'api.mobile.icons.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('name', $controlData["name"])
                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('url'))
                        )
                );

        if ($this->mainApiName == 'api.mobile.ads.destroy' || $this->mainApiName == 'api.mobile.ads.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->where('id', $controlData["image_id"])
                                    ->where('name', $controlData["image_name"])
                                    ->where('url', $controlData["image_url"]))
                        )
                );



        if ($this->mainApiName == 'api.mobile.ads.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json) =>
                            $json
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->where('id', $controlData["image_id"])
                                    ->where('name', $controlData["image_name"])
                                    ->where('url', $controlData["image_url"]))
                        )
                );
        if ($this->mainApiName == 'api.mobile.ads.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('name', $controlData["name"])
                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('url'))
                        )
                );


        if ($this->mainApiName == 'api.mobile.backgrounds.destroy' || $this->mainApiName == 'api.mobile.backgrounds.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])

                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->where('id', $controlData["image_id"])
                                    ->where('name', $controlData["image_name"])
                                    ->where('url', $controlData["image_url"]))
                        )
                );



        if ($this->mainApiName == 'api.mobile.backgrounds.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json) =>
                            $json
                                ->where('id', $controlData["id"])

                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->where('id', $controlData["image_id"])
                                    ->where('name', $controlData["image_name"])
                                    ->where('url', $controlData["image_url"]))
                        )
                );
        if ($this->mainApiName == 'api.mobile.backgrounds.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')

                                ->where('is_favorite', $controlData["is_favorite"])
                                ->where('color', $controlData["color"])
                                ->has('image', fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('url'))
                        )
                );

        if ($this->mainApiName == 'api.mobile.posts.destroy' || $this->mainApiName == 'api.mobile.posts.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('body', $controlData["body"])
                                ->where('is_main', $controlData["is_main"])
                                ->where('user_id', $controlData["user_id"])
                                ->where('user_name', $controlData["user_name"])
                                ->where('stock_id', $controlData["stock_id"])
                                ->has('replies')
                                ->has('created_at')
                                ->has('cover_image')
                        )
                );



        if ($this->mainApiName == 'api.mobile.posts.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',

                            fn ($json) =>
                            $json->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('posts',  $controlData['data_length'], fn ($json) =>
                                $json->has('id')
                                    ->has('body')
                                    ->has('is_main')
                                    ->has('user_id')
                                    ->has('user_name')
                                    ->has('stock_id')
                                    ->has('replies')
                                    ->has('created_at')
                                    ->has('cover_image'))
                        )
                );
        if ($this->mainApiName == 'api.mobile.posts.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('body', $controlData["body"])
                                ->where('is_main', $controlData["is_main"])
                                ->where('user_id', $controlData["user_id"])
                                ->where('user_name', $controlData["user_name"])
                                ->where('stock_id', $controlData["stock_id"])
                                ->has('created_at')
                                ->has('cover_image')
                                ->has('replies')
                        )
                );

        if ($this->mainApiName == 'api.mobile.videos.destroy' || $this->mainApiName == 'api.mobile.videos.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                                ->where('url', $controlData["url"])
                                ->where('is_information', $controlData["is_information"])
                        )
                );



        if ($this->mainApiName == 'api.mobile.videos.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json) =>
                            $json->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])
                                ->where('url', $controlData["url"])
                                ->where('is_information', $controlData["is_information"])
                        )
                );
        if ($this->mainApiName == 'api.mobile.videos.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('name', $controlData["name"])
                                ->where('url', $controlData["url"])
                                ->where('is_information', $controlData["is_information"])
                        )
                );

        return $this->assertTrue(false, "test route did not match with any use case");
    }

    protected function SanctumActingAs(array $userData = [], array $abilities =  ['*']): User
    {


        $authdUser = User::factory()->create($userData);
        $this->dataheaders['Authorization'] = 'Bearer ' . $authdUser->createToken($authdUser->id . $authdUser->name . uniqid())->plainTextToken;

        // Sanctum::actingAs(
        //     $authdUser,
        //     $abilities
        // );
        return  $authdUser;
    }

    protected function storageS3(): \Illuminate\Http\Testing\File
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->image('avatar.jpg');
        return  $file;
    }


    protected function mainModelCreate(array $modelData = [], array $tableNames = [], $is_dd = false)
    {
        $model = $this->mainModelName;
        $createdModel = $model::Factory()->create($modelData);
        
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $createdModel;
    }
    protected function mainModelCreateMany(array $modelData = [], array $tableNames = [], int $count)
    {
        $model = $this->mainModelName;
        $createdModels = $model::Factory()->count($count)->create($modelData);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $createdModels;
    }
    protected function CallDestroyApi(String  $mainApiName = null, array $params = [], $tableNames = [], int $status = 200, $is_dd = false): \Illuminate\Testing\TestResponse
    {


        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->deleteJson(route($routeUrl), $params, $this->dataheaders);
        if ($is_dd) {
         
        }
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }
    protected function CallStoreApi(String  $mainApiName = null, array $params = [], $tableNames = [], int $status = 200): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->postJson(route($routeUrl), $params, $this->dataheaders);
        $response->dump();
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }
    protected function CallUpdateApi(String  $mainApiName = null, array $params = [], $tableNames = [], int $status = 200): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->putJson(route($routeUrl), $params, $this->dataheaders);
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }
    protected function CallIndexApi(String  $mainApiName = null, array $params = [], $tableNames = [], int $status = 200): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->getJson(route($routeUrl, $params), $this->dataheaders);
        // $response->dump();
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }


    protected function CallMAinApi(string $mainApiMethod, array $params = []): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $this->mainApiName;
        $response = null;
        switch ($mainApiMethod) {
            case 'post':
                $response = $this->postJson(route($routeUrl), $params, $this->dataheaders);
                break;
            case 'delete':
                $response = $this->deleteJson(route($routeUrl), $params, $this->dataheaders);
                break;
            case 'put':
                $response = $this->putJson(route($routeUrl), $params, $this->dataheaders);
                break;
            case 'get':
                $response = $this->getJson(route($routeUrl, $params), $this->dataheaders);
                break;
            default:
                # code...
                break;
        }

        return $response;
    }
    protected function CheckFieldIsExists(string $field, array $params = [])
    {

        $params[$field] = 44444;
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsRequired(string $field, array $params = [], $is_dd = false)
    {

        unset($params[$field]);
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);

        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsIntenger(string $field, array $params = [])
    {

        $params[$field] = 1.1;
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsNumeric(string $field, array $params = [])
    {

        $params[$field] = "aaaaaa";
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsNaturalNumber(string $field, array $params = [])
    {

        $params[$field] = -1;
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsUnique(string $field, array $params = [])
    {
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsBoolean(string $field, array $params = [])
    {
        $params[$field] = 'aaaaa';
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
    protected function CheckFieldIsImage(string $field, array $params = [])
    {
        $params[$field] = 'aaaaa';
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has($field)
                    )
            );
    }
}
