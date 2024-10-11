<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class ApiController extends Controller
{
    public function addEmpregador(Request $request)
    {
        $nome =$request->nome;
        $email = $request->email;
        $cpf = $request->cpf;
        $senha = password_hash($request->senha,PASSWORD_DEFAULT);
        $empresa = $request->empresa;
        DB::insert('insert into EMPREGADOR (nome,email,cpf,senha,empresa) values(?,?,?,?,?)',[$nome,$email,$cpf,$senha,$empresa]);
        return "200 => dados recebidos com sucesso";
    }
    public function exibirEmpregador(Request $request){
        $email = '';
        $senha = '';

        $result = DB::select("SELECT email, senha FROM EMPREGADOR WHERE email =(?)",[$request->email]);
        foreach($result as $i){
           $email =  $i->email;
           $senha =  $i->senha;
        }
        $dados = [
            "email"=>"$email",
            "senha"=>password_verify($request->senha,$senha) 
        ];

        return json_encode($dados);
    }
       public function add(Request $request)
    {
        $nome =$request->nome;
        $email = $request->email;
        $cpf = $request->cpf;
        $integracao = $request->integracao;
        $senha = password_hash($request->senha,PASSWORD_DEFAULT) ;
        $funcao = $request->funcao;
        $empregador = $request->empregador;
        $ValidarEmail = DB::select('SELECT email FROM EMPREGADOR 
                         WHERE email = (?)',[$empregador]);
        if(empty($ValidarEmail)){
            return 'Email do empregador não consta no banco';
        }
                         
        DB::insert('insert into FUNCIONARIOS (nome,email,senha,cpf,data_contratacao,funcao,EMPREGADOR) values(?,?,?,?,?,?,?)',[$nome,$email,$senha,$cpf,$integracao,$funcao,$empregador]);
        return "200 => dados recebidos com sucesso";
    }
    public function exibirColaborador(Request $request){
        $email = '';
        $senha = '';

        $result = DB::select("SELECT email, senha FROM FUNCIONARIOS WHERE email =(?)",[$request->email]);
        foreach($result as $i){
           $email =  $i->email;
           $senha =  $i->senha;
        }
        $dados = [
            "email"=>"$email",
            "senha"=>password_verify($request->senha,$senha) 
        ];

        return json_encode($dados);
    }
    public function enviar(Request $request)
    {
        $users = DB::select('SELECT * from FUNCIONARIOS
                                WHERE EMPREGADOR = (?)',[$request->email]);
        
        return json_encode($users);
    }
    public function ReceberPonto(Request $request){
        $email = $request->Email;
        $hora = $request->Hora;
        $data = $request->Data;
        $cpf = $request->cpf;
        DB::insert('insert into TabelaPontos (email,hora,data_ponto,cpf) values(?,?,?,?)',[$email,$hora,$data,$cpf]);

        return "200 => dados recebidos com sucesso";
    }

  public function GerarRelatorio(Request $request){
    $email = $request->email;
    $data = $request->data;
    if($data == ""){
        $data = '';
    };

   $result = DB::select("CALL REL_HORARIOS_FUNCIONARIOS (?,?)",[$email,$data]); 
   if (empty($result)) {
    $result = [
        [
        "email"=>"Sem dados do dia",
        "nome"=>"Sem dados do dia",
        "cpf"=>"Sem dados do dia",
        "hora"=>"Sem dados do dia",
        "data_ponto"=>"Sem dados do dia",
        ]
    ];
}

    return json_encode($result);
}
public function ColaboradorExpecifico(Request $request){
    $email = $request->email;
    $result = DB::select("
        SELECT * FROM FUNCIONARIOS 
        WHERE
        email = (?)
    ", [$email]); 

    return json_encode($result);
}
public function Excluir(Request $request){
    $email = $request->email;
    $cpf = $request->cpf;
    $result = DB::delete("
        DELETE FROM FUNCIONARIOS
        WHERE email = (?)
        AND cpf = (?)"
 , [$email,$cpf]); 

    if( $result == 0){
        $result = "Nada";
    };

    return $result;
}
}
