<?php

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Usuario::create([
            'nome'  => 'Admin',
            'email' => 'admin@teste.com',
            'senha' => md5('123456'),
            'admin' => true
        ]);
    }
}
