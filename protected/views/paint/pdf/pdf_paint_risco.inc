<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo � parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  � um software livre; voc� pode redistribui-lo e/ou                   *
*  modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a vers�o 2 da                 *
*  Licen�a, como (a seu crit�rio) qualquer vers�o posterior.                    *
*                                                                               *
*  SIAUDI � distribu�do na expectativa de que seja �til,                        *
*  por�m, SEM NENHUMA GARANTIA; nem mesmo a garantia impl�cita                  *
*  de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.                *
*  Consulte a Licen�a P�blica Geral do GNU para mais detalhes em portugu�s:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU             *
*  junto com este programa; se n�o, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php
        $model_processo = new Processo;
        $model = new Subrisco;
        $exercicio = $_GET['exercicio'];
        $model_criterio_dados = Criterio::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio), array('order'=>'nome_criterio'));
        $model_processo_dados = Processo::carrega_tabela_risco($exercicio);
            
        if (!empty($model_criterio_dados)) { $criterio_ok = 1; }
        if (!empty($model_processo_dados)) { $acao_ok = 1; }            

         
        
        if ($criterio_ok and $acao_ok) {

            // conta os tipos de a��o para gerar o rowspan
            foreach ($model_processo_dados as $vetor) {
                $tipos_de_acao[$vetor[tipo_processo_fk]]++;
            }

            // calcula soma dos pesos para fazer m�dia composta
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
                $vetor_total[$contador] = round($vetor_soma[$contador] / $peso_total, 2);
                $contador++;
            }


            // define largua das colunas da tabela.
            // A largura total das colunas  de crit�rios, total grau de risco � 272.
            // Logo, a soma das colunas dos crit�rios + 2 = 272. 
            // Logo, valor da coluna = 272/(2 + n� crit�rios); 
            $numero_criterios = 2 + sizeof($model_criterio_dados);
            $largura_coluna = 272 / $numero_criterios;
            
            //gera cabe�alho das colunas
            $cabecalho_colunas="";
            for ($i=1; $i<=$largura_coluna; $i++){
                $cabecalho_colunas .= "<col width='$largura_coluna'></col>";
            }
            
            // monta cabe�alho da tabela                  
            $html="
            <div><span align=center>
            <table  class='tabela_atributo'>
            <col width='50'></col>
            <col width='200'></col>
            $cabecalho_colunas
                        
                <thead>
                    <tr>
                        <th class='th' >Tipo de Processo </th>
                        <th class='th'>Processo</th>";

                        foreach ($model_criterio_dados as $vetor) {
                            $html.= "<th class='th' align=center >". str_replace("(","<br>(",$vetor->nome_criterio) ."</th>";
                        }

                            $html.= "<th class='th'>Total</th>                                        
                        <th class='th'>Grau de Risco</th>                    
                    </tr>
                </thead>
                <tbody>";

                    // gera linhas com as a��es 
                    $contador = 1;
                    $encontrou_value = 0;
                    foreach ($model_processo_dados as $vetor) {
                        $class_bg = ($class_bg == 'even') ? 'odd' : 'even';
                        $html.="<tr>";
                        if (!$imprime[$vetor[tipo_processo_fk]]) {
                            $html.= "<td class='" . $class_bg . "' rowspan=" . $tipos_de_acao[$vetor[tipo_processo_fk]] . ">" . $vetor[nome_tipo_processo] . "</td>";
                            $imprime[$vetor[tipo_processo_fk]] = 1;
                        }
                        $html.= "<td class='" . $class_bg . "' >" . $vetor[nome_processo] . "</td>";

                        // gera colunas com os crit�rios
                        foreach ($model_criterio_dados as $vetor_criterio) {
                            $value = Risco::model()->RecuperaNota($vetor[id], $vetor_criterio->id);
                            $value = str_replace(".", ",", $value);
                            $value = ($value) ? $value : "";
                            if ($value && $encontrou_value == 0) {
                                $encontrou_value = 1;
                            }
                            $html.="<td  class='" . $class_bg . "' align=center>{$value}</td>";
                        }
                        $html.=
                        "<td  class='" . $class_bg . "' align=center>" . str_replace(".", ",", $vetor_total[$contador]) . "</td>" . // total
                        "<td  class='risco_".$this->calcula_risco($vetor_total[$contador],1)."' align=center>". $this->calcula_risco($vetor_total[$contador],0) ."</td>" . // crit�rio
                        "</tr>";
                        $contador++;
                    }
            $html.="
                </tbody>
            </table>
               </span>
               <br>
               <p align=center>
            <table align=center><tr><td width='400' align='center' valign='top'>
                        <table class='tabela_atributo' style='width:300px;'>
                            <thead>
                                <tr>
                                    <th id='criterio-grid_c0' class='th'>Legenda de Crit�rios </th><th class='th'>Pesos</th></tr>
                            </thead>
                            <tbody>";

                                if (is_array($model_criterio_dados)) {
                                    foreach ($model_criterio_dados as $vetor) {
                                        $class_bg = ($class_bg == "even") ? "odd" : "even";
                                        $TipoCriterio = GxHtml::listDataEx(TipoCriterio::model()->findAllByAttributes(array('id' => $vetor->tipo_criterio_fk)));
                                        $html.= "<tr class='" . $class_bg . "'><td>" .
                                        $vetor->nome_criterio . " - " .
                                        $TipoCriterio[$vetor->tipo_criterio_fk] .
                                        "</td><td align=center  style='padding: 3px;'>" . $vetor->valor_peso . "</td></tr>";
                                    }
                                }
                $html.=" 
                    </tbody>
                        </table>
                    </td>

                    <td width='400' align='center'  valign='top'>
                        <table  class='tabela_atributo' style='width:200px;'>
                            <thead>
                                <tr>
                                    <th id='criterio-grid_c0' colspan='2' class='th' >Grau de Risco </th></tr>
                            </thead>
                            <tbody>
                                <tr class='risco_irrelevante' >
                                    <td width='30%' align=center style='padding: 3px;'>0--0,99</td>
                                    <td style='padding: 3px;'>Irrelevante</td>                
                                </tr>
                                <tr class='risco_baixo'>
                                    <td align=center style='padding: 3px;'>1---1,99</td>
                                    <td style='padding: 3px;'>Baixo</td>                
                                </tr>
                                <tr class='risco_medio'>
                                    <td align=center style='padding: 3px;'>2---2,99</td>
                                    <td style='padding: 3px;'>M�dio</td>                
                                </tr>
                                <tr class='risco_alto'>
                                    <td align=center style='padding: 3px;'>3--3,99</td>
                                    <td style='padding: 3px;'>Alto</td>                
                                </tr>
                                <tr class='risco_critico'>
                                    <td align=center style='padding: 3px;'>4---5</td>
                                    <td style='padding: 3px;'>Cr�tico</td>                
                                </tr>            
                            </tbody>
                        </table>

                    </td></tr></table></p></div>
                    <br>";

        }        