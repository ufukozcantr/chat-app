<?php

use App\Models\User;
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
        // $this->call(UsersTableSeeder::class);
        factory(User::class)->create([
            'name' => 'Ufuk Özcan',
            'email' => 'ufukozcantr@gmail.com',
            'password' => bcrypt('123qwe*')
        ]);

        factory(User::class)->create([
            'name' => 'Ekin Özcan',
            'email' => 'ekinozcantr@gmail.com',
            'password' => bcrypt('123qwe*')
        ]);

        factory(User::class)->create([
            'name' => 'Gülay Özcan',
            'email' => 'gulayozcantr@gmail.com',
            'password' => bcrypt('123qwe*')
        ]);

        factory(User::class)->create([
            'name' => 'İbrahim Özcan',
            'email' => 'ibrahimozcantr@gmail.com',
            'password' => bcrypt('123qwe*')
        ]);
    }
}
