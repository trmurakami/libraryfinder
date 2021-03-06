<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
//use App\Models\Curriculum;
use App\Models\CreativeWork;
use App\Models\Person;
use App\Http\Controllers\PersonController;

class ImportLattesXMLController extends Controller
{

    function processAuthorsLattes($authors_array, $recordID)
    {
        foreach ($authors_array as $author) {
            $author = get_object_vars($author);
            $person = new PersonController;
            $result_person_query = $person->searchByName($author['@attributes']['NOME-COMPLETO-DO-AUTOR']);
            if (count(json_decode($result_person_query)) == 1) {
                $result_array = json_decode($result_person_query, true);                
                $id_author = $result_array[0]['id'];
            } else {
                $id_author = DB::table('people')->insertGetId(
                    [
                        'name' => $author['@attributes']['NOME-COMPLETO-DO-AUTOR']
                    ]
                );
            }
            
            // $array_result['doc']['author'][$i]['person']['name'] = $autor['@attributes']['NOME-COMPLETO-DO-AUTOR'];
            // $array_result['doc']['author'][$i]['nomeParaCitacao'] = $autor['@attributes']['NOME-PARA-CITACAO'];
            // $array_result['doc']['author'][$i]['ordemDeAutoria'] = $autor['@attributes']['ORDEM-DE-AUTORIA'];
            // if (isset($autor['@attributes']['NRO-ID-CNPQ'])) {
            //     $array_result['doc']['author'][$i]['nroIdCnpq'] = $autor['@attributes']['NRO-ID-CNPQ'];
            // }

            $record = CreativeWork::find($recordID);
            $author = Person::find($id_author);
            $record->authors()->attach($author, ['function' => 'Author']);

        }
    }

