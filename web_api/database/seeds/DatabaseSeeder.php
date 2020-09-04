<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CargosSeeder::class);
        $this->call(FuncionariosSeeder::class);
        $this->call(CategoriasProdutosSeeder::class);
        $this->call(MesasSeeder::class);
    }
}
