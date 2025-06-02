<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empregador extends Model
{
    use HasFactory;
    protected $table = 'empregadores';
    protected $fillable = [
        'senha',
        'email',
        'cpf',
        'nome',
        'empresa'
    ];
}
