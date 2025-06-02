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
        $nome = $request->nome;
        $email = $request->email;
        $cpf = $request->cpf;
        $integracao = $request->integracao;
        $senha = password_hash($request->senha, PASSWORD_DEFAULT);
        $funcao = $request->funcao;
        $empregador = $request->empregador;
        $ValidarEmail = DB::select('SELECT email FROM EMPREGADOR
                         WHERE email = (?)', [$empregador]);

        if (empty($ValidarEmail)) {
            return 'Email do empregador não consta no banco';
        }

        DB::insert('insert into FUNCIONARIOS (nome,email,senha,cpf,data_contratacao,funcao,EMPREGADOR) values(?,?,?,?,?,?,?)', [$nome, $email, $senha, $cpf, $integracao, $funcao, $empregador]);
        return "200 => dados recebidos com sucesso";
    }
    public function exibirColaborador(Request $request)
    {
        $email = '';
        $senha = '';

        $result = DB::select("SELECT email, senha FROM FUNCIONARIOS WHERE email =(?)", [$request->email]);
        foreach ($result as $i) {
            $email =  $i->email;
            $senha =  $i->senha;
        }
        $dados = [
            "email" => "$email",
            "senha" => password_verify($request->senha, $senha)
        ];

        return json_encode($dados);
    }
    public function enviar(Request $request)
    {
        $users = DB::select('SELECT * from FUNCIONARIOS
                                WHERE EMPREGADOR = (?)', [$request->email]);

        return json_encode($users);
    }
    public function ReceberPonto(Request $request)
    {
        $email = $request->Email;
        $hora = $request->Hora;
        $data = $request->Data;
        $cpf = $request->cpf;
        DB::insert('insert into TabelaPontos (email,hora,data_ponto,cpf) values(?,?,?,?)', [$email, $hora, $data, $cpf]);

        return "200 => dados recebidos com sucesso";
    }

    public function GerarRelatorio(Request $request)
    {
        $email = $request->email;
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

        return json_encode($result);
    }
    public function ColaboradorExpecifico(Request $request)
    {
        $email = $request->email;
        $result = DB::select("
        SELECT * FROM FUNCIONARIOS
        WHERE
        email = (?)
    ", [$email]);

        return json_encode($result);
    }
    public function Excluir(Request $request)
    {
        $email = $request->email;
        $cpf = $request->cpf;
        $result = DB::delete(
            "
        DELETE FROM FUNCIONARIOS
        WHERE email = (?)
        AND cpf = (?)",
            [$email, $cpf]
        );

        if ($result == 0) {
            $result = "Nada";
        };

        return $result;
    }
}
