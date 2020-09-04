<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Tarefa;
use Faker\Generator as Faker;

$factory->define(Tarefa::class, function (Faker $faker) {
    return [
        'descricao' => $faker->title,
        'data'      => $faker->date('Y-m-d'),
        'usuario_id'=> 1,
        'imagem'    => 'teste.png'
    ];
});
