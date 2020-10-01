<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(6),
        'content' => $faker->realText(300),
        'tags' => json_encode(['PHP', 'SQL', 'Node']),
        'autor_id' =>  factory(User::class),
    ];
});
