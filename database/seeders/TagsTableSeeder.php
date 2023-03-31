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
        $tags = ['گیم' ,
            'ورزشی' ,
            'کارتون' ,
            'طنز' ,
            'آموزشی' ,
            'تفریحی' ,
            'فیلم' ,
            'مذهبی' ,
            'موسیقی' ,
            'خبری' ,
            'سیاسی' ,
            'علم و تکنولوژی' ,
            'حوادث' ,
            'گزدشگری' ,
            'حیوانات' ,
            'متفرقه' ,
            'تبلیغات' ,
            'هنری' ,
            'بانوان' ,
            'سلامت' ,
            'آشپزی'
        ];
        foreach ($tags as $tag)
        {
            Tag::create([
                'title' => $tag,
            ]);
            $this->command->info('add' . $tag . 'category');
        }
    }
}
