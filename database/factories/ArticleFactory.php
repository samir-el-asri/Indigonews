<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'user_id'=>18,
        'title'=>$faker->catchPhrase(),
        'content'=>implode($faker->paragraphs(3)),
        'feature'=>'noimage.jpg',
        'category_id'=>$faker->numberBetween(2,8),
        'created_at'=>\Carbon\Carbon::now(),
        'updated_at'=>\Carbon\Carbon::now()
    ];
});
