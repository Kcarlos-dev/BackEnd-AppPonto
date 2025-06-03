<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/addEmpregador',[ApiController::class,'addEmpregador']);
Route::get('/exibirEmpregador',[ApiController::class,'exibirEmpregador']);
Route::post('/addFuncionario', [ApiController::class,'addFuncionario']);
Route::get('/exibirFuncionario', [ApiController::class,'exibirFuncionario']);
Route::post('/enviarcolaborador',[ApiController::class,'enviar']);
Route::post('/ColaboradorExpecifico',[ApiController::class,'ColaboradorExpecifico']);
Route::post('/ReceberPonto', [ApiController::class,'ReceberPonto']);
Route::post('/GerarRelatorio', [ApiController::class,'GerarRelatorio']);
Route::post('/ExcluirColaborador', [ApiController::class,'Excluir']);


