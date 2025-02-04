<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php 
 function calcula_risco($risco,$retornar_css){
            if ($risco>=0 && $risco<1) {
                $retorno="Irrelevante";
                $retorno_css = "irrelevante";
            }

            if ($risco>=1 && $risco<2) {
                $retorno="Baixo";
                $retorno_css = "baixo";
            }            
            if ($risco>=2 && $risco<3) {
                $retorno="Médio";
                $retorno_css = "medio";
            }            
            if ($risco>=3 && $risco<4) {
                $retorno="Alto";
                $retorno_css = "alto";
            }            
            if ($risco>=4 && $risco<=5) {
                $retorno="Crítico";
                $retorno_css = "critico";
            }       

            if ($retornar_css==1) { return $retorno_css; } 
                            else { return $retorno; } 
        }
?>
<style type='text/css'>
            <!--
                    table.page_header {width: 100%; border: none; background-color: white; border-bottom: solid 1mm #FFFFFF; padding: 2mm }
                    table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1mm #COCOCO; padding: 2mm}
                    h1 {color: #000033}
                    h2 {color: #000055}
                    h3 {color: #000077}
                    div.standard{ padding-left: 5mm;}
                    .tabela_acao{width: 750px; margin-top:5px; 
                                border: 3px solid #858585; background-color: #E8E8E8; padding: 2mm}
                    .tabela_acao2{width: 750px; margin-top:5px;border-bottom: 1px solid #000000; border-right: 1px solid #000000;}                    
                    .th_acao {padding:5px; background-color: #EFEFEF; font-size: 14px; vertical-align:middle; text-align:center; font-weight: bold; border-top: 1px solid #000000; border-left: 1px solid #000000;}
                    .td_acao {padding:5px; font-size: 14px; vertical-align:top; text-align:justify;  border-top: 1px solid #000000; border-left: 1px solid #000000; }
                    .tabela_atributo2 {  margin-left:auto; margin-right:auto; }                    
                    .tabela_atributo {border: 1px solid #444;  margin-left:auto; margin-right:auto; }
                    .page { font-size: 15px; 
                              margin-top: 5px;
                              font-weight: bold; 
                              }        
                    .nivel0 { font-size: 20px; 
                              color:#0E2F62; 
                              background-color: #CCCCCC; 
                              width:100%; 
                              margin-top: 20px;
                              font-weight: bold; 
                              }
                    .nivel1 { font-size: 15px; 
                              color:#0E2F62; 
                              margin-top: 20px;
                              font-weight: bold;                                   
                              }     
                    p, P {text-indent: 12.5mm; line-height:170%; margin: 0 0; font-family:Arial; font-size:12px; }
                    .th {background-color: #DBDBDB; padding:5px; font-size: 10px; vertical-align:middle; text-align:center;}
                    .even {background-color: #E8E8E8; padding:3px; font-size: 10px;   }                   
                    .odd {background-color: #FFFFFF; padding:3px; font-size: 10px;   }                           
                    .risco_irrelevante {
                        background-color: #86B686;
                        height:16px;  
                        font-size: 10px;
                    }

                    .risco_baixo {
                        background-color: #B7D4B7;
                        height:16px; 
                        font-size: 10px;
                    }

                    .risco_medio {
                        background-color: #FFEDA5;
                        height:16px;
                        font-size: 10px;
                    }

                    .risco_alto {
                        background-color: #FFD089;
                        height:16px;
                        font-size: 10px;
                    }

                    .risco_critico {
                        background-color: #CB7C7C;
                        height:16px;
                        font-size: 10px;
                    }          
            -->


            </style>

<?php 

// =================================================
//  MONTA O VETOR COM OS RISCOS CALCULADOS
//  DE ACORDO COM AS TABELAS DE SUBRISCO
// =================================================
$exercicio=2012;


        $model_processo = new Processo;
        $model = new Subrisco;
        $model_criterio_dados = Criterio::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio), array('order'=>'nome_criterio'));
        $model_processo_dados = Processo::carrega_tabela_risco($exercicio);
            
        if (!empty($model_criterio_dados)) { $criterio_ok = 1; }
        if (!empty($model_processo_dados)) { $acao_ok = 1; }            

       
        if ($criterio_ok and $acao_ok) {

            // conta os tipos de ação para gerar o rowspan
            foreach ($model_processo_dados as $vetor) {
                $tipos_de_acao[$vetor[tipo_processo_fk]]++;
            }

            // calcula soma dos pesos para fazer média composta
            $peso_total = 0;
            if (is_array($model_criterio_dados)) {
                foreach ($model_criterio_dados as $vetor) {
                    $peso_total +=$vetor->valor_peso;
                }
            }

            // calcula totais dos itens
            $contador = 1;
            foreach ($model_processo_dados as $vetor) {
                foreach ($model_criterio_dados as $vetor_criterio) {
                    $nota = Risco::model()->RecuperaNota($vetor[id], $vetor_criterio->id);
                    $vetor_soma[$contador]+= ($nota * $vetor_criterio->valor_peso);
                }
                $vetor_risco_total[$vetor[id]] = round($vetor_soma[$contador] / $peso_total, 2);
                $contador++;
            }
        }        


































$acoes = Acao::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio));


if (sizeof($acoes)>0){
    foreach ($acoes as $vetor_acoes){
        $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$vetor_acoes->especie_auditoria_fk));
        
        // pega as Unidades Regionais associadas à ação
        $unidadeAdministrativa_todas=null;
        $acao_sureg= AcaoSureg::model()->findAllByAttributes(array('acao_fk'=>$vetor_acoes->id));        
        foreach ($acao_sureg as $vetor_acao_sureg){
            $unidadeAdministrativa= UnidadeAdministrativa::model()->findByAttributes(array('id'=>$vetor_acao_sureg->unidade_administrativa_fk));
            $unidadeAdministrativa_todas[] = $unidadeAdministrativa->sigla;
        }
       
        
        // pega so riscos identificados e alternativas de mitigação
        $riscopre_todos=null;
        $contador=1; 
        $acao_riscopre= AcaoRiscoPre::model()->findAllByAttributes(array('acao_fk'=>$vetor_acoes->id));        
        foreach ($acao_riscopre as $vetor_acao_riscopre){
            $riscopre= RiscoPre::model()->findByAttributes(array('id'=>$vetor_acao_riscopre->risco_pre_fk));
            $riscopre_todos[$contador]['nome_risco'] = $riscopre->nome_risco;
            $riscopre_todos[$contador]['descricao_mitigacao'] = $riscopre->descricao_mitigacao;
            $contador++;
        }        

        
        echo  "<div class='tabela_atributo2'><font size=4><b>ESPÉCIE DE AUDITORIA: ". strtoupper($especie_auditoria->nome_auditoria . " - " . $especie_auditoria->sigla_auditoria) . "</b></font>";

        echo " <table class='tabela_acao'><tr>
               <td><b>APRESENTAÇÃO:</b><br>".
                str_replace("\n","<br>",$vetor_acoes->descricao_apresentacao) .                  
               "</td></tr></table>
                <div style='height:10px;'></div>";
        
        echo " <table class='tabela_acao'><tr>
               <td><b>AÇÃO DE AUDITORIA:</b><br>".
                str_replace("\n","<br>",$vetor_acoes->nome_acao). "<br>";
        
                // escreve uma sub-tabela com as Unidades Regionais
                echo "<table border=0 align=center><tr>
                    <td align=right valign=top><b>Locais:</b> </td>
                    <td>";
                $contador=1;
                foreach ($unidadeAdministrativa_todas as $vet){
                    echo "- " .$vet . "<br>";
                    $contador++;
                    if ($contador==3) { 
                        echo "</td><td width=15></td><td>";
                        $contador==1; 
                    }
                }
                echo "</td></tr></table><br>
                     <b>OBJETIVOS ESTRATÉGICOS:</b><br>".
                          str_replace("\n","<br>",$vetor_acoes->descricao_objetivo_estrategico). "<br>";

                
                
               echo "</td></tr></table>
                   <table class='tabela_acao2'>
                   <thead><tr>
                   <th class='th_acao' width=150>OBJETIVO DA <br>AUDITORIA</th>
                   <th class='th_acao'>ESCOPO</th>
                   <th class='th_acao'>REPRESENTATIVIDADE / AMPLITUDE</th>
                   <th class='th_acao'>ORIGEM DA <BR> DEMANDA</th>
                   </tr></thead>
                   
                   <tbody>
                   <tr>
                   <td class='td_acao'  width=150>". str_replace("\n","<br>",$vetor_acoes->descricao_objetivo)."</td>
                   <td class='td_acao'>". str_replace("\n","<br>",$vetor_acoes->descricao_escopo)."</td>
                   <td class='td_acao'>". str_replace("\n","<br>",$vetor_acoes->descricao_representatividade)."</td>
                   <td class='td_acao' align=center>". str_replace("\n","<br>",$vetor_acoes->descricao_origem)."</td>
                   </tr>
                   
                   <tr>
                   <td class='td_acao'><b>GRAU DE RISCOS</b></td>";
                   
                   if ($vetor_risco_total[$vetor_acoes->processo_fk]){
                       echo "<td colspan=3 class='risco_".calcula_risco($vetor_risco_total[$vetor_acoes->processo_fk],1)."' style='border-top: 1px solid #000000; border-left: 1px solid #000000;'  align=left>&nbsp;<font size=4><b>". calcula_risco($vetor_risco_total[$vetor_acoes->processo_fk],0). " - " . str_replace(".",",",$vetor_risco_total[$vetor_acoes->processo_fk]) ."</b></font></td>"; 
                   } else {
                       echo "<td colspan=3 class='td_acao'>&nbsp;</td>";
                   }
                     
                       
                       echo "</td>
                   </tr>
                   
                   <tr>
                   <td class='td_acao'><b>RISCOS <br>PRÉ-IDENTIFICADOS</b></td>
                   <td colspan=3 class='td_acao'> ";
                        $cont=1; 
                       foreach($riscopre_todos as $vetor_riscopre){
                           echo $cont. ") " . $vetor_riscopre[nome_risco] . "<br>";
                           $cont++; 
                       }
                   echo "</td>
                   </tr>
                   
                   <tr>
                   <td class='td_acao'><b>ALTERNATIVAS <br>DE MITIGAÇÃO</b></td>
                   <td colspan=3 class='td_acao'>";
                   
                       $cont=1; 
                       foreach($riscopre_todos as $vetor_riscopre){
                           echo $cont. ") " . $vetor_riscopre[descricao_mitigacao] . "<br>";
                           $cont++; 
                       }                   
                   
                   echo "</td>
                   </tr> 
                   
                   <tr>
                   <td colspan=4 class='td_acao'><b>RESULTADOS ESPERADOS:</b><Br>".
                         str_replace("\n","<br>",$vetor_acoes->descricao_resultados)."
                   </td>
                   </tr>
                   
                   <tr>
                   <td colspan=4 class='td_acao'><b>CONHECIMENTOS ESPECÍFICOS REQUERIDOS:</b><br>".
                       str_replace("\n","<br>",$vetor_acoes->descricao_conhecimentos)."
                   </td>
                   </tr>
                   
                   </tbody>
                   </table>";
        
        echo "</div> <br>";
        exit;        
    }
    
    
}

exit;?>
