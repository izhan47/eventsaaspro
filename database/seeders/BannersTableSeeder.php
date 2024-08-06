<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Eventsaaspro\Models\Banner;

class BannersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $banner = Banner::count();
        if($banner)
            return true;

        $banner = $this->banner('id', 1);
        if (!$banner->exists) {
            $banner->fill([
                'title' => 'EventSaaSPro',
                'subtitle' => 'Event management & selling platform',
                'image' => 'banners/August2019/3MIAC8BaLwk8ytlYYvVi.jpg',
                'status' => 1,
                'order' => 1,
                'button_url' => 'https://eventsaaspro-pro.classiebit.com/events',
                'button_title' => 'Get Event Tickets',
            ])->save();
        }

    }

    protected function banner($field, $for)
    {
        return Banner::firstOrNew([$field => $for]);
    }
}
