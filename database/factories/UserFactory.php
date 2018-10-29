<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'avatar' => $faker->imageUrl(640, 480),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
    ];
});

$factory->define(App\UserCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->bothify('user category ##??'),
//        'user_id' => factory('App\User')->create()->id,
    ];
});

$factory->define(App\SchedulePost::class, function (Faker $faker) {
    return [
        'text' => $faker->text(100),
        'date_to_post' => $faker->dateTime('2018-12-12 00:00:00'),
//        'group_id' => factory('App\Group')->create()->id,
    ];
});

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->bothify('group ##??'),
        'link' => $faker->url,
    ];
});

$factory->define(App\FacebookAccount::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'link' => $faker->url,
//        'user_id' => factory('App\User')->create()->id,
        'long_life_token' => 'token_' . str_random(20),
    ];
});

$factory->define(App\CustomCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->bothify('custom category ##??'),
    ];
});

$factory->define(App\Attachment::class, function (Faker $faker) {
    return [
        'route' => $faker->imageUrl(300, 300, 'cats'),
//        'schedule_post_id' => factory('App\SchedulePost')->create()->id,
    ];
});