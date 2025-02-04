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
    $model_criterio_todos = Criterio::model()->findAllByAttributes(array('valor_exercicio' => $_GET["exercicio"]), array('order' => 'id'));
    if (sizeof($model_criterio_todos)>0){
            // gera a primeira tabela com os critérios e descrições
            $html.="<div><table><tr><td>&nbsp;</td></tr></table></div><br>
                <div><table border=0><tr><td align=center width=700><b>Anexo 1 - Descrição dos Critérios de Avaliação </b></td></tr></table>
                <table class='tabela_atributo'>
                ";            
            foreach ($model_criterio_todos as $criterio_todos) {
                $model_tipo_criterio_todos = TipoCriterio::model()->findByAttributes(array('id' => $criterio_todos->tipo_criterio_fk));               
                $html.="<tr><td  class='th' style='padding:10px;'><span style='font-size:12px;text-align:center;'><b>".mb_strtoupper( $model_tipo_criterio_todos->nome_criterio, 'ISO-8859-1' ) . "</b></span></td></tr> 
                        <tr><td class='odd' style='padding:10px;text-align:'justify' align=justify>".str_replace("\n","<br>",$criterio_todos->descricao_criterio)."</td></tr>";

            }            
            $html.="</table></div>";

            // pega todos os critérios para gerar as tabelas  de sub-risco
            $indice_anexo = 1;
            foreach ($model_criterio_todos as $criterio_todos) {
                $criterio = $criterio_todos->id;
                $tipo_criterio = Criterio::carrega_criterios_subrisco($criterio);
                $model_criterio_dados = Subcriterio::model()->findAllByAttributes(array('criterio_fk' => $criterio), array('order' => 'id'));
                $model_processo_dados = Processo::carrega_tabela_subrisco($criterio);

                // testa se existe ações e critérios cadastrados para este ano 
                if ($criterio) {
                    if (!empty($model_criterio_dados)) { $criterio_ok = 1;}
                    if (!empty($model_processo_dados)) { $acao_ok = 1;}
                }

                if ($criterio_ok and $acao_ok) {

                    $html.="<div><table><tr><td>&nbsp;</td></tr></table></div><br>
                        <div><table border=0><tr><td align=center width=700><b>Anexo ".++$indice_anexo." - Avaliação de ".$tipo_criterio[nome_criterio]."</b></td></tr></table>";

                    // conta os tipos de ação para gerar o rowspan
                    $tipos_de_acao = null;
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


                    $contador = 1;
                    foreach ($model_processo_dados as $vetor) {
                        $soma_total = 0;
                        foreach ($model_criterio_dados as $vetor_criterio) {
                            $nota = Subrisco::model()->RecuperaNota($vetor[id], $vetor_criterio->id);
                            $soma_total += ($nota[0][numero_nota] * $vetor_criterio->valor_peso);
                        }
                        $vetor_total[$contador] = round(($soma_total / $peso_total), 2);
                        $contador++;
                    }

                    // monta cabeçalho da tabela                  
                    $html.="<table  class='tabela_atributo' width=800 align=center>";
                    $num_col = sizeof($model_criterio_dados);
                    if($num_col > 2){
	                    $html.="	<col width='50'></col>
                   			<col width='100'></col>"; 
	                    $num_col = sizeof($model_criterio_dados);
	                    $size_col = 322.0/$num_col;
	                    for($i=0; $i<$num_col; $i++){
       	                    $html.="	<col width='".$size_col."'></col>";
	                    }
	                    $html.="	<col width='20'></col>
                                        <col width='30'></col>";
                    } elseif($num_col == 1){
                    	$html.="	<col width='60'></col>
                			<col width='250'></col>
					<col width='142'></col>
                                        <col width='30'></col>
                                        <col width='40'></col>";                    	
                    } elseif($num_col == 2){
                    	$html.="	<col width='60'></col>
                                        <col width='210'></col>
                                        <col width='91'></col>
                                        <col width='91'></col>
                                        <col width='30'></col>
                                        <col width='40'></col>";
                    }
			$html.="                    		
                            <thead>
                            <tr>
                            <th class='th' >Tipo de Processo </th>
                            <th class='th' >Processo</th>";

                    foreach ($model_criterio_dados as $vetor) {
                        $html.=" <th class='th' > " . str_replace("(","<br>(",$vetor->nome_criterio) . " </th>";
                    }
                        $html.="
                            <th class='th' >Total</th>                                        
                            <th  class='th' >Grau de Risco</th>                    
                            </tr>
                            </thead>                    
                            <tbody>";

                    // gera linhas com as ações 
                    $contador = 1;
                    $encontrou_value = 0;
                    $imprime = null;
                    $class_bg = null;
                    foreach ($model_processo_dados as $vetor) {
                        $class_bg = ($class_bg == "even") ? "odd" : "even";
                        $html.= "<tr class='" . $class_bg . "'>";
                        if (!$imprime[$vetor[tipo_processo_fk]]) {
                            $html.= "<td align=center  rowspan=" . $tipos_de_acao[$vetor[tipo_processo_fk]] . ">" . str_replace("(","<br>(",$vetor[nome_tipo_processo]) . "</td>";
                            $imprime[$vetor[tipo_processo_fk]] = 1;
                        }
                        $html.="<td >" . $vetor[nome_processo] . "</td>";

                        // gera colunas com os critérios

                        foreach ($model_criterio_dados as $vetor_criterio) {
                            $value = Subrisco::model()->RecuperaNota($vetor[id], $vetor_criterio->id);
                            $value = $value[0]["numero_nota"];
                            $value = str_replace(".", ",", $value);
                            $value = (isset($value)) ? $value : "";
                            if ($value && $encontrou_value == 0) {
                                $encontrou_value = 1;
                            }
                            $html.=" <td align=center>{$value}</td>";
                        }
                        $html.="<td align=center>" . str_replace(".", ",", $vetor_total[$contador]) . "</td>" . // total
                               "<td  class='risco_" . $this->calcula_risco($vetor_total[$contador], 1) . "' align=center>" . $this->calcula_risco($vetor_total[$contador], 0) . "</td>" . // critério
                               "</tr>";
                        $contador++;
                    }
                    $html.="
                            </tbody>
                            </table>
                <br><br>

                <Table align=center style='width:300px;' ><tr><td valign='top'>
                <table class='tabela_atributo'>
                <thead>
                <tr>
                <th  class='th' style='width:100px;' >Legenda de Critérios </th><th class='th' style='width:80px;' >Pesos</th></tr>
                </thead>
                <tbody>";

            if (is_array($model_criterio_dados)) {
                foreach ($model_criterio_dados as $vetor) {
                    $class_bg = ($class_bg == "even") ? "odd" : "even";
                    $TipoCriterio = GxHtml::listDataEx(TipoCriterio::model()->findAllByAttributes(array('id' => $vetor->tipo_criterio_fk), array('order' => 'id')));
                    $html.="<tr class='" . $class_bg . "'><td>" .
                    $vetor->nome_criterio . " - " .
                    $TipoCriterio[$vetor->tipo_criterio_fk] .
                    "</td><td align=center>" . $vetor->valor_peso . "</td></tr>";
                }
            }
            $html.="
                </tbody>
                </table>
                            </td>
                 <td valign='top'>
                <table  class='tabela_atributo'>
                <thead>
                <tr>
                <th class='th' colspan='2'>Grau de Risco </th></tr>
                </thead>
                <tbody>
                    <tr class='risco_irrelevante'>
                        <td style='width:50px;' align=center>0--0,99</td>
                        <td style='width:50px;'  >Irrelevante</td>                
                    </tr>
                    <tr class='risco_baixo'>
                        <td align=center>1---1,99</td>
                        <td>Baixo</td>                
                    </tr>
                    <tr class='risco_medio'>
                        <td align=center>2---2,99</td>
                        <td>Médio</td>                
                    </tr>
                    <tr class='risco_alto'>
                        <td align=center>3--3,99</td>
                        <td>Alto</td>                
                    </tr>
                    <tr class='risco_critico'>
                        <td align=center>4---5</td>
                        <td>Crítico</td>                
                    </tr>            
                </tbody>
                </table>
                    </td></tr></table></div><br><br><br>";
            //<div style='page-break-after:always; clear:both'></div>
       }
    }

            // gera a primeira tabela com os critérios e descrições
            $html.="<br><div><p align=center><b>Anexo ".++$indice_anexo." - Escala dos Critérios de Avaliação </b></p>
                <table border=1>";   
            $contador=1;
            foreach ($model_criterio_todos as $criterio_todos) {
                $model_tipo_criterio_todos = TipoCriterio::model()->findByAttributes(array('id' => $criterio_todos->tipo_criterio_fk));
                 $html.="<tr><td  class='th' style='padding:10px;' colspan=7><span style='font-size:12px;text-align:center;'><b>" .$model_tipo_criterio_todos->nome_criterio. "</b></span></td></tr> 
                        <tr>
                            <td class='th'><font size=2><b> Critério</b></font></td>
                            <td class='th'><font size=2><b> Relação</b></font></td>
                            <td class='th' colspan=5><font size=2><b> Escala</b></font></td>
                        </tr>
                        ";
                 
                 if($contador==1){
                  $html.="<tr>
                            <td class='odd' rowspan=2 valign=middle> Avaliação de Riscos</td>
                            <td class='odd' rowspan=2  valign=middle align=center style='text-align:center;'> ( - / - )</td>
                            <td class='odd' colspan=5 style='text-align:center;'> Grau de risco do processo</td>
                        </tr>
                        <tr>
                            <td class='odd' width=100>0---0,99: irrelevante</td>
                            <td class='odd' >1---1,99: baixo</td>                            
                            <td class='odd' >2---2,99: médio</td>
                            <td class='odd' >3---3,99: alto</td>
                            <td class='odd' >4---5: crítico</td>
                        </tr>
                        <tr>
                            <td class='odd' rowspan=2 valign=middle> Denúncias e Recomendações de <br>Auditoria e Òrgãos de Controle</td>
                            <td class='odd' rowspan=2  valign=middle align=center style='text-align:center;'> ( - / - )</td>
                            <td class='odd' colspan=5 style='text-align:center;'> Volume de denúncias, recomendações de auditoria e de órgãos de controle</td>
                        </tr>
                        <tr>
                            <td class='odd' >0---0,99: irrelevante</td>
                            <td class='odd' >1---1,99: baixo</td>                            
                            <td class='odd' >2---2,99: médio</td>
                            <td class='odd' >3---3,99: alto</td>
                            <td class='odd' >4---5: crítico</td>
                        </tr>                        
                        <tr>
                            <td class='odd' rowspan=2 valign=middle> Auditorias Pretéritas</td>
                            <td class='odd' rowspan=2  valign=middle align=center style='text-align:center;'> ( - / + )</td>
                            <td class='odd' colspan=5 style='text-align:center;'> Frequência com que se realizam auditorias no tema</td>
                        </tr>
                        <tr>
                            <td class='odd' >0---0,99: sempre</td>
                            <td class='odd' >1---1,99: frequente</td>                            
                            <td class='odd' >2---2,99: as vezes</td>
                            <td class='odd' >3---3,99: raramente</td>
                            <td class='odd' >4---5: nunca</td>
                        </tr>";
                 }
                 if($contador==2){
                  $html.="<tr>
                            <td class='odd' rowspan=2 valign=middle> Realizado</td>
                            <td class='odd' rowspan=2  valign=middle align=center style='text-align:center;'> ( - / - )</td>
                            <td class='odd' colspan=5 style='text-align:center;'> Montante de recursos realizados no último exercício</td>
                        </tr>
                        <tr>
                            <td class='odd' >0---0,99: até <br> R$300.000</td>
                            <td class='odd' >1---1,99: de <br> R$300.001 a <br> R$1.000.000</td>                            
                            <td class='odd' >2---2,99: de <br> R$1.000.001 a <br> R$5.000.000</td>
                            <td class='odd' >3---3,99: de <br> R$5.000.001 a <br> R$30.000.000</td>
                            <td class='odd' >4---5: acima de <br> R$30.000.000 </td>
                            
                        </tr>";
                 }
                 if($contador==3){
                  $html.="<tr>
                            <td class='odd' rowspan=2 valign=middle> Grau de Comprometimento dos <br> Objetivos e Metas Estratégicas</td>
                            <td class='odd' rowspan=2  valign=middle align=center style='text-align:center;'> ( - / - )</td>
                            <td class='odd' colspan=5 style='text-align:center;'> Grau de relacionamento do processo com os objetivos estratégicos</td>
                        </tr>
                        <tr>
                            <td class='odd' width=100>0---0,99: muito baixo</td>
                            <td class='odd' >1---1,99: baixo</td>                            
                            <td class='odd' >2---2,99: médio</td>
                            <td class='odd' >3---3,99: alto</td>
                            <td class='odd' >4---5: extremo</td>
                        </tr>
                        <tr>
                            <td class='odd' rowspan=2 valign=middle> Risco à Imagem da Companhia</td>
                            <td class='odd' rowspan=2  valign=middle align=center style='text-align:center;'> ( - / - )</td>
                            <td class='odd' colspan=5 style='text-align:center;'> Grau de repercussão de problemas relacionados ao tema fora da Companhia</td>
                        </tr>
                        <tr>
                            <td class='odd' >0---0,99: irrelevante</td>
                            <td class='odd' >1---1,99: baixo</td>                            
                            <td class='odd' >2---2,99: médio</td>
                            <td class='odd' >3---3,99: grave</td>
                            <td class='odd' >4---5: gravíssimo</td>
                        </tr>";
                 }                 
                 $contador++;
            }            
            $html.="</table></div><br><br>";    
    }