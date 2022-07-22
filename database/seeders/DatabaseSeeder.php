<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            // FakeDataSeeder::class,
            // MasterUserSeeder::class
            // PayuConfigSeeder::class
            //PayuConfigtestSeeder
        ]);
    }
}
