<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model {
    
    use SoftDeletes;
    
    //Não protege nenhum campo
    protected $guarded = [];

    //Esconde os campos
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Retorna os dados do usuário dono da tarefa
     * Inner Join
     */
    public function usuario() {
        return $this->belongsTo('App\Models\Usuario');
    }

    /**
     * Altera para a imagem ser exibida com a URL inteira.
     */
    public function getImagemAttribute($value) {
        if (!empty($value))
            return url('storage/fotos/'.$value);
        return $value;
    }

    /**
     * Altera como a data deve ser exibida
     */
    public function getDataAttribute($value) {
        return date('d/m/Y', strtotime($value));
    }

}
