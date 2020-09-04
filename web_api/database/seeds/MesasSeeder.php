<?php

use Illuminate\Database\Seeder;

class MesasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mesas')->delete(); /* Exclui todas as linhas contidas na tabela especificada. Executado antes dos insert para garantir que não haja duplicatas */

        /* Inserindo dados na tabela */
        DB::table('mesas')->insert(['id_mesa' => 1, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 2, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 3, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 4, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 5, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 6, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 7, 'status' => 'Disponível']);
        DB::table('mesas')->insert(['id_mesa' => 8, 'status' => 'Disponível']);
    }
}
