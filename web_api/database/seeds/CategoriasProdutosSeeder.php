<?php

use Illuminate\Database\Seeder;

class CategoriasProdutosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias_produtos')->delete(); /* Exclui todas as linhas contidas na tabela especificada. Executado antes dos insert para garantir que não haja duplicatas */

        /* Inserindo dados na tabela */
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 1, 'nome' => 'Manhã', 'descricao' => 'Comidas vendidas pela manhã']);
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 2, 'nome' => 'Almoço', 'descricao' => 'Comidas do horário de almoço']);
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 3, 'nome' => 'Doces', 'descricao' => 'Variados doces']);
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 4, 'nome' => 'Lanche', 'descricao' => 'Lanches']);
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 5, 'nome' => 'Jantar', 'descricao' => 'Comidas de jantar']);
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 6, 'nome' => 'Bebidas', 'descricao' => 'Todos tipos de bebidas']);
        DB::table('categorias_produtos')->insert(['id_categoria_produto' => 7, 'nome' => 'Outros', 'descricao' => 'Alimentos que não se encaixam nas demais categorias']);

    }
}
