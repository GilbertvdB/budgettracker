<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Gilbert B.',
            'email' => 'gvanderbiezen@gmail.com',
            'password' => bcrypt('Crunks_69'),
        ]);

        
        User::factory()->create([
            'name' => 'Gerlinde van W.',
            'email' => 'gerlindevanwarners@hotmail.com',
            'password' => bcrypt('1Bastaard!'),
        ]);
    }
}
