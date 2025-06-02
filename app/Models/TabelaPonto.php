<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelaPonto extends Model
{
    use HasFactory;

    protected $fillable = [
            'cpf',
            'email',
            'nome',
            'senha',
            'EMPREGADOR',
            'data_contratacao',
            'funcao'
    ];

}
