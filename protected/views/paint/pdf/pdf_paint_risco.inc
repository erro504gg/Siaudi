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
        $model_processo = new Processo;
        $model = new Subrisco;
        $exercicio = $_GET['exercicio'];
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
                $vetor_total[$contador] = round($vetor_soma[$contador] / $peso_total, 2);
                $contador++;
            }


            // define largua das colunas da tabela.
            // A largura total das colunas  de critérios, total grau de risco é 272.
            // Logo, a soma das colunas dos critérios + 2 = 272. 
            // Logo, valor da coluna = 272/(2 + nº critérios); 
            $numero_criterios = 2 + sizeof($model_criterio_dados);
            $largura_coluna = 272 / $numero_criterios;
            
            //gera cabeçalho das colunas
            $cabecalho_colunas="";
            for ($i=1; $i<=$largura_coluna; $i++){
                $cabecalho_colunas .= "<col width='$largura_coluna'></col>";
            }
            
            // monta cabeçalho da tabela                  
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

                    // gera linhas com as ações 
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

                        // gera colunas com os critérios
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
                        "<td  class='risco_".$this->calcula_risco($vetor_total[$contador],1)."' align=center>". $this->calcula_risco($vetor_total[$contador],0) ."</td>" . // critério
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
                                    <th id='criterio-grid_c0' class='th'>Legenda de Critérios </th><th class='th'>Pesos</th></tr>
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
                                    <td style='padding: 3px;'>Médio</td>                
                                </tr>
                                <tr class='risco_alto'>
                                    <td align=center style='padding: 3px;'>3--3,99</td>
                                    <td style='padding: 3px;'>Alto</td>                
                                </tr>
                                <tr class='risco_critico'>
                                    <td align=center style='padding: 3px;'>4---5</td>
                                    <td style='padding: 3px;'>Crítico</td>                
                                </tr>            
                            </tbody>
                        </table>

                    </td></tr></table></p></div>
                    <br>";

        }        