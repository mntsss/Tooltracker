<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'name' => $faker->company." ".$faker->numberBetween(54684),
        'acquired_from' => $faker->address,
        'warranty_date' => $faker->date('Y-m-d', '2020-12-31'),
        'purchase_date' => $faker->date('Y-m-d', '2018-12-31'),
        'consumable' => $faker->boolean,
        'group_id' => 1,
        'identification' => $faker->md5
    ];
});
