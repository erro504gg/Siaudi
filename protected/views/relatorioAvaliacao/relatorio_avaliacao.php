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
<br><br><br><br>
 <div class="tabelaListagemItensWrapper">
	<div align="center" >
            <?php 
                echo "<b>Quadro de Acompanhamento das Avaliações dos Auditores - Exercício ".$valor_exercicio."</b>";
            
            ?>
	</div>
 <div class="tabelaListagemItens">
<table border="0" cellpadding="0" cellpadding="0" align="center">
	<tr>
    	<th align="center" bgcolor="#FFFFFF" rowspan="2">Questões</th>
            <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">
                <?php                
                    echo '<div align="center">'.$dados[0]['nome_usuario'].'</div>';
                ?>
            </th>
  	</tr>
        <tr>
            <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">
                <div align="center">Nota</div>
            </th>
	</tr>
        <?php 
            $media_total = 0.0;
            $notas_total = 0.0;
            foreach ($dados as $registro){
                echo '<tr>';
                echo '<td align=left>'.$registro['numero_questao']. ' - '.$registro['descricao_questao'].'</td>';
                echo '<td align=center rowspan="1" colspan="12">'.number_format($registro['nota_media'], 2, ',', '').'</td>';
                echo '</tr>';
                $notas_total += $registro['nota_media'];
            }
            $media_total = ($notas_total/sizeof($dados));
            echo '<tr>';
            echo '<td align=right>Média:</td>';
            echo '<td align=center rowspan="1" colspan="12">'.number_format($media_total, 2, ',', '').'</td>';
            echo '</tr>';
        ?>
</table>

</div><br>
    <table>
         <tr>
             <td valign="top">Dados Gerenciais:</td>
             <td align="left">
                 <?php 
                    echo "<b>Total de Avaliações: ".$total_avaliacao[0]['total_avaliacao']."</b><br>";
                    echo "<b>Total de Relatórios Homologados: ".$total_homologados[0]['total_relatorio_homologado']."</b><br>";
                    echo "<b>Total de Relatórios Homologados Avaliados: ".$total_homologados_avaliados[0]['total_relatorio_homologado_avaliado']."</b><br>";
                    echo "<b>Total de Relatórios Finalizados: ".$total_finalizados[0]['total_relatorio_finalizado']."</b><br>";
                    echo "<b>Total de Relatórios Finalizados Avaliados: ".$total_finalizados_avaliados[0]['total_relatorio_finalizado_avaliado']."</b><br>";
                 ?>
             </td>
         </tr>
     </table>

 </div>

    <br><br>
    <center>
        <a href="./" class="imitacaoBotao">Voltar</a>
    </center>

    



