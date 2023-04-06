<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;


Artisan::command('aparat:clear', function () {
    clear_storage('videos');
    $this->info('Clear uploaded video files');


    clear_storage('category');
    $this->info('Clear uploaded category files');


    clear_storage('channel');
    $this->info('Clear uploaded channel files');


})->describe('Clear all temporary files and ...');
