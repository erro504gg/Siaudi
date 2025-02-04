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
        <div class="tabelaListagemItens">
            <table border="0" cellpadding="0" cellpadding="0" align="center">
                <tr>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº do Relatório</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Auditores</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Dias Úteis</th>
                </tr>
                <?php
                foreach ($dados as $vetorDados) {
                        $RelatorioAuditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $vetorDados['id']));
                        $PlanEspecifico= PlanEspecifico::model()->findByAttributes(array('id' => $vetorDados['plan_especifico_fk']));
                  ?>
                    <tr>
                        <td align="center">
                            <?php
                            echo $vetorDados['numero_relatorio'] . ' de ' . $vetorDados['data_relatorio'];
                            ?>
                        </td>
                        <td align="center"><?php
                            foreach ($RelatorioAuditor as $vetor) {
                                $Auditor = Usuario::model()->findByPk($vetor[usuario_fk]);
                                echo $Auditor->nome_usuario . '<br>';
                            }
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            if ($vetorDados['plan_especifico_fk']){
                                if (count($PlanEspecifico) > 1) {
                                    echo Feriado::model()->CalculaQtdeDiasUteis($PlanEspecifico[0]['data_inicio_atividade'], $vetorDados['data_gravacao']);
                                } else {
                                    echo Feriado::model()->CalculaQtdeDiasUteis($PlanEspecifico['data_inicio_atividade'], $vetorDados['data_gravacao']);
                                }
                            } else {
                                echo "(Sem planejamento específico vinculado)";
                            }
                            ?>
                        </td>

                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
<br><br>
<center>
    <a href="./RelatorioTempoExecucaoTrabalhoAuditorAjax" class="imitacaoBotao">Voltar</a>
</center>
