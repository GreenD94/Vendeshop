<?php

namespace Tests;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

trait CheckHelpers2
{

    protected $mainModelName, $mainApiName, $mainApiMethod, $mainApiModelName, $dataheaders = [];

    protected function initdata(array $data)
    {
        $this->mainModelName = $data["mainModelName"];
        $this->mainApiName = $data["mainApiName"];
        $this->mainApiMethod = $data["mainApiMethod"];
        $this->mainApiModelName = $data["mainApiModelName"];
    }
    protected function ResponseAssertJson(\Illuminate\Testing\TestResponse $response, array $controlData)
    {
        $mainApiModelName = $this->mainApiModelName;

        $is_sucees = $mainApiModelName::checkResponse($this->mainApiName, $response, $controlData);
        if (!$is_sucees) {
            $this->assertTrue(false, "test route did not match with any use case");
        }
    }

    protected function SanctumActingAs(array $userData = [], array $abilities = ['*']): User
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
        return $file;
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
    protected function CallDestroyApi(String $mainApiName = null, array $params = [], $tableNames = [], int $status = 200): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;

        $response = $this->deleteJson(route($routeUrl), $params, $this->dataheaders);

        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }
    protected function CallStoreApi(String $mainApiName = null, array $params = [], $tableNames = [], int $status = 200, $is_dd = false): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->postJson(route($routeUrl), $params, $this->dataheaders);
        if ($is_dd) {
            dd($response);
        }
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }
    protected function CallUpdateApi(String $mainApiName = null, array $params = [], $tableNames = [], int $status = 200): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->putJson(route($routeUrl), $params, $this->dataheaders);
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }

    protected function CallIndexApi(String $mainApiName = null, array $params = [], $tableNames = [], int $status = 200): \Illuminate\Testing\TestResponse
    {
        $routeUrl = $mainApiName ?? $this->mainApiName;
        $response = $this->getJson(route($routeUrl, $params), $this->dataheaders);
        $response->dump();
        $response->assertStatus($status);
        foreach ($tableNames as $key => $value) {
            $this->assertDatabaseCount($key, $value);
        }
        return $response;
    }

    public function checkUpdateField($field, $content, &$data, $createdModel, $tableNames = [], $is_dd = false)
    {
        $data[$field] = $content;

        $params = array('id' => $createdModel->id, $field => $data[$field]);
        $response = $this->CallUpdateApi(null, $params, $tableNames);
        if ($is_dd) {
            $response->dump();
            dd($response->original);
        }

        $data["id"] = $createdModel->id;
        $this->ResponseAssertJson($response, $data);
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
    protected function CheckFieldIsIntenger(string $field, array $params = [], $is_dd = false)
    {

        $params[$field] = 1.1;
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        if ($is_dd) dd($response->original);
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
    protected function CheckFieldIsBoolean(string $field, array $params = [], $is_dd = false)
    {
        $params[$field] = 'aaaaa';
        $response = $this->CallMAinApi($this->mainApiMethod ?? "post", $params);
        if ($is_dd) {
            dd($response);
        }

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
    protected function CheckFieldIsDate(string $field, array $params = [])
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
    protected function CheckFieldIsArray(string $field, array $params = [])
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
