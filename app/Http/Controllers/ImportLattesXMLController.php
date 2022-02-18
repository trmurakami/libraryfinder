<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
//use App\Models\Curriculum;
use App\Models\CreativeWork;
use App\Models\Person;

class ImportLattesXMLController extends Controller
{

    function processAuthorsLattes($authors_array, $recordID)
    {
        foreach ($authors_array as $author) {
            $author = get_object_vars($author);
            // $array_result['doc']['author'][$i]['person']['name'] = $autor['@attributes']['NOME-COMPLETO-DO-AUTOR'];
            // $array_result['doc']['author'][$i]['nomeParaCitacao'] = $autor['@attributes']['NOME-PARA-CITACAO'];
            // $array_result['doc']['author'][$i]['ordemDeAutoria'] = $autor['@attributes']['ORDEM-DE-AUTORIA'];
            // if (isset($autor['@attributes']['NRO-ID-CNPQ'])) {
            //     $array_result['doc']['author'][$i]['nroIdCnpq'] = $autor['@attributes']['NRO-ID-CNPQ'];
            // }

            $id_author = DB::table('people')->insertGetId(
                [
                    'name' => $author['@attributes']['NOME-COMPLETO-DO-AUTOR']
                ]
            );

            $record = CreativeWork::find($recordID);
            $author = Person::find($id_author);
            $record->authors()->attach($author, ['function' => 'Author']);

        }
    }

    public function parse(Request $request)
    {
        if ($request->file) {
            $cv = simplexml_load_file($request->file);
            // $existing_id = Curriculum::find((string)$cv->attributes()->{'NUMERO-IDENTIFICADOR'});

            // $curriculum = Curriculum::insertGetId(
            //     [
            //         'cidade_nascimento' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'CIDADE-NASCIMENTO'},
            //         'data_falecimento' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'DATA-FALECIMENTO'},
            //         'genero' => $request->genero,
            //         'lattes_id_13' => (string)$cv->attributes()->{'NUMERO-IDENTIFICADOR'},
            //         'nacionalidade' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'NACIONALIDADE'},
            //         'nome_completo' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'NOME-COMPLETO'},
            //         'numero_funcional' => $request->numero_funcional,
            //         'orcid_id' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'ORCID-ID'},
            //         'pais_de_nacionalidade' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'PAIS-DE-NACIONALIDADE'},
            //         'pais_de_nascimento' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'PAIS-DE-NASCIMENTO'},
            //         'ppg_nome' => $request->ppg_nome,
            //         'resumo_cv' => str_replace('"', '\"', (string)$cv->{'DADOS-GERAIS'}->{'RESUMO-CV'}->attributes()->{'TEXTO-RESUMO-CV-RH'}),
            //         'resumo_cv_en' => str_replace('"', '\"', (string)$cv->{'DADOS-GERAIS'}->{'RESUMO-CV'}->attributes()->{'TEXTO-RESUMO-CV-RH-EN'}),
            //         'sigla_pais_nacionalidade' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'SIGLA-PAIS-NACIONALIDADE'},
            //         'uf_nascimento' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'UF-NASCIMENTO'},
            //         'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')),
            //         'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))
            //     ]
            // );

            if (isset($cv->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'})) {
                $records = $cv->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'}->{'TRABALHO-EM-EVENTOS'};
                foreach ($records as $obra) {
                    $obra = get_object_vars($obra);
                    $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-TRABALHO"]);
                    $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-TRABALHO"]);
                    $id = DB::table('creative_works')->insertGetId(
                        [
                            'doi' => $dadosBasicosDoTrabalho['@attributes']["DOI"],
                            'name' => $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-TRABALHO"],
                            'record_source' => 'Currículo Lattes',
                            'type' => 'Trabalho apresentado em evento',
                            'type_schema_org' => 'ScholarlyArticle'
                        ]
                    );
                    if (!empty($obra["AUTORES"])) {
                        $this->processAuthorsLattes($obra["AUTORES"], $id);
                    }
                }
            }
            if (isset($cv->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'})) {
                $records = $cv->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'}->{'ARTIGO-PUBLICADO'};
                foreach ($records as $obra) {
                    $obra = get_object_vars($obra);
                    $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-ARTIGO"]);
                    $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-ARTIGO"]);
                    $id = DB::table('creative_works')->insertGetId(
                        [
                            'doi' => $dadosBasicosDoTrabalho['@attributes']["DOI"],
                            'name' => strip_tags($dadosBasicosDoTrabalho['@attributes']["TITULO-DO-ARTIGO"]),
                            'record_source' => 'Currículo Lattes',
                            'type' => 'Artigo publicado',
                            'type_schema_org' => 'ScholarlyArticle'
                        ]
                    );
                    if (!empty($obra["AUTORES"])) {
                        $this->processAuthorsLattes($obra["AUTORES"], $id);
                    }
                }
            }
            return response()->json(['success' => 'Arquivo XML processado com sucesso'], 200);
        }

        return response()->json(['error' => 'Não foi enviado nenhum Arquivo XML'], 500);
    }



}
