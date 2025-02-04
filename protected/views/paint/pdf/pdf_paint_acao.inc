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
 
        // =================================================
        //  MONTA O VETOR COM OS RISCOS CALCULADOS
        //  DE ACORDO COM AS TABELAS DE SUBRISCO
        // =================================================
        $exercicio = $_GET["exercicio"];

        $model_processo = new Processo;
        $model = new Subrisco;
        $model_criterio_dados = Criterio::model()->findAllByAttributes(array('valor_exercicio' => $exercicio), array('order' => 'nome_criterio'));
        $model_processo_dados = Processo::carrega_tabela_risco($exercicio);

        if (!empty($model_criterio_dados)) {
            $criterio_ok = 1;
        }
        if (!empty($model_processo_dados)) {
            $acao_ok = 1;
        }


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


        $acoes = Acao::model()->findAllByAttributes(array('valor_exercicio' => $exercicio),array('order'=>'valor_exercicio,numero_acao'));


        if (sizeof($acoes) > 0) {
            foreach ($acoes as $vetor_acoes) {
                $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id' => $vetor_acoes->especie_auditoria_fk));

                // pega as suregs associadas à ação
                $unidadeAdministrativa_todas = null;
                $acao_sureg = AcaoSureg::model()->findAllByAttributes(array('acao_fk' => $vetor_acoes->id));
                foreach ($acao_sureg as $vetor_acao_sureg) {
                    $unidadeAdministrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor_acao_sureg->unidade_administrativa_fk));
                    $unidadeAdministrativa_todas[] = $unidadeAdministrativa->sigla;
                }


                // pega so riscos identificados e alternativas de mitigação
                $riscopre_todos = null;
                $contador = 1;
                $processo_riscopre = ProcessoRiscoPre::model()->findAllByAttributes(array('processo_fk' => $vetor_acoes->processo_fk));
                foreach ($processo_riscopre as $vetor_processo_riscopre) {
                    $riscopre = RiscoPre::model()->findByAttributes(array('id' => $vetor_processo_riscopre->risco_pre_fk));
                    $riscopre_todos[$contador]['nome_risco'] = $riscopre->nome_risco;
                    $riscopre_todos[$contador]['descricao_mitigacao'] = $riscopre->descricao_mitigacao;
                    $contador++;
                }
 
                $html.="<div>";
                $html.= "<div class='tabela_atributo2'><span style='font-size:17px;'><b>ESPÉCIE DE AUDITORIA: " . Paint::model()->acao_strtoupper($especie_auditoria->nome_auditoria . " - " . $especie_auditoria->sigla_auditoria) . "</b></span>";

                $html.=" <table class='tabela_acao'>
                    <col width=670></col>
                    <tr>
                       <td class='texto_acao' align=justify><b>APRESENTAÇÃO:</b><br>" .
                str_replace("\n", "<br>", $vetor_acoes->descricao_apresentacao) .
                "</td></tr></table>
                      ";
                
                $html.= " <table><tr><td></td></tr></table> <table class='tabela_acao'>
                    <col width=670></col>
                    <tr>
                       <td width=670 class='texto_acao' align=justify><b>AÇÃO DE AUDITORIA:</b><br>".
                        $vetor_acoes->numero_acao ." - " .
                str_replace("\n", "<br>", $vetor_acoes->nome_acao) . "<br>";

                // escreve uma sub-tabela com as suregs
                $html.= "<table border=0 align=center><tr>
                            <td class='texto_acao'>";
                $contador = 1;
                $contador_acao=1;
                if (sizeof($unidadeAdministrativa_todas)){ 
	                foreach ($unidadeAdministrativa_todas as $vet) {
	                    $html.= $vetor_acoes->numero_acao  . ".". $contador_acao ." - " . $vet . "<br>";
	                    $contador++;
	                    if ($contador == 3) {
	                        $html.= "</td><td width=15></td><td>";
	                        $contador = 1;
	                    }
	                    $contador_acao++;
	                }
                }
                $html.= "</td></tr></table><br>
                             <b>OBJETIVOS ESTRATÉGICOS:</b><br>" .
                str_replace("\n", "<br>", $vetor_acoes->descricao_objetivo_estrategico) . "<br>";



                $html.= "</td></tr></table> <table><tr><td></td></tr></table>
                           <table class='tabela_acao2'>
                           <col width=115></col>
                           <col width=210></col>
                           <col width=210></col>
                           <col width=65></col>
                           <thead><tr>
                           <th class='th_acao' >OBJETIVO DA <br>AUDITORIA</th>
                           <th class='th_acao' >ESCOPO</th>
                           <th class='th_acao' >REPRESENTATIVIDADE / AMPLITUDE</th>
                           <th class='th_acao' >ORIGEM DA <BR> DEMANDA</th>
                           </tr></thead>

                           <tbody>
                           <tr>
                           <td class='td_acao' >" . str_replace("\n", "<br>", $vetor_acoes->descricao_objetivo) . "</td>
                           <td class='td_acao' >" . str_replace("\n", "<br>", $vetor_acoes->descricao_escopo) . "</td>
                           <td class='td_acao' >" . str_replace("\n", "<br>", $vetor_acoes->descricao_representatividade) . "</td>
                           <td class='td_acao' >" . str_replace("\n", "<br>", $vetor_acoes->descricao_origem) . "</td>
                           </tr>

                           <tr>
                           <td class='td_acao'><b>GRAU DE RISCOS</b></td>";

                if ($vetor_risco_total[$vetor_acoes->processo_fk]) {
                    $html.= "<td colspan=3 class='risco_" . $this->calcula_risco($vetor_risco_total[$vetor_acoes->processo_fk], 1) . "' style='border-top: 1px solid #000000; border-left: 1px solid #000000;'  align=left>&nbsp;<span style='font-size:14px;'><b>" . $this->calcula_risco($vetor_risco_total[$vetor_acoes->processo_fk], 0) . " - " . str_replace(".", ",", $vetor_risco_total[$vetor_acoes->processo_fk]) . "</b></span></td>";
                } else {
                    $html.= "<td colspan=3 class='td_acao'>&nbsp;</td>";
                }

                $html.= "</tr>";
                
				if (sizeof($riscopre_todos)){
	                $html.= "
	                           <tr>
	                           <td class='td_acao'><b>RISCOS <br>PRÉ-IDENTIFICADOS</b></td>
	                           <td colspan=3 class='td_acao'> ";
	                $cont = 1;
	                foreach ($riscopre_todos as $vetor_riscopre) {
	                    $html.= $cont . ") " . $vetor_riscopre[nome_risco] . "<br>";
	                    $cont++;
	                }
	                $html.= "</td>
	                           </tr>
	
	                           <tr>
	                           <td class='td_acao'><b>ALTERNATIVAS <br>DE MITIGAÇÃO</b></td>
	                           <td colspan=3 class='td_acao'>";
	
	                $cont = 1;
	                foreach ($riscopre_todos as $vetor_riscopre) {
                            if ($vetor_riscopre[descricao_mitigacao]){
                                $html.= $cont . ") " . $vetor_riscopre[descricao_mitigacao] . "<br>";
                                $cont++;
                            }
	                }
	                $html.= "</td>
                           </tr>";
				}
                $html.= "			
                           <tr>
                           <td colspan=4 class='td_acao'><b>RESULTADOS ESPERADOS:</b><Br>" .
                str_replace("\n", "<br>", $vetor_acoes->descricao_resultados) . "
                           </td>
                           </tr>

                           <tr>
                           <td colspan=4 class='td_acao'><b>CONHECIMENTOS ESPECÍFICOS REQUERIDOS:</b><br>" .
                str_replace("\n", "<br>", $vetor_acoes->descricao_conhecimentos) . "
                           </td>
                           </tr>

                           </tbody>
                           </table>";

                $html.= "</div></div> <br>";
            }
        }