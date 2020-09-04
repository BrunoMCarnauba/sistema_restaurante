<?php

use Illuminate\Database\Seeder;

class FuncionariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcionarios')->delete(); /* Exclui todas as linhas contidas na tabela especificada. Executado antes dos insert para garantir que não haja duplicatas */

        /* Inserindo dados na tabela */
        $senha = md5('123456');
        DB::table('funcionarios')->insert(['id_funcionario' => 1, 'nome' => 'Admin => Alterar cadastro!', 'email' => 'admin@admin.com', 'senha' => $senha, 'cargo_id' => 1]);
    }
}