    public function parse(Request $request)
    {
        if ($request->file) {
            $cv = simplexml_load_file($request->file);
            $person = new PersonController;
            $result_person_query = $person->searchByName((string)$cv->{'DADOS-GERAIS'}->attributes()->{'NOME-COMPLETO'});
            if (count(json_decode($result_person_query)) == 0) {
                $id_author = DB::table('people')->insertGetId(
                    [
                        'name' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'NOME-COMPLETO'},
                        'lattesID' => (string)$cv->attributes()->{'NUMERO-IDENTIFICADOR'},
                        'orcidID' => (string)$cv->{'DADOS-GERAIS'}->attributes()->{'ORCID-ID'}
                    ]
                );
            }

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

                    $creative_work = new CreativeWorkController;
                    $result_cw_query = $creative_work->dedup($dadosBasicosDoTrabalho);

                    if (count(json_decode($result_cw_query)) == 0) {

                        $id = DB::table('creative_works')->insertGetId(
                            [
                                'countryOfOrigin' => $dadosBasicosDoTrabalho['@attributes']["PAIS-DO-EVENTO"],
                                'datePublished' => $dadosBasicosDoTrabalho['@attributes']["ANO-DO-TRABALHO"],
                                'doi' => $dadosBasicosDoTrabalho['@attributes']["DOI"],
                                'educationEvent_name' => $detalhamentoDoTrabalho['@attributes']["NOME-DO-EVENTO"],
                                'inLanguage' => $dadosBasicosDoTrabalho['@attributes']["IDIOMA"],
                                'isPartOf_isbn' => $detalhamentoDoTrabalho['@attributes']["ISBN"],
                                'isPartOf_issueNumber' => $detalhamentoDoTrabalho['@attributes']["FASCICULO"],
                                'isPartOf_serieNumber' => $detalhamentoDoTrabalho['@attributes']["SERIE"],
                                'isPartOf_name' => $detalhamentoDoTrabalho['@attributes']["TITULO-DOS-ANAIS-OU-PROCEEDINGS"],
                                'isPartOf_volumeNumber' => $detalhamentoDoTrabalho['@attributes']["VOLUME"],
                                'locationCreated' => $detalhamentoDoTrabalho['@attributes']["CIDADE-DO-EVENTO"],
                                'name' => $dadosBasicosDoTrabalho['@attributes']["TITULO-DO-TRABALHO"],
                                'pageEnd' => $detalhamentoDoTrabalho['@attributes']["PAGINA-FINAL"],
                                'pageStart' => $detalhamentoDoTrabalho['@attributes']["PAGINA-INICIAL"],
                                'publisher_organization_location' => $detalhamentoDoTrabalho['@attributes']["CIDADE-DA-EDITORA"],
                                'publisher_organization_name' => $detalhamentoDoTrabalho['@attributes']["NOME-DA-EDITORA"],
                                'record_source' => 'Curr??culo Lattes',
                                'type' => 'Trabalho apresentado em evento',
                                'type_schema_org' => 'ScholarlyArticle',
                                'url' => $dadosBasicosDoTrabalho['@attributes']["HOME-PAGE-DO-TRABALHO"]
                            ]
                        );
                        if (!empty($obra["AUTORES"])) {
                            $this->processAuthorsLattes($obra["AUTORES"], $id);
                        }
                    }
                }
            }

            if (isset($cv->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'})) {
                $records = $cv->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'}->{'ARTIGO-PUBLICADO'};
                foreach ($records as $obra) {
                    $obra = get_object_vars($obra);
                    $dadosBasicosDoTrabalho = get_object_vars($obra["DADOS-BASICOS-DO-ARTIGO"]);
                    $detalhamentoDoTrabalho = get_object_vars($obra["DETALHAMENTO-DO-ARTIGO"]);

                    $creative_work = new CreativeWorkController;
                    $result_cw_query = $creative_work->dedup($dadosBasicosDoTrabalho);

                    if (count(json_decode($result_cw_query)) == 0) {

                        $id = DB::table('creative_works')->insertGetId(
                            [
                                'countryOfOrigin' => $dadosBasicosDoTrabalho['@attributes']["PAIS-DE-PUBLICACAO"],
                                'datePublished' => $dadosBasicosDoTrabalho['@attributes']["ANO-DO-ARTIGO"],
                                'doi' => $dadosBasicosDoTrabalho['@attributes']["DOI"],
                                'inLanguage' => $dadosBasicosDoTrabalho['@attributes']["IDIOMA"],
                                'isPartOf_issn' => $detalhamentoDoTrabalho['@attributes']["ISSN"],
                                'isPartOf_name' => $detalhamentoDoTrabalho['@attributes']["TITULO-DO-PERIODICO-OU-REVISTA"],
                                'isPartOf_issueNumber' => $detalhamentoDoTrabalho['@attributes']["FASCICULO"],
                                'isPartOf_serieNumber' => $detalhamentoDoTrabalho['@attributes']["SERIE"],
                                'isPartOf_volumeNumber' => $detalhamentoDoTrabalho['@attributes']["VOLUME"],
                                'locationCreated' => $detalhamentoDoTrabalho['@attributes']["LOCAL-DE-PUBLICACAO"],
                                'name' => strip_tags($dadosBasicosDoTrabalho['@attributes']["TITULO-DO-ARTIGO"]),
                                'pageEnd' => $detalhamentoDoTrabalho['@attributes']["PAGINA-FINAL"],
                                'pageStart' => $detalhamentoDoTrabalho['@attributes']["PAGINA-INICIAL"],
                                'record_source' => 'Curr??culo Lattes',
                                'type' => 'Artigo publicado',
                                'type_schema_org' => 'ScholarlyArticle',
                                'url' => $dadosBasicosDoTrabalho['@attributes']["HOME-PAGE-DO-TRABALHO"]
                            ]
                        );
                        if (!empty($obra["AUTORES"])) {
                            $this->processAuthorsLattes($obra["AUTORES"], $id);
                        }
                        
                    }
                }
            }
            return response()->json(['success' => 'Arquivo XML processado com sucesso'], 200);
        }

        return response()->json(['error' => 'N??o foi enviado nenhum Arquivo XML'], 500);
    }



}
