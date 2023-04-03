<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        if (Tag::count()){
           Tag::truncate();
        }

        $tags = [
            'عمومی',
            'خبری',
            'علم و تکنولوژی',
            'ورزشی',
            'بانوان',
            'بازی',
            'طنز',
            'آموزشی',
            'تفریحی',
            'فیلم',
            'مذهبی',
            'موسیقی',
            'سیاسی',
            'حوادث',
            'گردشگری',
            'حیوانات',
            'متفرقه',
            'تبلیغات',
            'هنری',
            'کارتون',
            'سلامت',

        ];

        foreach ($tags as $tagName) {
            Tag::create(['title'=>$tagName]);
            $this->command->info('add these tags' . implode(', ', $tags));
        }

    }
}
