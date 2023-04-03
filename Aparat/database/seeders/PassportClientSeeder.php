<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPersonalClient();
        $this->createPasswordClient();
    }

    private function createPersonalClient()
    {
        DB::table('oauth_clients')->insert([

            'user_id' => null,
            'name' => 'Laravel Personal Access Client',
            'redirect' => 'http://localhost',
            'provider' => null,
            'secret' => 'mOsuiPz5ehXChdZvv4qa1PLBNlFDQCXyjoqQOff0',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),

        ]);


        DB::table('oauth_personal_access_clients')->insert([

            'client_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),

        ]);


    }

    private function createPasswordClient()
    {

        DB::table('oauth_clients')->insert([

            'user_id' => null,
            'name' => 'Laravel Password Grant Client',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'secret' => 'xYyHFj4VPHKAwoo6IQXCF8z56QcTmPNdHHGBJjg7',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

    }


}
