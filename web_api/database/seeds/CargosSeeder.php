<?php

use Illuminate\Database\Seeder;

class CargosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cargos')->delete(); /* Exclui todas as linhas contidas na tabela especificada. Executado antes dos insert para garantir que não haja duplicatas */

        /* Inserindo na tabela 'cargos' os nomes dos cargos e seus determinados salários. */
        DB::table('cargos')->insert(['id_cargo' => 1, 'nome' => 'Administrador(a)', 'descricao' => 'Tem acesso à todo o sistema', 'salario' => 5000.00, 'tudo_permitido' => true]);
        DB::table('cargos')->insert(['id_cargo' => 2, 'nome' => 'Cozinheiro(a)', 'descricao' => 'Acesso à funções que não são de administrador', 'salario' => 3000.00, 'tudo_permitido' => false]);
        DB::table('cargos')->insert(['id_cargo' => 3, 'nome' => 'Garçom', 'descricao' => 'Acesso à funções que não são de administrador', 'salario' => 2500.00, 'tudo_permitido' => false]);
        DB::table('cargos')->insert(['id_cargo' => 4, 'nome' => 'Operador(a) de caixa', 'descricao' => 'Acesso à funções que não são de administrador', 'salario' => 3250.00, 'tudo_permitido' => false]);
    }
}