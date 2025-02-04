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
<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
        <table border="0" cellpadding="0" cellpadding="0" align="center">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº do Relatório</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Unidade Auditada</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº de Recomendações</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº de Recomendações Solucionadas</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Percentual Solucionado</th>
            </tr>
            <?php
            foreach ($dados as $vetor) {
                echo '<tr>';
                echo '<td align=center>' . $vetor['relatorio'] . '</td>';
                echo '<td align=center rowspan="1">' . $vetor['sigla'] . '</td>';
                echo '<td align=center rowspan="1">' . $vetor['total_recomendacao'] . '</td>';
                echo '<td align=center rowspan="1">' . $vetor['total_solucionadas'] . '</td>';
                $percentual_solucionado = round((($vetor['total_solucionadas'] / $vetor['total_recomendacao'])*100), 2);
                echo '<td align=center rowspan="1">' . $percentual_solucionado . '%</td>';
                echo '</tr>';
            }
            ?>
        </table>

    </div>

</div>

<br><br>
<center>
    <a href="./RecomendacaoSolucionadasAjax" class="imitacaoBotao">Voltar</a>
</center>




