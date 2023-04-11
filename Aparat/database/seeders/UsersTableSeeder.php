<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(User::count()){
            Channel::truncate();
            User::truncate();
        }
        $this->createAdminUser();
        for ($i=1;$i<5;$i++)
        {
            $this->createUser($i);
        }

    }

    private function createAdminUser()
    {
        $user = User::factory(1)->create([
            'type' => User::TYPE_ADMIN,
            'name' => 'مدیر اصلی',
            'email' => 'admin@aparat.me',
            'mobile' => '+989000000000',
            ]);

       $this->command->info('کاربر ادمین اصلی سایت ایجاد شد');
    }

    private function createUser($num = 1)
    {


        $user = User::factory(1)->create([
            'name' => 'کاربر' . $num,
            'email' => 'user'. $num .'@aparat.me',
            'mobile' => '+989' . str_repeat($num,9),
        ]);

        $this->command->info('کاربر' . $num . 'به سیستم اضافه شد');


    }
}
