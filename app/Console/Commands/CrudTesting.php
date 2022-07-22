<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CrudTesting extends Command
{
    /** php artisan make:crudtesting Post
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crudtesting {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create an scalffolding for testing crud api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $name = $this->argument('name');
            $folderName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));

            Artisan::call(' make:factory ' . $name . 'Factory');
            Artisan::call(' make:controller ' . $name . 'Controller --resource');
            Artisan::call(' make:request ' . $folderName . '/' . $name . 'StoreRequest');
            Artisan::call(' make:request ' . $folderName . '/' . $name . 'IndexRequest');
            Artisan::call(' make:request ' . $folderName . '/' . $name . 'UpdateRequest');
            Artisan::call(' make:request ' . $folderName . '/' . $name . 'DestroyRequest');
            Artisan::call(' make:resource ' . $name . 'Resource');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'StoreTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'StoreValidationTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'UpdateTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'UpdateValidationTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'DestroyTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'DestroyValidationTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'IndexTest');
            Artisan::call(' make:test ' . $folderName . '/' . $name . 'IndexValidationTest');
            Artisan::call(' make:model ' . $name . ' --migration');
            dd("success");
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
