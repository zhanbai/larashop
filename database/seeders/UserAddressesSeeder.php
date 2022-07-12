<?php

namespace Database\Seeders;

use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserAddress::factory()->count(3)->create(['user_id' => 1]);
    }
}
