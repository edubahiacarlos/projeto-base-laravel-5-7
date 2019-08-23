<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Perfil;
use App\User;
use App\PerfilFuncionalidadeAcao;
use App\ModelPadrao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
    }
    
    public function index(Request $request) {
        return Perfil::buscaTodosPerfisPorPagina(10);
    }

    public function show($id) {
   
        try {
            return response()->json(Perfil::buscaPerfilPorId($id));
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            PerfilFuncionalidadeAcao::where('perfil_id', '=', $id)->delete();
            Perfil::where('id', '=', $id)->delete();
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }

    function sanitizeString($str) {
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        $str = preg_replace('/[^a-z0-9]/i', '_', $str);
        $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
        return strtolower($str);
    }

    public function store(Request $request){
        $perfil = $request->input();

        $funcionalidades = $perfil['funcionalidades'];

        $perfil['slug'] = $this->sanitizeString($perfil['nome']);
      
        $perfil = ModelPadrao::salvar($perfil, new Perfil());
       
        foreach($funcionalidades as $funcionalidade) {
            DB::table('perfil_funcionalidade_acao')
                ->where('perfil_id', '=', $perfil->id)
                ->where('funcionalidade_id', '=', $funcionalidade['id'])
                ->delete();

         //   dd($funcionalidade['acoesSelecionadas']);
            foreach($funcionalidade['acoesSelecionadas'] as $acao) {
                $perfilFuncionalidadeAcao['perfil_id'] = $perfil['id'];
                $perfilFuncionalidadeAcao['funcionalidade_id'] = $funcionalidade['id'];
                $perfilFuncionalidadeAcao['acao_id'] = $acao['id'];

                ModelPadrao::salvar($perfilFuncionalidadeAcao, new PerfilFuncionalidadeAcao());
            }
        }

        try {
            return response()->json(['mensagem' => 'Perfil salva com sucesso', 'perfil' => $perfil ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request) {
     
        $perfil = $request->input();

        $funcionalidades = $perfil['funcionalidades'];

        $perfil['slug'] = $this->sanitizeString($perfil['nome']);
      
        $perfil = ModelPadrao::salvar($perfil, new Perfil());

        foreach($funcionalidades as $funcionalidade) {
            DB::table('perfil_funcionalidade_acao')
                ->where('perfil_id', '=', $perfil->id)
                ->where('funcionalidade_id', '=', $funcionalidade['id'])
                ->delete();

         //   dd($funcionalidade['acoesSelecionadas']);
            foreach($funcionalidade['acoesSelecionadas'] as $acao) {
                $perfilFuncionalidadeAcao['perfil_id'] = $perfil['id'];
                $perfilFuncionalidadeAcao['funcionalidade_id'] = $funcionalidade['id'];
                $perfilFuncionalidadeAcao['acao_id'] = $acao['id'];

                ModelPadrao::salvar($perfilFuncionalidadeAcao, new PerfilFuncionalidadeAcao());
            }
        }

        try {
            return response()->json(['mensagem' => 'Perfil atualizada com sucesso', 'perfil' => $perfil ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function verificaAutorizacao($funcionalidade) {
        return response()->json(Perfil::autorizado($funcionalidade, Auth::user()->id));
    }

    public function dadosDominio() {
        return Perfil::todos();
    }
}
