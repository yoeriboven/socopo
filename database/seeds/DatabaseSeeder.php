<?php

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
        $user = factory('App\User')->create([
            'name' => 'Yoeri.me',
            'email' => 'yoeri@yoeri.me',
            'password' => bcrypt('toetsenbord')
        ]);

        // Add profiles
        $dobrik = factory('App\Profile')->create([ 'username' => 'daviddobrik' ]);
        $besiktas = factory('App\Profile')->create([ 'username' => 'besiktas' ]);
        $ajax = factory('App\Profile')->create([ 'username' => 'afcajax' ]);

        $besiktas->attachUser($user);
        $dobrik->attachUser($user);
        $ajax->attachUser($user);

        // Add posts
        factory('App\Post')->create(['profile_id' => $dobrik->id]);
        factory('App\Post')->create(['profile_id' => $dobrik->id]);
        factory('App\Post')->create(['profile_id' => $besiktas->id]);
        factory('App\Post')->create(['profile_id' => $dobrik->id]);
        factory('App\Post')->create(['profile_id' => $ajax->id]);
        factory('App\Post')->create(['profile_id' => $ajax->id]);
    }
}
