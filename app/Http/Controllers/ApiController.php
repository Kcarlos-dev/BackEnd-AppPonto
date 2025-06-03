<?php

namespace App\Http\Controllers;

use App\Models\Empregado;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Empregador;
use App\Models\Funcionario;
use App\Models\TabelaPonto;
use Illuminate\Support\Facades\Log;
use Throwable;



class ApiController extends Controller
{
    public function addEmpregador(Request $request)
    {
        try {
            $nome = $request->nome;
            $email = $request->email;
            $cpf = $request->cpf;
            $senha = password_hash($request->senha, PASSWORD_DEFAULT);
            $empresa = $request->empresa;

            if (
                strlen($nome) <= 0
                || strlen($email) <= 0
                || strlen($cpf) <= 0
                || strlen($senha) <= 0
                || strlen($empresa) <= 0
            ) {
                return response()->json(['message' => "Todos os campos devem ser preenchidos"], 400);
            }

            $empregador = new Empregador();
            if ($empregador::where('cpf', $cpf)->exists()) {
                return response()->json([
                    'erro' => 'email já registrado.'
                ], 422);
            }
            if ($empregador::where('email', $email)->exists()) {
                return response()->json([
                    'erro' => 'CPF já registrado.'
                ], 422);
            }


            $empregador->nome = $nome;
            $empregador->email = $email;
            $empregador->cpf = $cpf;
            $empregador->senha = $senha;
            $empregador->empresa = $empresa;
            $empregador->save();

            //DB::insert('insert into EMPREGADOR (nome,email,cpf,senha,empresa) values(?,?,?,?,?)',[$nome,$email,$cpf,$senha,$empresa]);

            return response()->json(['message' => 'Empregador cadastrado com sucesso!!', 200]);
        } catch (Throwable $e) {
            Log::error("Erro addEmpregador: " . $e->getMessage());
            return response()->json(["message" => "Erro interno"], 500);
        }
    }
    public function exibirEmpregador(Request $request)
    {
        try {
            $email = '';
            $senha = '';

            $empregador = new Empregador();

            $result = $empregador::select('email', 'senha')
                ->where('email', $request->query('email'))
                ->first();

            if ($result) {
                $email = $result->email;
                $senha = $result->senha;

                $dados = [
                    "email" => $email,
                    "senha" => password_verify($request->query('senha'), $senha)
                ];
            } else {
                $dados = [
                    "email" => null,
                    "senha" => false
                ];
            }

            return response()->json($dados);
        } catch (Throwable $e) {
            Log::error("Erro exibirEmpregador: " . $e->getMessage());
            return response()->json(["message" => "Erro interno"], 500);
        }
    }
    public function addFuncionario(Request $request)
    {
        try {
            $nome = $request->nome;
            $email = $request->email;
            $cpf = $request->cpf;
            $integracao = $request->integracao;
            $senha = password_hash($request->senha, PASSWORD_DEFAULT);
            $funcao = $request->funcao;
            $empresa = $request->empresa;


            if (
                strlen($nome) <= 0
                || strlen($email) <= 0
                || strlen($cpf) <= 0
                || strlen($senha) <= 0
                || strlen($integracao) <= 0
                || strlen($funcao) <= 0
                || strlen($empresa) <= 0
            ) {
                return response()->json(['message' => "Todos os campos devem ser preenchidos"], 400);
            }


            $funcionario = new Funcionario();
            $empregador = new Empregador();


            if (!$empregador::where('empresa', $empresa)->exists()) {
                return response()->json(
                    ['message' => 'Empregador não consta no banco'],
                    422
                );
            }

            $funcionario->cpf = $cpf;
            $funcionario->email = $email;
            $funcionario->nome = $nome;
            $funcionario->senha = $senha;
            $funcionario->EMPREGADOR = $empresa;
            $funcionario->data_contratacao = $integracao;
            $funcionario->funcao = $funcao;
            $funcionario->save();

            //DB::insert('insert into FUNCIONARIOS (nome,email,senha,cpf,data_contratacao,funcao,EMPREGADOR) values(?,?,?,?,?,?,?)', [$nome, $email, $senha, $cpf, $integracao, $funcao, $empregador]);
            return response()->json(["massage" => "Funcionario cadastrado com sucesso"], 200);
        } catch (Throwable $e) {
            Log::error("Erro addFuncionario: " . $e->getMessage());
            return response()->json(["message" => "Erro interno"], 500);
        }
    }
    public function exibirFuncionario(Request $request)
    {
        try {
            $email = '';
            $senha = '';

            $funcionario = new Funcionario();

            $result = $funcionario::select('email', 'senha')
                ->where('email', $request->query('email'))
                ->first();

            if ($result) {
                $email = $result->email;
                $senha = $result->senha;

                $dados = [
                    "email" => $email,
                    "senha" => password_verify($request->query('senha'), $senha)
                ];
            } else {
                $dados = [
                    "email" => null,
                    "senha" => false
                ];
            }

            return response()->json($dados);
        } catch (Throwable $e) {
            Log::error("Erro exibirFuncionario: " . $e->getMessage());
            return response()->json(["message" => "Erro interno"], 500);
        }
    }
    public function enviar(Request $request)
    {
        $usuarios = Funcionario::where('EMPREGADOR', $request->empresa)->get();
        return response()->json($usuarios);
    }
    public function ReceberPonto(Request $request)
    {
        TabelaPonto::create([
            'email' => $request->Email,
            'hora' => $request->Hora,
            'data_ponto' => $request->Data,
            'cpf' => $request->cpf,
        ]);

        return response()->json(['message' => 'Dados recebidos com sucesso'], 200);
    }


    public function GerarRelatorio(Request $request)
    {
         return response()->json(['message' => 'teste'], 200);
      /*  $email = $request->email;
        $data = $request->data;
        if ($data == "") {
            $data = '';
        };

        $result = DB::select("CALL REL_HORARIOS_FUNCIONARIOS (?,?)", [$email, $data]);
        if (empty($result)) {
            $result = [
                [
                    "email" => "Sem dados do dia",
                    "nome" => "Sem dados do dia",
                    "cpf" => "Sem dados do dia",
                    "hora" => "Sem dados do dia",
                    "data_ponto" => "Sem dados do dia",
                ]
            ];
        }

        return json_encode($result);*/
    }
    public function ColaboradorExpecifico(Request $request)
    {
        $colaborador = Funcionario::where('email', $request->email)->get();
        return response()->json($colaborador);
    }

    public function Excluir(Request $request)
    {
        $deleted = Funcionario::where('email', $request->email)
            ->where('cpf', $request->cpf)
            ->delete();

        return $deleted ? $deleted : 'Nada';
    }
}
