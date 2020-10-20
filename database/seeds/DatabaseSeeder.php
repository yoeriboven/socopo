<?php

namespace Database\Seeders;

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
            'email' => 'yoeri@yoeri.me',
            'password' => bcrypt('toetsenbord')
        ]);

        // Add profiles
        $profiles = [
            [
                'username' => 'daviddobrik',
                'avatar' => 'https://instagram.fams2-1.fna.fbcdn.net/vp/c1581bb9353bece8c4dc6c32dc880218/5D0AF498/t51.2885-19/s320x320/40374841_2171773369523155_4420619227124203520_n.jpg?_nc_ht=instagram.fams2-1.fna.fbcdn.net'
            ],
            [
                'username' => 'besiktas',
                'avatar' => 'https://instagram.fams2-1.fna.fbcdn.net/vp/311d83dcf58004ea8b96d716c8de2e34/5D127141/t51.2885-19/s320x320/23421635_1355016614620383_2372662405302845440_n.jpg?_nc_ht=instagram.fams2-1.fna.fbcdn.net'
            ],
            [
                'username' => 'afcajax',
                'avatar' => 'https://instagram.fams2-1.fna.fbcdn.net/vp/d6f39d2b9f188f9f7b8cc5cece6cb27b/5D1640E8/t51.2885-19/s320x320/47496090_275249859741440_1488316458529193984_n.jpg?_nc_ht=instagram.fams2-1.fna.fbcdn.net'
            ]
        ];

        foreach ($profiles as $profileData) {
            $profile = factory('App\Profile')->create($profileData);
            $profile->attachUser($user);

            factory('App\Post')->create(['profile_id' => $profile->id]);
            factory('App\Post')->create(['profile_id' => $profile->id]);
        }
    }
}
