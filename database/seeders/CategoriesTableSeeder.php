<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count())
            Category::truncate();

        $categories = ['عمومی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'گیم' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'ورزشی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'کارتون' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'طنز' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'آموزشی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'تفریحی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'فیلم' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'مذهبی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'موسیقی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'خبری' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'سیاسی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'علم و تکنولوژی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'حوادث' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'گزدشگری' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'حیوانات' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'متفرقه' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'تبلیغات' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'هنری' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'بانوان' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'سلامت' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'آشپزی' => ['icon' => '', 'banner' => '', 'user_id' => null],
            'دسته بندی 1' => ['icon' => '', 'banner' => '', 'user_id' => 1] // special category 4 user

        ];
        foreach ($categories as $categoryName => $options)
        {
            Category::create([
                'title' => $categoryName,
                'icon' => $options['icon'],
                'banner' => $options['banner'],
                'user_id' => $options['user_id']
            ]);
            $this->command->info('add' . $categoryName . 'category');
        }
    }
}
