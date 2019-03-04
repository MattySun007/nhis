<?php

use App\Models\Permission;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
  return [
    'name' => $faker->word
  ];
});
