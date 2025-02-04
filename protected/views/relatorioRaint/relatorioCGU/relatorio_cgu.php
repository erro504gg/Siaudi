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
<style type="text/css">
    p {
        display: inline;
    }
</style>
<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
        <div class="tabelaListagemItens">
            <table border="0" cellpadding="0" cellpadding="0" align="center">
                <tr>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº do Relatório</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Ações</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Valor</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Constatação</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="2">Recomendação / Estágio de Implementação</th>
                </tr>
                <?php
                foreach ($dados as $vetorDados) {
                    $rowspan = sizeof($vetorDados->recomendacaoFk);
                    $rowspan = ($rowspan==0)? 1: $rowspan ;
                    if ($rowspan!=0)
                        $vetorDados->recomendacaoFk = array_reverse($vetorDados->recomendacaoFk);
                    ?>
                    <tr>
                        <td align="center" rowspan="<?php echo $rowspan; ?>">
                            <?php echo Item::model()->ItemRelatorio($vetorDados->id,null,1) ?>
                            <br>
                            <?php echo "(" . RelatorioSureg::model()->sureg_por_relatorio($vetorDados->capituloFk->relatorioFk->id) . ")"; ?>
                        </td>
                        <td align="center" rowspan="<?php echo $rowspan; ?>">
                            <?php
                            $dados_objeto = Objeto::model()->findByAttributes(array('id' => $vetorDados['objeto_fk']));
                            echo $dados_objeto['nome_objeto'];
                            ?>
                        </td>                        
                        <td align="right" width="100" rowspan="<?php echo $rowspan; ?>"> <?=($vetorDados['valor_reais'])? 'R$ '. $vetorDados['valor_reais']:"não se aplica"; ?> </td>
                        <td align="left" rowspan="<?php echo $rowspan; ?>"> <?php echo $vetorDados['nome_item']; ?> </td>
                                <?php 
                                $iteracao_rowspan=1;
                                foreach ($vetorDados->recomendacaoFk as $key => $recomendacao): 
                                    if ($iteracao_rowspan==1){
                                        echo "<td width=500 align='left'>$vetorDados->numero_item.$recomendacao->numero_recomendacao - $recomendacao->descricao_recomendacao </td>
                                              <td align=center width=50>".Resposta::model()->FollowUp_Ultimo_Status($recomendacao->id)."</td></tr>";
                                    } else {
                                        echo "<tr><td width=500 align='left'>$vetorDados->numero_item.$recomendacao->numero_recomendacao - $recomendacao->descricao_recomendacao</td>
                                                  <td align=center width=50>".Resposta::model()->FollowUp_Ultimo_Status($recomendacao->id)."</td>
                                              </tr>";
                                    }
                                    $iteracao_rowspan++;
                                 endforeach; 
                                 if (sizeof($vetorDados->recomendacaoFk)==0){
                                     echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
                                 }
                 } ?>
            </table>
        </div>
    </div>
</div>

<br><br>
<center>
    <a href="./RelatorioCGUAjax" class="imitacaoBotao">Voltar</a>
</center>
