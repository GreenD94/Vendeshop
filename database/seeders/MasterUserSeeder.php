<?php

namespace Database\Seeders;

use App\Models\address;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Size;
use App\Models\Stock;
use App\Models\User;
use App\Models\Video;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(["name" => "customer"]);
        Role::create(["name" => "admin"]);
        Role::create(["name" => "master"]);
        $password = "master@master.com";
        $body = [
            'email' => "master@master.com",
            'password' => Hash::make($password),
            'first_name' => 'master',
            'last_name' => 'master',
            'avatar_id' => Image::factory()->create([
                'url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScskPIMZJMMekOCpeHWnVtlhll8Fi73yCoYA&usqp=CAU',
                'name' => 'avatar01'
            ])->id
        ];
        $model = User::factory()->create($body);
        $model->assignRole("master");

        $model->addresses()->attach(address::factory()->create(['is_favorite' => true])->id);

        $model = User::factory()->create([
            'email' => "admin1@admin1.com",
            'first_name' => 'admin1',
            'last_name' => 'admin1',
            'password' => Hash::make('admin1'),
            'avatar_id' => Image::factory()->create([
                'url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScskPIMZJMMekOCpeHWnVtlhll8Fi73yCoYA&usqp=CAU',
                'name' => 'avatar01'
            ])
        ]);
        $model->addresses()->attach(address::factory()->create(['is_favorite' => true])->id);
        $model->assignRole("admin");

        $model = User::factory()->create([
            'email' => "admin2@admin2.com",
            'first_name' => 'admin2',
            'last_name' => 'admin2',
            'password' => Hash::make('admin2'),
            'avatar_id' => Image::factory()->create([
                'url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScskPIMZJMMekOCpeHWnVtlhll8Fi73yCoYA&usqp=CAU',
                'name' => 'avatar01'
            ])
        ]);
        $model->assignRole("admin");
        $model->addresses()->attach(address::factory()->create(['is_favorite' => true])->id);
    }
}
