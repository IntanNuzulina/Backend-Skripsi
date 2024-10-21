<?php

namespace Database\Seeders;

use App\Http\Resources\FlashsaleResource;
use App\Models\FlashSale;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FlashsaleSeeder::class
        ]);
    }
}
