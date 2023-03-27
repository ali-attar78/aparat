<?php

namespace Database\Seeders;

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
        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser()
    {
        $user = User::factory(1)->create([
            'type' => User::TYPE_ADMIN,
            'name' => 'مدیر اصلی',
            'email' => 'admin@aparat.me',
            'mobile' => '+989111111111',
            ]);

       $this->command->info('کاربر ادمین اصلی سایت ایجاد شد');
    }

    private function createUser()
    {


        $user = User::factory(1)->create([
            'name' => 'کاربر1',
            'email' => 'user1@aparat.me',
            'mobile' => '+989222222222',
        ]);

        $this->command->info('یک کاربر پیش فرض به سیستم اضافه شد');


    }
}
